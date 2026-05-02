<div class="{{ data_get($classes, 'container') }}">
    <x-card>
        <x-slot:header>
            <div class="{{ data_get($classes, 'header.wrapper') }}">
                <div class="{{ data_get($classes, 'header.summary') }}">
                    <div class="{{ data_get($classes, 'header.icon') }}">
                        <x-icon icon="chat-bubble-left-right" class="h-4 w-4" />
                    </div>

                    <div class="{{ data_get($classes, 'header.content') }}">
                        <div class="{{ data_get($classes, 'header.title_row') }}">
                            <p class="{{ data_get($classes, 'header.title') }}">{{ trans('ts-ui::messages.comments.title') }}</p>
                        </div>

                        <p class="{{ data_get($classes, 'header.description') }}">
                            {{ trans_choice('ts-ui::messages.comments.counter', $this->total, ['count' => $this->total]) }}
                        </p>
                    </div>
                </div>

                <div class="{{ data_get($classes, 'header.toolbar') }}">
                    <div class="{{ data_get($classes, 'header.toolbar_wrapper') }}">
                        <span class="{{ data_get($classes, 'header.toolbar_label') }}">
                            {{ trans('ts-ui::messages.comments.sort.label') }}
                        </span>

                        <div class="{{ data_get($classes, 'header.toolbar_select') }}">
                        <x-select.styled
                            wire:model.live="sort"
                            :label="null"
                            :options="[
                                ['value' => 'latest', 'label' => trans('ts-ui::messages.comments.sort.latest')],
                                ['value' => 'oldest', 'label' => trans('ts-ui::messages.comments.sort.oldest')],
                                ['value' => 'popular', 'label' => trans('ts-ui::messages.comments.sort.popular')],
                            ]"
                            :selectable="['label' => 'label', 'value' => 'value']"
                            invalidate required />
                        </div>
                    </div>
                </div>
            </div>
        </x-slot:header>

        @if ($this->authMode() && !auth()->check())
            <div class="{{ data_get($classes, 'state.locked.wrapper') }}">
                <div class="{{ data_get($classes, 'state.locked.icon') }}">
                    <x-icon icon="lock-closed" class="h-5 w-5" />
                </div>

                <p class="{{ data_get($classes, 'state.locked.text') }}">
                    {{ trans('ts-ui::messages.comments.login_required') }}
                </p>
            </div>
        @else
            <div class="{{ data_get($classes, 'form.wrapper') }}">
                @if ($this->guestMode())
                    <div class="{{ data_get($classes, 'form.guest') }}">
                        @if (data_get($settings, 'guest_fields.name', true))
                            <x-input wire:model.live="guest.name"
                                     :label="trans('ts-ui::messages.comments.fields.name')" />
                        @endif
                        @if (data_get($settings, 'guest_fields.email', true))
                            <x-input wire:model.live="guest.email"
                                     :label="trans('ts-ui::messages.comments.fields.email')"
                                     type="email" />
                        @endif
                        @if (data_get($settings, 'guest_fields.website', false))
                            <x-input wire:model.live="guest.website"
                                     :label="trans('ts-ui::messages.comments.fields.website')"
                                     type="url" />
                        @endif
                    </div>
                @endif

                <x-textarea  wire:model="body"
                            :label="trans('ts-ui::messages.comments.fields.comment')"
                            :hint="trans('ts-ui::messages.comments.hint')"
                            :rows="3"
                            invalidate />

                <div class="{{ data_get($classes, 'form.actions') }}">
                    <span class="{{ data_get($classes, 'form.helper') }}">
                        <x-icon icon="shield-check" class="h-4 w-4" />
                        {{ trans('ts-ui::messages.comments.policy') }}
                    </span>

                    <x-button wire:click="comment"
                              loading="comment"
                              icon="chat-bubble-left-ellipsis"
                              sm
                              round
                              :text="trans('ts-ui::messages.comments.actions.comment')" />
                </div>
            </div>
        @endif

        @if ($this->comments->isEmpty())
            <div class="{{ data_get($classes, 'state.empty.wrapper') }}">
                <div class="{{ data_get($classes, 'state.empty.icon') }}">
                    <x-icon icon="chat-bubble-bottom-center-text" class="h-6 w-6" />
                </div>

                <p class="{{ data_get($classes, 'state.empty.text') }}">
                    {{ trans('ts-ui::messages.comments.empty') }}
                </p>
            </div>
        @else
            <div class="{{ data_get($classes, 'list') }}">
                @foreach ($this->comments as $comment)
                    @include('ts-ui::livewire.comments.comment', ['comment' => $comment, 'depth' => 0])
                @endforeach
            </div>

            <div>
                {{ $this->comments->links('ts-ui::components.table.paginators', ['scrollTo' => false, 'simplePagination' => false]) }}
            </div>
        @endif
    </x-card>
</div>
