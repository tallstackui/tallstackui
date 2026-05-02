<?php

namespace TallStackUi\Livewire\Comments;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Component as LivewireComponent;
use Livewire\WithPagination;
use TallStackUi\Comments\Models\Comment;

class Component extends LivewireComponent
{
    use WithPagination;

    public Model $model;

    public array $settings = [];

    public array $classes = [];

    public string $sort = 'latest';

    public string $body = '';

    public array $guest = [
        'name' => '',
        'email' => '',
        'website' => '',
    ];

    public array $replying = [];

    public array $replyBodies = [];

    public array $editing = [];

    public array $editingBodies = [];

    public function mount(Model $model, array $settings = [], array $classes = []): void
    {
        $this->model = $model;
        $this->settings = $settings;
        $this->classes = Arr::undot($classes);
        $this->sort = data_get($settings, 'sorting.default', 'latest');

        if ($this->guestMode()) {
            $this->guest = array_merge($this->guest, session($this->guestSessionKey().'.profile', []));
        }
    }

    #[Computed]
    public function comments(): LengthAwarePaginator
    {
        $query = $this->applySort(
            $this->applyVisibility(
                $this->commentQuery()->roots()
            )
        );

        $paginator = $query->paginate(
            perPage: (int) data_get($this->settings, 'pagination.per_page', 10),
            pageName: 'commentsPage'
        );

        $paginator->getCollection()->transform(fn (Comment $comment): Comment => $this->appendReplies($comment));

        return $paginator;
    }

    #[Computed]
    public function total(): int
    {
        return $this->commentQuery()->approved()->count();
    }

    public function comment(): void
    {
        $this->ensureCanCreate('comment');

        $validated = $this->validateCommentPayload();

        $comment = $this->newComment();
        $comment->fill($this->makePayload($validated['body']));
        $comment->commentable()->associate($this->model);
        $comment->save();

        $this->afterCommentCreated($comment);
        $this->body = '';
        $this->resetPage('commentsPage');
    }

    public function startReply(int $comment): void
    {
        $this->replying[$comment] = ! ($this->replying[$comment] ?? false);

        if ($this->replying[$comment] && ! isset($this->replyBodies[$comment])) {
            $this->replyBodies[$comment] = '';
        }
    }

    public function reply(int $comment): void
    {
        $parent = $this->findComment($comment);

        if (! $parent || ! $this->canReply($parent)) {
            throw ValidationException::withMessages([
                'reply' => trans('ts-ui::messages.comments.errors.reply'),
            ]);
        }

        $rules = [
            "replyBodies.$comment" => ['required', 'string', 'min:'.data_get($this->settings, 'editor.min', 3), 'max:'.data_get($this->settings, 'editor.max', 5000)],
        ];

        if ($this->guestMode()) {
            if ((bool) data_get($this->settings, 'guest_fields.name', true)) {
                $rules['guest.name'] = ['required', 'string', 'max:255'];
            }

            if ((bool) data_get($this->settings, 'guest_fields.email', true)) {
                $rules['guest.email'] = ['required', 'email', 'max:255'];
            }

            if ((bool) data_get($this->settings, 'guest_fields.website', false)) {
                $rules['guest.website'] = ['nullable', 'url', 'max:255'];
            }
        }

        $validated = $this->validate($rules);

        $reply = $this->newComment();
        $reply->fill($this->makePayload($validated['replyBodies'][$comment], $parent));
        $reply->commentable()->associate($this->model);
        $reply->parent()->associate($parent);
        $reply->save();

        $this->afterCommentCreated($reply);
        $this->replying[$comment] = false;
        $this->replyBodies[$comment] = '';
    }

    public function startEdit(int $comment): void
    {
        $instance = $this->findComment($comment);

        if (! $instance || ! $this->canEdit($instance)) {
            return;
        }

        $this->editing[$comment] = true;
        $this->editingBodies[$comment] = $instance->body;
    }

    public function cancelEdit(int $comment): void
    {
        $this->editing[$comment] = false;
        unset($this->editingBodies[$comment]);
    }

    public function updateComment(int $comment): void
    {
        $instance = $this->findComment($comment);

        if (! $instance || ! $this->canEdit($instance)) {
            throw ValidationException::withMessages([
                'edit' => trans('ts-ui::messages.comments.errors.edit'),
            ]);
        }

        $validated = $this->validate([
            "editingBodies.$comment" => ['required', 'string', 'min:'.data_get($this->settings, 'editor.min', 3), 'max:'.data_get($this->settings, 'editor.max', 5000)],
        ]);

        $payload = [
            'body' => trim($validated['editingBodies'][$comment]),
            'edited_at' => now(),
        ];

        if ((bool) data_get($this->settings, 'moderation.reapprove_on_edit', false)) {
            $payload['status'] = Comment::STATUS_PENDING;
            $payload['approved_at'] = null;
        }

        $instance->update($payload);

        $this->editing[$comment] = false;
        unset($this->editingBodies[$comment]);
    }

