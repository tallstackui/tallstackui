<?php

namespace TallStackUi\Comments\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use TallStackUi\Components\Comments\Component as CommentsComponent;

class Comment extends Model
{
    use SoftDeletes;

    public const STATUS_APPROVED = 'approved';

    public const STATUS_PENDING = 'pending';

    protected $fillable = [
        'parent_id',
        'commentable_type',
        'commentable_id',
        'commenter_type',
        'commenter_id',
        'guest_name',
        'guest_email',
        'guest_website',
        'body',
        'status',
        'depth',
        'approved_at',
        'edited_at',
        'ip_address',
        'user_agent',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'edited_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function getTable(): string
    {
        return __ts_get_component_configuration(CommentsComponent::class, 'table') ?? parent::getTable();
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function commenter(): MorphTo
    {
        return $this->morphTo();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('created_at');
    }

    public function scopeApproved(Builder $query): void
    {
        $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePending(Builder $query): void
    {
        $query->where('status', self::STATUS_PENDING);
    }

    public function scopeRoots(Builder $query): void
    {
        $query->whereNull('parent_id');
    }

    public function authorName(): string
    {
        return $this->guest_name
            ?? data_get($this->commenter, 'name')
            ?? data_get($this->commenter, 'username')
            ?? 'Guest';
    }

    public function authorInitials(): string
    {
        return Str::of($this->authorName())
            ->explode(' ')
            ->filter()
            ->take(2)
            ->map(fn (string $part): string => Str::upper(Str::substr($part, 0, 1)))
            ->implode('');
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }
}
