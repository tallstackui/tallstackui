@php
    $isReply = $depth > 0;
    $replies = $comment->repliesTree ?? collect();
    $hasReplies = $replies->isNotEmpty();
@endphp

<article @class([
    data_get($classes, 'comment.article'),
    data_get($classes, 'comment.reply') => $isReply,
]) wire:key="comment-{{ $comment->getKey() }}">
    <div class="{{ data_get($classes, 'comment.header') }}">
        <div class="{{ data_get($classes, 'comment.author') }}">
            <div @class([
                data_get($classes, 'comment.avatar'),
                data_get($classes, 'comment.reply_avatar') => $isReply,
            ])>
                {{ $comment->authorInitials() }}
            </div>

            <div class="{{ data_get($classes, 'comment.identity') }}">
                <div class="{{ data_get($classes, 'comment.name_row') }}">
                    <p class="{{ data_get($classes, 'comment.name') }}">{{ $comment->authorName() }}</p>

                    @if ($isReply)
                        <x-badge light round color="secondary" :text="trans('ts-ui::messages.comments.actions.reply')" />
                    @endif
                </div>

                <div class="{{ data_get($classes, 'comment.meta') }}">
                    <span class="{{ data_get($classes, 'comment.meta_pill') }}">{{ $comment->created_at?->diffForHumans() }}</span>

                    @if ($comment->edited_at)
                        <span class="{{ data_get($classes, 'comment.meta_pill') }}">
                            <x-icon icon="pencil" class="h-3.5 w-3.5" />
                            {{ trans('ts-ui::messages.comments.edited') }}
                        </span>
                    @endif

                    @if ($comment->status === \TallStackUi\Comments\Models\Comment::STATUS_PENDING)
                        <x-badge color="amber" light text="{{ trans('ts-ui::messages.comments.pending') }}" />
                    @endif
                </div>
            </div>
        </div>

        @if ($this->canModerate($comment) && $comment->status === \TallStackUi\Comments\Models\Comment::STATUS_PENDING)
            <div class="{{ data_get($classes, 'comment.moderation') }}">
                <x-button xs
                          icon="check"
                          round
                          wire:click="approve({{ $comment->getKey() }})"
                          :text="trans('ts-ui::messages.comments.actions.approve')" />
            </div>
        @endif
    </div>

    @if ($editing[$comment->getKey()] ?? false)
        <div class="{{ data_get($classes, 'comment.editor') }}">
            <div class="{{ data_get($classes, 'comment.editor_title') }}">
                <x-icon icon="pencil-square" class="h-4 w-4" />
                {{ trans('ts-ui::messages.comments.actions.edit') }}
            </div>

            <x-textarea wire:model.live="editingBodies.{{ $comment->getKey() }}"
                        :label="trans('ts-ui::messages.comments.actions.edit')"
                        :rows="3"
                        invalidate />
            <div class="flex flex-wrap gap-2">
                <x-button sm
                          icon="check"
                          round
                          wire:click="updateComment({{ $comment->getKey() }})"
                          loading="updateComment"
                          :text="trans('ts-ui::messages.comments.actions.save')" />
                <x-button sm
                          flat
                          color="secondary"
                          icon="x-mark"
                          round
                          wire:click="cancelEdit({{ $comment->getKey() }})"
                          :text="trans('ts-ui::messages.comments.actions.cancel')" />
            </div>
        </div>
    @else
        <div class="{{ data_get($classes, 'comment.body') }}">{{ $comment->body }}</div>
    @endif

    <div class="{{ data_get($classes, 'comment.actions') }}">
        @if ($this->canReply($comment))
            <x-button xs
                      flat
                      icon="arrow-turn-up-right"
                      round
                      wire:click="startReply({{ $comment->getKey() }})"
                      :text="trans('ts-ui::messages.comments.actions.reply')" />
        @endif

        @if ($this->canEdit($comment))
            <x-button xs
                      flat
                      color="secondary"
                      icon="pencil"
                      round
                      wire:click="startEdit({{ $comment->getKey() }})"
                      :text="trans('ts-ui::messages.comments.actions.edit')" />
        @endif

        @if ($this->canDelete($comment))
            <x-button xs
                      flat
                      color="red"
                      icon="trash"
                      round
                      wire:click="deleteComment({{ $comment->getKey() }})"
                      :text="trans('ts-ui::messages.comments.actions.delete')" />
        @endif

        @if ($hasReplies)
            <span class="{{ data_get($classes, 'comment.counter') }}">
                <x-icon icon="chat-bubble-left-ellipsis" class="h-3.5 w-3.5" />
                {{ $replies->count() }}
            </span>
        @endif
    </div>

    @if ($replying[$comment->getKey()] ?? false)
        <div class="{{ data_get($classes, 'comment.editor') }}">
            <div class="{{ data_get($classes, 'comment.editor_title') }}">
                <x-icon icon="arrow-turn-up-right" class="h-4 w-4" />
                {{ trans('ts-ui::messages.comments.actions.reply') }}
            </div>

            <x-textarea wire:model.live="replyBodies.{{ $comment->getKey() }}"
                        :label="trans('ts-ui::messages.comments.actions.reply')"
                        :rows="3"
                        invalidate />
            <div class="flex flex-wrap gap-2">
                <x-button sm
                          icon="chat-bubble-left-ellipsis"
                          round
                          wire:click="reply({{ $comment->getKey() }})"
                          loading="reply"
                          :text="trans('ts-ui::messages.comments.actions.send_reply')" />
                <x-button sm
                          flat
                          color="secondary"
                          icon="x-mark"
                          round
                          wire:click="startReply({{ $comment->getKey() }})"
                          :text="trans('ts-ui::messages.comments.actions.cancel')" />
            </div>
        </div>
    @endif

    @if ($hasReplies)
        <div class="{{ data_get($classes, 'comment.thread') }}">
            @foreach ($replies as $reply)
                @include('ts-ui::livewire.comments.comment', ['comment' => $reply, 'depth' => $depth + 1])
            @endforeach
        </div>
    @endif
</article>
