<li x-bind:id="item.id ? 'li-' + item.id : null"
    class="{{ $customization['circles.li'] }}"
    x-bind:class="{ 'cursor-pointer': navigate === true }"
    x-on:click="if (navigate === false || (previous === false && item.step < parseInt(selected))) return; selected = item.step;">
    <div class="{{ $customization['circles.wrapper'] }}">
        <span class="{{ $customization['circles.circle.wrapper'] }}"
              x-bind:class="{
                  '{{ $customization['circles.circle.inactive'] }}': parseInt(selected) < item.step,
                  '{{ $customization['circles.circle.current'] }}': parseInt(selected) === item.step && item.completed === false,
                  '{{ $customization['circles.circle.border'] }}': parseInt(selected) === item.step && item.completed === true,
                  '{{ $customization['circles.circle.active'] }}': parseInt(selected) > item.step || parseInt(selected) === item.step && item.completed === true,
              }">
            <x-dynamic-component :component="TallStackUi::prefix('icon')"
                                 :icon="TallStackUi::icon('check')"
                                 x-show="parseInt(selected) > item.step && item.completed === false || parseInt(selected) === item.step && item.completed === true"
                                 internal
                                 class="{{ $customization['circles.check'] }}" />
            <span x-show="parseInt(selected) === item.step && item.completed === false"
                  class="{{ $customization['circles.highlighter.wrapper'] }}"
                  x-bind:class="{
                      '{{ $customization['circles.highlighter.current'] }}': parseInt(selected) === item.step && item.completed ===
                          false,
                      '{{ $customization['circles.highlighter.active'] }}': item.completed === true,
                  }"></span>
            <span x-show="parseInt(selected) < item.step" x-text="item.step"></span>
        </span>
        <div class="{{ $customization['circles.divider.wrapper'] }}"
             x-show="item.step != steps.length"
             x-bind:class="{
                 '{{ $customization['circles.divider.inactive'] }}': parseInt(selected) <= item.step,
                 '{{ $customization['circles.divider.active'] }}': parseInt(selected) > item.step,
             }">
        </div>
    </div>
    <div class="{{ $customization['circles.text.wrapper'] }}">
        <span x-text="item.title" class="{{ $customization['circles.text.title'] }}"></span>
        <span x-text="item.description" class="{{ $customization['circles.text.description'] }}"></span>
    </div>
</li>
