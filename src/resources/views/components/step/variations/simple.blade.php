<li @class($personalize['simple.li'])
    x-bind:class="{ 'cursor-pointer': navigate === true }"
    x-on:click="if (navigate === false || (previous === false && item.step < parseInt(selected))) return; selected = item.step;">
    <div @class($personalize['simple.bar.wrapper'])
         x-bind:class="{
             '{{ $personalize['simple.bar.inactive'] }}': parseInt(selected) < item.step,
             '{{ $personalize['simple.bar.current'] }}': parseInt(selected) === item.step && item.completed === false,
             '{{ $personalize['simple.bar.active'] }}': parseInt(selected) > item.step || parseInt(selected) === item.step && item.completed === true,
         }">
        <span x-text="item.title"
              @class($personalize['simple.text.title.wrapper'])
              x-bind:class="{
                  '{{ $personalize['simple.text.title.inactive'] }}': parseInt(selected) < item.step,
                  '{{ $personalize['simple.text.title.current'] }}': parseInt(selected) === item.step && item.completed === false,
                  '{{ $personalize['simple.text.title.active'] }}': parseInt(selected) > item.step || parseInt(selected) === item.step && item.completed === true,
              }"></span>
        <span x-text="item.description" @class($personalize['simple.text.description'])></span>
    </div>
</li>
