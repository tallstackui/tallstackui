@php
    $personalize = $classes();
    $slot ??= $slot->toHtml();
    $extension = $animated === true ? 'gif' : 'png';
@endphp

<div wire:ignore>
  <button x-data="app(@js($content()), @js($position))"
          x-on:click="show = !show"
          x-on:mouseover="show = true"
          x-ref="button" @class($personalize['wrapper.first'])>
    <div @class($personalize['wrapper.second'])>
      @if($slot->isNotEmpty())
        {{ $slot }}
      @else
        @foreach ($icons as $icon => $key)
          @if($loop->iteration <= 3)
            <img @class($personalize['image']) src="https://fonts.gstatic.com/s/e/notoemoji/latest/{{ $key }}/512.{{ $extension }}">
          @endif
        @endforeach
      @endif
    </div>
    @if ($quantity)
      <p @class($personalize['quantity'])>{{ $quantity }}</p>
    @endif
  </button>
</div>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script>
  function app(content, position) {
    return {
      show: false,
      init() {
        const that = this;
        tippy(this.$refs.button, {
          content: content,
          trigger: 'click',
          allowHTML: true,
          interactive: true,
          hideOnClick: false,
          placement: position,
          onClickOutside(instance) {
            that.show = false;
            instance.hide();
          },
        });

        this.$watch('show', (value) => {
          if (value) {
            this.showTooltip();
          } else {
            this.hideTooltip();
          }
        });
      },
      showTooltip() {
        this.$refs.button._tippy.show();
      },
      hideTooltip() {
        this.$refs.button._tippy.hide();
      },
    }
  }
</script>