    public function deleteComment(int $comment): void
    {
        $instance = $this->findComment($comment);

        if (! $instance || ! $this->canDelete($instance)) {
            throw ValidationException::withMessages([
                'delete' => trans('ts-ui::messages.comments.errors.delete'),
            ]);
        }

        $instance->delete();
    }

    public function approve(int $comment): void
    {
        $instance = $this->findComment($comment, false);

        if (! $instance || ! $this->canModerate($instance)) {
            return;
        }

        $instance->update([
            'status' => Comment::STATUS_APPROVED,
            'approved_at' => now(),
        ]);
    }

    public function render(): View
    {
        return view('ts-ui::livewire.comments');
    }

    public function authMode(): bool
    {
        return (bool) data_get($this->settings, 'mode.auth', true);
    }

    public function guestMode(): bool
    {
        return (bool) data_get($this->settings, 'mode.guest', false);
    }

    public function canReply(Comment $comment): bool
    {
        if (! $this->threadingEnabled() || ! (bool) data_get($this->settings, 'actions.reply', true)) {
            return false;
        }

        if ($comment->depth + 1 >= (int) data_get($this->settings, 'threading.max_depth', 3)) {
            return false;
        }

        $authorized = $this->resolveCustomAuthorization('reply', $comment);

        return $authorized ?? $this->resolveAction('comment');
    }

    public function canEdit(Comment $comment): bool
    {
        if (! (bool) data_get($this->settings, 'actions.edit', true)) {
            return false;
        }

        $authorized = $this->resolveCustomAuthorization('edit', $comment);

        if ($authorized !== null) {
            return $authorized;
        }

        if (! $this->owns($comment)) {
            return $this->canModerate($comment);
        }

        $window = data_get($this->settings, 'ownership.edit_window_in_minutes');

        return $window === null || $comment->created_at?->addMinutes((int) $window)->isFuture() === true;
    }

    public function canDelete(Comment $comment): bool
    {
        if (! (bool) data_get($this->settings, 'actions.delete', true)) {
            return false;
        }

        $authorized = $this->resolveCustomAuthorization('delete', $comment);

        return $authorized ?? ($this->owns($comment) || $this->canModerate($comment));
    }

    public function canModerate(?Comment $comment = null): bool
    {
        if (! (bool) data_get($this->settings, 'moderation.enabled', false)) {
            return false;
        }

        return $this->resolveCustomAuthorization('moderate', $comment) ?? false;
    }

    protected function applySort(Builder $query): Builder
    {
        return match ($this->sort) {
            'oldest' => $query->oldest('created_at'),
            'popular' => $query->withCount('replies')->orderByDesc('replies_count')->latest('created_at'),
            default => $query->latest('created_at'),
        };
    }

    protected function applyVisibility(Builder $query): Builder
    {
        if ($this->canModerate()) {
            return $query;
        }

        return $query->where(function (Builder $builder): void {
            $builder->where('status', Comment::STATUS_APPROVED);

            if (! (bool) data_get($this->settings, 'visibility.show_pending_to_owner', true)) {
                return;
            }

            if ($this->authMode() && Auth::check()) {
                $builder->orWhere(function (Builder $nested): void {
                    $nested->where('status', Comment::STATUS_PENDING)
                        ->where('commenter_type', Auth::user()::class)
                        ->where('commenter_id', Auth::id());
                });
            }

            if ($this->guestMode() && $ids = $this->guestCommentIds()) {
                $builder->orWhere(function (Builder $nested) use ($ids): void {
                    $nested->where('status', Comment::STATUS_PENDING)
                        ->whereIn('id', $ids);
                });
            }
        });
    }

    protected function appendReplies(Comment $comment, int $depth = 1): Comment
    {
        if (! $this->threadingEnabled() || $depth > (int) data_get($this->settings, 'threading.max_depth', 3)) {
            $comment->setRelation('repliesTree', collect());

            return $comment;
        }

        $replies = $this->applyVisibility(
            $this->commentQuery()->where('parent_id', $comment->getKey())->oldest('created_at')
        )->get();

        $replies->each(fn (Comment $reply): Comment => $this->appendReplies($reply, $depth + 1));

        $comment->setRelation('repliesTree', $replies);

        return $comment;
    }

    protected function commentQuery(): Builder
    {
        return $this->newComment()->newQuery()
            ->whereMorphedTo('commentable', $this->model)
            ->with('commenter');
    }

