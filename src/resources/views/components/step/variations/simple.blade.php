<li x-bind:id="item.id ? 'li-' + item.id : null"
    class="{{ $customization['simple.li'] }}"
    x-bind:class="{ 'cursor-pointer': navigate === true }"
    x-on:click="if (navigate === false || (previous === false && item.step < parseInt(selected))) return; selected = item.step;">
    <div class="{{ $customization['simple.bar.wrapper'] }}"
         x-bind:class="{
             '{{ $customization['simple.bar.inactive'] }}': parseInt(selected) < item.step,
             '{{ $customization['simple.bar.current'] }}': parseInt(selected) === item.step && item.completed === false,
             '{{ $customization['simple.bar.active'] }}': parseInt(selected) > item.step || parseInt(selected) === item.step && item.completed === true,
         }">
        <span x-text="item.title"
              class="{{ $customization['simple.text.title.wrapper'] }}"
              x-bind:class="{
                  '{{ $customization['simple.text.title.inactive'] }}': parseInt(selected) < item.step,
                  '{{ $customization['simple.text.title.current'] }}': parseInt(selected) === item.step && item.completed === false,
                  '{{ $customization['simple.text.title.active'] }}': parseInt(selected) > item.step || parseInt(selected) === item.step && item.completed === true,
              }"></span>
        <span x-text="item.description" class="{{ $customization['simple.text.description'] }}"></span>
    </div>
</li>
