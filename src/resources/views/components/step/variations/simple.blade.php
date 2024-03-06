<li @class($personalize['simple.li'])
    x-bind:class="{ 'cursor-pointer': navigate === true }"
    x-on:click="if (navigate === false || (previous === false && item.step < selected)) return; selected = item.step;">
    <div @class($personalize['simple.bar.wrapper'])
         x-bind:class="{
             '{{ $personalize['simple.bar.inactive'] }}': selected < item.step,
             '{{ $personalize['simple.bar.current'] }}': selected === item.step && item.completed === false,
             '{{ $personalize['simple.bar.active'] }}': selected > item.step || selected === item.step && item.completed === true,
         }">
        <span x-text="item.title"
              @class($personalize['simple.text.title.wrapper'])
              x-bind:class="{
                  '{{ $personalize['simple.text.title.inactive'] }}': selected < item.step,
                  '{{ $personalize['simple.text.title.current'] }}': selected === item.step && item.completed === false,
                  '{{ $personalize['simple.text.title.active'] }}': selected > item.step || selected === item.step &&
                      item.completed === true,
              }"></span>
        <span x-text="item.description" @class($personalize['simple.text.description'])></span>
    </div>
</li>
