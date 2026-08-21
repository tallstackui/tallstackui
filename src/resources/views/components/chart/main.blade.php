@php
    $customization = $classes();
@endphp

<div {{ $attributes->class([$customization['wrapper']]) }}
     @if ($interactive)
         x-data="tallstackui_chart(@js($interaction))"
         x-on:click.outside="clear()"
     @endif>
    @if ($header)
        <div class="{{ $customization['slots.header'] }}">{{ $header }}</div>
    @endif

    <div class="{{ $customization['plot.wrapper'] }}" style="min-height: {{ $configurations['height'] }}px">
        <div class="{{ $customization['axis.y.wrapper'] }}">@if ($ticks)
                <span class="{{ $customization['axis.y.label'] }} invisible relative translate-y-0">{{ $widest }}</span>
                @foreach ($ticks as $tick)
                    <span class="{{ $customization['axis.y.label'] }}" style="top: {{ $tick['y'] }}%">{{ $tick['label'] }}</span>
                @endforeach
            @endif</div>

        <div class="relative min-w-0">
            <svg class="{{ $customization['plot.svg'] }}"
                 viewBox="{{ $viewbox }}"
                 preserveAspectRatio="{{ $aspect }}"
                 xmlns="http://www.w3.org/2000/svg"
                 aria-hidden="true"
                 dusk="tallstackui_chart"
                 @if ($interactive)
                     x-ref="plot"
                     @if (! $radial)
                         x-on:pointermove="track($event)"
                         x-on:pointerdown="track($event)"
                         x-on:pointerleave="release($event)"
                     @endif
                 @endif>
                @foreach ($ticks as $tick)
                    <line class="{{ $customization['plot.grid'] }}"
                          x1="0"
                          x2="{{ $bounds['width'] }}"
                          y1="{{ $tick['y'] }}"
                          y2="{{ $tick['y'] }}"
                          vector-effect="non-scaling-stroke" />
                @endforeach

                @if ($interactive && ! $radial)
                    <line class="{{ $customization['plot.crosshair'] }}"
                          x-show="active !== null"
                          x-bind:x1="crosshair"
                          x-bind:x2="crosshair"
                          y1="0"
                          y2="{{ $bounds['height'] }}"
                          vector-effect="non-scaling-stroke"
                          x-cloak />
                @endif

                @foreach ($plots as $index => $plot)
                    <g class="{{ $plot['color'] }}"
                       @if ($interactive)
                           x-show="visible({{ $index }})"
                           x-bind:transform="transform({{ $index }})"
                       @endif>
                        @if ($plot['area'])
                            <defs>
                                <linearGradient id="{{ $plot['gradient'] }}"
                                                x1="0"
                                                y1="0"
                                                x2="0"
                                                y2="1">
                                    <stop offset="0%"
                                          stop-color="currentColor"
                                          stop-opacity="{{ $customization['opacity.from'] }}" />
                                    <stop offset="100%"
                                          stop-color="currentColor"
                                          stop-opacity="{{ $customization['opacity.to'] }}" />
                                </linearGradient>
                            </defs>
                            <path class="{{ $customization['plot.area'] }}"
                                  fill="url(#{{ $plot['gradient'] }})"
                                  d="{{ $plot['area'] }}" />
                        @endif

                        @foreach ($plot['bars'] as $bar)
                            <path class="{{ $customization['plot.bar'] }}" d="{{ $bar['path'] }}" />
                        @endforeach

                        @if ($plot['line'])
                            <path class="{{ $customization['plot.line'] }}"
                                  vector-effect="non-scaling-stroke"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="{{ $plot['line'] }}" />
                        @endif

                    </g>
                @endforeach

                @foreach ($slices as $position => $slice)
                    <path class="{{ $customization['plot.slice'] }} {{ $slice['color'] }}"
                          fill="currentColor"
                          d="{{ $slice['path'] }}"
                          @if ($interactive)
                              x-show="visible({{ $position }})"
                              x-bind:d="arc({{ $position }})"
                              x-on:pointermove="pick({{ $position }}, $event)"
                              x-on:pointerdown="pick({{ $position }}, $event)"
                              x-on:pointerleave="release($event)"
                          @endif />
                @endforeach
            </svg>

            @if ($chrome['markers'] && ! $radial)
                <div class="{{ $customization['plot.markers'] }}">
                    @foreach ($plots as $index => $plot)
                        @continue (! $plot['points'])

                        <div class="{{ $plot['color'] }}" @if ($interactive) x-show="visible({{ $index }})" x-cloak @endif>
                            @foreach ($plot['points'] as $point)
                                <span class="{{ $customization['plot.marker'] }}"
                                      style="left: {{ $point[0] }}%; top: {{ $point[1] }}%"
                                      @if ($interactive) x-bind:style="marker({{ $index }}, {{ $point[0] }}, {{ $point[1] }})" @endif></span>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($interactive && $chrome['tooltip'])
                <div class="{{ $customization['tooltip.wrapper'] }}"
                     x-bind:class="{ 'invisible': active === null }"
                     x-bind:style="position"
                     x-ref="tip"
                     dusk="tallstackui_chart_tooltip"
                     x-cloak>
                    <p class="{{ $customization['tooltip.title'] }}" x-text="title"></p>
                    <template x-for="row in rows" :key="row.name">
                        <div class="{{ $customization['tooltip.row'] }}" x-bind:class="row.color">
                            <span class="{{ $customization['tooltip.dot'] }}"></span>
                            <span class="{{ $customization['tooltip.name'] }}" x-text="row.name"></span>
                            <span class="{{ $customization['tooltip.value'] }}" x-text="row.value"></span>
                        </div>
                    </template>
                </div>
            @endif
        </div>

        <div class="{{ $customization['axis.y.right.wrapper'] }}">@if ($secondary)
                <span class="{{ $customization['axis.y.right.label'] }} invisible relative translate-y-0">{{ $widestSecondary }}</span>
                @foreach ($secondary as $tick)
                    <span class="{{ $customization['axis.y.right.label'] }}" style="top: {{ $tick['y'] }}%">{{ $tick['label'] }}</span>
                @endforeach
            @endif</div>

        <div></div>

        <div class="{{ $customization['axis.x.wrapper'] }}"
             @if ($captions)
                 x-data="tallstackui_chartAxis({ fit: '{{ $fitting }}' })"
                 x-bind:style="wrapper"
             @endif>@foreach ($captions as $caption)
                <span class="{{ $customization['axis.x.label'] }}"
                      style="left: {{ $caption['x'] }}%"
                      x-bind:style="style({{ $loop->index }})"
                      x-bind:class="shown({{ $loop->index }}) ? '' : '{{ $customization['axis.x.off'] }}'"
                      dusk="tallstackui_chart_caption_{{ $loop->index }}">{{ $caption['label'] }}</span>
            @endforeach</div>

        <div></div>
    </div>

    @if ($chrome['legend'] && $entries)
        <div class="{{ $customization['legend.wrapper'] }}">
            @foreach ($entries as $index => $entry)
                <button type="button"
                        class="{{ $customization['legend.item'] }} {{ $entry['color'] }}"
                        @if ($interactive)
                            x-on:click="toggle({{ $index }})"
                            x-bind:class="visible({{ $index }}) ? '' : '{{ $customization['legend.off'] }}'"
                        @endif
                        dusk="tallstackui_chart_legend_{{ $index }}">
                    <span class="{{ $customization['legend.dot'] }}"></span>
                    <span class="{{ $customization['legend.text'] }}">{{ $entry['name'] }}</span>
                </button>
            @endforeach
        </div>
    @endif

    @if ($footer)
        <div class="{{ $customization['slots.footer'] }}">{{ $footer }}</div>
    @endif
</div>
