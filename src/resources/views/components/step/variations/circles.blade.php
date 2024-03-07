<li @class($personalize['circles.li'])
    x-bind:class="{ 'cursor-pointer': navigate === true }"
    x-on:click="if (navigate === false || (previous === false && item.step < parseInt(selected))) return; selected = item.step;">
    <div @class($personalize['circles.wrapper'])>
        <span @class($personalize['circles.circle.wrapper'])
              x-bind:class="{
                  '{{ $personalize['circles.circle.inactive'] }}': parseInt(selected) < item.step,
                  '{{ $personalize['circles.circle.current'] }}': parseInt(selected) === item.step && item.completed === false,
                  '{{ $personalize['circles.circle.border'] }}': parseInt(selected) === item.step && item.completed === true,
                  '{{ $personalize['circles.circle.active'] }}': parseInt(selected) > item.step || parseInt(selected) === item.step && item.completed === true,
              }">
            <x-dynamic-component :component="TallStackUi::component('icon')"
                                 :icon="TallStackUi::icon('check')"
                                 x-show="parseInt(selected) > item.step && item.completed === false || parseInt(selected) === item.step && item.completed === true"
                                 @class($personalize['circles.check']) />
            <span x-show="parseInt(selected) === item.step && item.completed === false"
                  @class($personalize['circles.highlighter.wrapper'])
                  x-bind:class="{
                      '{{ $personalize['circles.highlighter.current'] }}': parseInt(selected) === item.step && item.completed ===
                          false,
                      '{{ $personalize['circles.highlighter.active'] }}': item.completed === true,
                  }"></span>
            <span x-show="parseInt(selected) < item.step" x-text="item.step"></span>
        </span>
        <div @class($personalize['circles.divider.wrapper'])
             x-show="item.step != steps.length"
             x-bind:class="{
                 '{{ $personalize['circles.divider.inactive'] }}': parseInt(selected) <= item.step,
                 '{{ $personalize['circles.divider.active'] }}': parseInt(selected) > item.step,
             }">
        </div>
    </div>
    <div @class($personalize['circles.text.wrapper'])>
        <span x-text="item.title" @class($personalize['circles.text.title'])></span>
        <span x-text="item.description" @class($personalize['circles.text.description'])></span>
    </div>
</li>
