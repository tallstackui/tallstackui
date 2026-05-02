<?php

namespace TallStackUi\Components\Comments;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Comments\Concerns\HasComments;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('comments')]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public Model $model,
        public ?int $perPage = null,
        public ?string $sort = null,
        public ?bool $reply = null,
        public ?bool $guest = null,
        public ?bool $auth = null,
        public ?bool $edit = null,
        public ?bool $delete = null,
        public ?bool $moderation = null,
        public ?bool $approval = null,
        public ?int $maxDepth = null,
        #[SkipDebug]
        public ?array $settings = null,
    ) {
        $configuration = __ts_get_component_configuration(static::class) ?? [];
        $overrides = method_exists($this->model, 'commentsConfiguration') ? $this->model->commentsConfiguration() : [];

        $this->settings = array_replace_recursive($configuration, $overrides);

        data_set($this->settings, 'pagination.per_page', $this->perPage ?? data_get($this->settings, 'pagination.per_page'));
        data_set($this->settings, 'sorting.default', $this->sort ?? data_get($this->settings, 'sorting.default'));
        data_set($this->settings, 'actions.reply', $this->reply ?? data_get($this->settings, 'actions.reply'));
        data_set($this->settings, 'actions.edit', $this->edit ?? data_get($this->settings, 'actions.edit'));
        data_set($this->settings, 'actions.delete', $this->delete ?? data_get($this->settings, 'actions.delete'));
        data_set($this->settings, 'mode.guest', $this->guest ?? data_get($this->settings, 'mode.guest'));
        data_set($this->settings, 'mode.auth', $this->auth ?? data_get($this->settings, 'mode.auth'));
        data_set($this->settings, 'moderation.enabled', $this->moderation ?? data_get($this->settings, 'moderation.enabled'));
        data_set($this->settings, 'moderation.require_approval', $this->approval ?? data_get($this->settings, 'moderation.require_approval'));
        data_set($this->settings, 'threading.max_depth', $this->maxDepth ?? data_get($this->settings, 'threading.max_depth'));
    }

    public function blade(): View
    {
        return view('ts-ui::components.comments.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'container' => 'space-y-3',
            'header' => [
                'wrapper' => 'flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between',
                'summary' => 'flex min-w-0 items-center gap-3',
                'icon' => 'flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-600 ring-1 ring-primary-100 dark:bg-primary-500/10 dark:text-primary-300 dark:ring-primary-500/20',
                'content' => 'min-w-0 space-y-0.5',
                'title_row' => 'flex items-center gap-2',
                'title' => 'text-base font-semibold text-gray-900 dark:text-dark-100',
                'description' => 'text-xs text-gray-500 dark:text-dark-300',
                'toolbar' => 'w-full sm:w-auto sm:min-w-[11rem]',
                'toolbar_wrapper' => 'flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50/80 px-3 py-2 dark:border-dark-600 dark:bg-dark-800/80',
                'toolbar_label' => 'text-xs font-medium text-gray-500 dark:text-dark-300',
                'toolbar_select' => 'min-w-0 flex-1 sm:w-44',
            ],
            'state' => [
                'empty' => [
                    'wrapper' => 'flex flex-col items-center gap-3 rounded-2xl border border-dashed border-gray-300 bg-gray-50/80 px-6 py-10 text-center dark:border-dark-600 dark:bg-dark-700/40',
                    'icon' => 'flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-primary-500 ring-1 ring-gray-200 dark:bg-dark-800 dark:text-primary-300 dark:ring-dark-600',
                    'text' => 'max-w-md text-sm leading-6 text-gray-500 dark:text-dark-300',
                ],
                'locked' => [
                    'wrapper' => 'flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50/90 px-4 py-4 dark:border-amber-500/20 dark:bg-amber-500/10',
                    'icon' => 'mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-white text-amber-500 ring-1 ring-amber-200 dark:bg-dark-800 dark:text-amber-300 dark:ring-amber-500/20',
                    'text' => 'text-sm leading-6 text-amber-700 dark:text-amber-200',
                ],
            ],
            'form' => [
                'wrapper' => 'space-y-3 rounded-2xl border border-gray-200 bg-white p-3 shadow-xs dark:border-dark-600 dark:bg-dark-700 sm:p-4',
                'guest' => 'grid gap-2.5 md:grid-cols-3',
                'actions' => 'flex flex-col gap-2 border-t border-gray-200 pt-3 dark:border-dark-600 sm:flex-row sm:items-center sm:justify-between',
                'helper' => 'inline-flex items-center gap-1.5 text-[11px] leading-5 text-gray-500 dark:text-dark-300',
            ],
            'list' => 'mt-4 space-y-3',
            'comment' => [
                'article' => 'rounded-2xl border border-gray-200 bg-white p-3.5 shadow-xs transition hover:border-gray-300 dark:border-dark-600 dark:bg-dark-700 dark:hover:border-dark-500 sm:p-4',
                'reply' => 'bg-gray-50/70 dark:bg-dark-800/70',
                'header' => 'flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between',
                'author' => 'flex min-w-0 items-start gap-2.5',
                'avatar' => 'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-500 text-xs font-semibold text-primary-50',
                'reply_avatar' => 'h-8 w-8 rounded-lg text-[10px]',
                'identity' => 'min-w-0 space-y-0.5',
                'name_row' => 'flex flex-wrap items-center gap-2',
                'name' => 'truncate text-sm font-semibold text-gray-900 dark:text-dark-100',
                'meta' => 'flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-dark-300',
                'meta_pill' => 'inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-600 dark:bg-dark-800 dark:text-dark-200',
                'moderation' => 'shrink-0',
                'body' => 'mt-3 rounded-xl bg-gray-50 px-3 py-2.5 whitespace-pre-line text-sm leading-5 text-gray-700 dark:bg-dark-800/60 dark:text-dark-200',
                'actions' => 'mt-3 flex flex-wrap items-center gap-1.5',
                'counter' => 'inline-flex items-center gap-1 rounded-full bg-primary-50 px-2 py-0.5 text-[10px] font-semibold text-primary-700 dark:bg-primary-500/10 dark:text-primary-200',
                'editor' => 'mt-3 space-y-2.5 rounded-xl border border-gray-200 bg-gray-50/80 p-3 dark:border-dark-600 dark:bg-dark-800/60',
                'editor_title' => 'flex items-center gap-2 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-dark-200',
                'thread' => 'mt-2.5 space-y-2.5 border-l border-dashed border-gray-200 pl-3 dark:border-dark-600',
            ],
        ]);
    }

    public function key(): string
    {
        return 'tallstackui-comments-'.str_replace('\\', '-', $this->model::class).'-'.$this->model->getKey();
    }

    /** @throws InvalidArgumentException */
    protected function validate(): void
    {
        if (! in_array(HasComments::class, class_uses_recursive($this->model), true) && ! method_exists($this->model, 'comments')) {
            __ts_validation_exception($this, 'The provided model must use the [TallStackUi\\Comments\\Concerns\\HasComments] trait or define a [comments] relation.');
        }

        if (data_get($this->settings, 'mode.auth') === data_get($this->settings, 'mode.guest')) {
            __ts_validation_exception($this, 'The comments component requires exactly one active mode between [auth] and [guest].');
        }
    }
}
