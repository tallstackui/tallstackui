<li x-bind:id="item.id ? 'li-' + item.id : null"
    class="{{ $customization['panels.li'] }}"
    x-bind:class="{ 'cursor-pointer': navigate === true }"
    x-on:click="if (navigate === false || (previous === false && item.step < parseInt(selected))) return; selected = item.step;">
    <div class="{{ $customization['panels.wrapper'] }}">
        <span class="{{ $customization['panels.item'] }}">
            <span class="{{ $customization['panels.circle.wrapper'] }}"
                  x-bind:class="{
                      '{{ $customization['panels.circle.inactive'] }}': parseInt(selected) < item.step,
                      '{{ $customization['panels.circle.current'] }}': parseInt(selected) === item.step && item.completed === false,
                      '{{ $customization['panels.circle.active'] }}': parseInt(selected) > item.step || parseInt(selected) === item.step && item.completed === true,
                  }">
                <span x-text="item.step"
                      x-show="parseInt(selected) < item.step || parseInt(selected) === item.step && item.completed === false"
                      x-bind:class="{
                          '{{ $customization['panels.text.number.active'] }}': parseInt(selected) <= item.step,
                          '{{ $customization['panels.text.number.inactive'] }}': parseInt(selected) >= item.step,
                      }">
                </span>
                <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                     :icon="TallStackUi::icon('check')"
                                     x-show="parseInt(selected) > item.step || parseInt(selected) === item.step && item.completed === true"
                                     internal
                                     class="{{ $customization['panels.check'] }}" />
            </span>
            <div class="flex flex-col">
                <span class="{{ $customization['panels.text.title.wrapper'] }}"
                      x-bind:class="{
                        '{{ $customization['panels.text.title.inactive'] }}': parseInt(selected) === item.step && item.completed === false || parseInt(selected) < item.step,
                        '{{ $customization['panels.text.title.active'] }}': parseInt(selected) > item.step || parseInt(selected) === item.step && item.completed === true,
                      }" x-text="item.title"></span>
                <span class="{{ $customization['panels.text.description'] }}" x-text="item.description"></span>
            </div>
        </span>
    </div>
    <div x-show="item.step !== steps.length" class="{{ $customization['panels.divider.wrapper'] }}">
        <svg class="{{ $customization['panels.divider.svg'] }}" viewBox="0 0 22 80" fill="none"
             preserveAspectRatio="none">
            <path d="M0 -2L20 40L0 82" vector-effect="non-scaling-stroke" stroke="currentcolor"
                  stroke-linejoin="round" />
        </svg>
    </div>
</li>
