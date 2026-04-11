<?php

namespace TallStackUi\Comments\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use TallStackUi\Comments\Models\Comment;

trait HasComments
{
    public function allComments(): MorphMany
    {
        return $this->morphMany($this->commentModel(), 'commentable');
    }

    public function comments(): MorphMany
    {
        return $this->allComments()
            ->whereNull('parent_id')
            ->latest('created_at');
    }

    public function approvedComments(): MorphMany
    {
        return $this->allComments()
            ->approved()
            ->whereNull('parent_id')
            ->latest('created_at');
    }

    public function commentsConfiguration(): array
    {
        return [];
    }

    protected function commentModel(): string
    {
        return __ts_get_component_configuration(\TallStackUi\Components\Comments\Component::class, 'models')['comment']
            ?? Comment::class;
    }
}