    protected function newComment(): Comment
    {
        $class = data_get($this->settings, 'models.comment', Comment::class);

        return new $class();
    }

    protected function makePayload(string $body, ?Comment $parent = null): array
    {
        $approved = $this->shouldAutoApprove();

        $payload = [
            'body' => trim($body),
            'status' => $approved ? Comment::STATUS_APPROVED : Comment::STATUS_PENDING,
            'approved_at' => $approved ? now() : null,
            'depth' => $parent ? $parent->depth + 1 : 0,
            'ip_address' => request()->ip(),
            'user_agent' => Str::limit((string) request()->userAgent(), 1000, ''),
        ];

        if ($this->authMode()) {
            $payload['commenter_type'] = Auth::user()::class;
            $payload['commenter_id'] = Auth::id();
        }

        if ($this->guestMode()) {
            $payload['guest_name'] = trim($this->guest['name']);
            $payload['guest_email'] = trim($this->guest['email']);
            $payload['guest_website'] = blank($this->guest['website']) ? null : trim($this->guest['website']);
        }

        return $payload;
    }

    protected function validateCommentPayload(): array
    {
        $rules = [
            'body' => ['required', 'string', 'min:'.data_get($this->settings, 'editor.min', 3), 'max:'.data_get($this->settings, 'editor.max', 5000)],
        ];

        if ($this->guestMode()) {
            if ((bool) data_get($this->settings, 'guest_fields.name', true)) {
                $rules['guest.name'] = ['required', 'string', 'max:255'];
            }

            if ((bool) data_get($this->settings, 'guest_fields.email', true)) {
                $rules['guest.email'] = ['required', 'email', 'max:255'];
            }

            if ((bool) data_get($this->settings, 'guest_fields.website', false)) {
                $rules['guest.website'] = ['nullable', 'url', 'max:255'];
            }
        }

        return $this->validate($rules);
    }

    protected function afterCommentCreated(Comment $comment): void
    {
        if ($this->guestMode()) {
            session()->put($this->guestSessionKey().'.profile', $this->guest);
            session()->push($this->guestSessionKey().'.comments', $comment->getKey());
        }
    }

    protected function findComment(int $comment, bool $visible = true): ?Comment
    {
        $query = $this->commentQuery()->whereKey($comment);

        if ($visible) {
            $query = $this->applyVisibility($query);
        }

        return $query->first();
    }

    protected function ensureCanCreate(string $action): void
    {
        if (! $this->resolveAction($action)) {
            throw ValidationException::withMessages([
                'body' => trans('ts-ui::messages.comments.errors.comment'),
            ]);
        }
    }

    protected function resolveAction(string $action): bool
    {
        if (! (bool) data_get($this->settings, "actions.$action", true) && $action !== 'comment') {
            return false;
        }

        $authorized = $this->resolveCustomAuthorization($action);

        if ($authorized !== null) {
            return $authorized;
        }

        if ($this->authMode()) {
            return Auth::check();
        }

        return true;
    }

    protected function resolveCustomAuthorization(string $action, ?Comment $comment = null): ?bool
    {
        $callable = data_get($this->settings, "authorization.$action");

        if ($callable === null || $callable === false) {
            return $callable;
        }

        if (is_string($callable) && str_contains($callable, '@')) {
            [$class, $method] = explode('@', $callable);

            return (bool) app($class)->{$method}(Auth::user(), $this->model, $comment, $this);
        }

        if (is_string($callable)) {
            return (bool) app($callable)(Auth::user(), $this->model, $comment, $this);
        }

        return (bool) app()->call($callable, [
            'user' => Auth::user(),
            'model' => $this->model,
            'comment' => $comment,
            'component' => $this,
        ]);
    }

    protected function owns(Comment $comment): bool
    {
        if ($this->authMode()) {
            return Auth::check()
                && $comment->commenter_type === Auth::user()::class
                && (string) $comment->commenter_id === (string) Auth::id();
        }

        return in_array($comment->getKey(), $this->guestCommentIds(), true);
    }

    protected function shouldAutoApprove(): bool
    {
        if (! (bool) data_get($this->settings, 'moderation.require_approval', false)) {
            return true;
        }

        return $this->authMode()
            ? (bool) data_get($this->settings, 'moderation.auto_approve_authenticated', true)
            : (bool) data_get($this->settings, 'moderation.auto_approve_guests', false);
    }

    protected function guestCommentIds(): array
    {
        return array_map('intval', session($this->guestSessionKey().'.comments', []));
    }

    protected function guestSessionKey(): string
    {
        return (string) data_get($this->settings, 'ownership.guest_session_key', 'tallstackui.comments.guest');
    }

    protected function threadingEnabled(): bool
    {
        return (bool) data_get($this->settings, 'threading.enabled', true);
    }
}
