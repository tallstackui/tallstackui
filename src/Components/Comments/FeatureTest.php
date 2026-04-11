<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;
use TallStackUi\Components\Comments\Component as Comments;
use TallStackUi\Livewire\Comments\Component as CommentsComponent;

uses(TestCase::class)->group('Feature');

beforeEach(function () {
    if (! Schema::hasTable('feature_posts')) {
        Schema::create('feature_posts', function (Blueprint $table): void {
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
});

it('can render comments component', function () {
    $post = new class extends Model
    {
        use \TallStackUi\Comments\Concerns\HasComments;

        protected $table = 'feature_posts';

        protected $guarded = [];
    };

    $post->forceFill(['title' => 'Post'])->save();

    expect('<x-comments :model="$post" />')
        ->render(['post' => $post])
        ->toContain('Comments')
        ->toContain('TallStackUi\Livewire\Comments\Component::class');

    $component = new Comments(model: $post);

    Livewire::test(CommentsComponent::class, [
        'model' => $post,
        'settings' => __ts_get_component_configuration(Comments::class),
        'classes' => $component->classes(),
    ])->assertSeeHtml('rounded-2xl');
});
