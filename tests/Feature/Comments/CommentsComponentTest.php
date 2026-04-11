<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use TallStackUi\Comments\Concerns\HasComments;
use TallStackUi\Comments\Models\Comment;
use TallStackUi\Livewire\Comments\Component as CommentsComponent;

class CommentsTestUser extends Authenticatable
{
    protected $table = 'comments_test_users';

    protected $guarded = [];
}

class CommentsTestPost extends Model
{
    use HasComments;

    protected $table = 'comments_test_posts';

    protected $guarded = [];
}

beforeEach(function () {
    config()->set('auth.providers.users.model', CommentsTestUser::class);
    config()->set('database.default', 'testing');

    if (! Schema::hasTable('comments_test_users')) {
        Schema::create('comments_test_users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });
    }

    if (! Schema::hasTable('comments_test_posts')) {
        Schema::create('comments_test_posts', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });
    }

    if (! Schema::hasTable('tallstackui_comments')) {
        Schema::create('tallstackui_comments', function (Blueprint $table): void {
            $table->id();
            $table->nullableMorphs('commenter');
            $table->morphs('commentable');
            $table->foreignId('parent_id')->nullable()->constrained('tallstackui_comments')->cascadeOnDelete();
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_website')->nullable();
            $table->longText('body');
            $table->string('status', 20)->default('approved')->index();
            $table->unsignedInteger('depth')->default(0);
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('edited_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    Comment::query()->forceDelete();
    CommentsTestPost::query()->delete();
    CommentsTestUser::query()->delete();
});

function commentsSettings(array $overrides = []): array
{
    return array_replace_recursive(__ts_get_component_configuration(\TallStackUi\Components\Comments\Component::class), $overrides);
}

it('can create comments in auth mode', function () {
    $user = CommentsTestUser::query()->create(['name' => 'Jane']);
    $post = CommentsTestPost::query()->create(['title' => 'Post']);

    $this->actingAs($user);

    Livewire::test(CommentsComponent::class, [
        'model' => $post,
        'settings' => commentsSettings(),
        'classes' => [],
    ])
        ->set('body', 'First comment')
        ->call('comment')
        ->assertSet('body', '');

    expect(Comment::query()->count())->toBe(1)
        ->and(Comment::query()->first()->body)->toBe('First comment')
        ->and(Comment::query()->first()->commenter_id)->toBe($user->getKey());
});

it('can reply edit and delete owned comments', function () {
    $user = CommentsTestUser::query()->create(['name' => 'Jane']);
    $post = CommentsTestPost::query()->create(['title' => 'Post']);

    $this->actingAs($user);

    $comment = Comment::query()->create([
        'commentable_type' => $post::class,
        'commentable_id' => $post->getKey(),
        'commenter_type' => $user::class,
        'commenter_id' => $user->getKey(),
        'body' => 'Root comment',
        'status' => Comment::STATUS_APPROVED,
        'approved_at' => now(),
    ]);

    Livewire::test(CommentsComponent::class, [
        'model' => $post,
        'settings' => commentsSettings(),
        'classes' => [],
    ])
        ->set("replyBodies.{$comment->getKey()}", 'Reply comment')
        ->call('reply', $comment->getKey())
        ->call('startEdit', $comment->getKey())
        ->set("editingBodies.{$comment->getKey()}", 'Edited root comment')
        ->call('updateComment', $comment->getKey());

    expect(Comment::query()->count())->toBe(2)
        ->and($comment->fresh()->body)->toBe('Edited root comment');

    $reply = Comment::query()->whereNotNull('parent_id')->firstOrFail();

    Livewire::test(CommentsComponent::class, [
        'model' => $post,
        'settings' => commentsSettings(),
        'classes' => [],
    ])->call('deleteComment', $reply->getKey());

    expect(Comment::query()->whereKey($reply->getKey())->exists())->toBeFalse();
});

it('supports guest mode with moderation', function () {
    $post = CommentsTestPost::query()->create(['title' => 'Post']);

    Livewire::test(CommentsComponent::class, [
        'model' => $post,
        'settings' => commentsSettings([
            'mode' => ['auth' => false, 'guest' => true],
            'moderation' => ['require_approval' => true, 'auto_approve_guests' => false],
        ]),
        'classes' => [],
    ])
        ->set('guest.name', 'Guest User')
        ->set('guest.email', 'guest@example.com')
        ->set('body', 'Guest comment')
        ->call('comment');

    $comment = Comment::query()->firstOrFail();

    expect($comment->status)->toBe(Comment::STATUS_PENDING)
        ->and($comment->guest_name)->toBe('Guest User');
});
