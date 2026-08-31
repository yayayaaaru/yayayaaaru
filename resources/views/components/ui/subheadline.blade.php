@props(['label', 'href' => null, 'disabled' => false, 'level' => 2])

@php
    $level = in_array((int)$level, [2, 3, 4]) ? (int)$level : 2;
    $tag = match ($level) {
        2 => 'h2',
        3 => 'h3',
        4 => 'h4',
    };
@endphp

@if(!$disabled)
    <div
        {{ $attributes->merge(['class' => 'subheadline']) }}
    >
        @if($href)
            <{{ $tag }} @class(['h4', 'm-0'])>
                <a
                    href="{{ $href }}"
                    @class(['d-flex', 'fw-bold', 'text-uppercase', 'align-items-center'])
                >
                    <div class="flex-grow-1">
                        {!! $label !!}
                    </div>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                        class="icon icon-tabler icons-tabler-filled icon-tabler-caret-right"
                    >
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M9 6c0 -.852 .986 -1.297 1.623 -.783l.084 .076l6 6a1 1 0 0 1 .083 1.32l-.083 .094l-6 6l-.094 .083l-.077 .054l-.096 .054l-.036 .017l-.067 .027l-.108 .032l-.053 .01l-.06 .01l-.057 .004l-.059 .002l-.059 -.002l-.058 -.005l-.06 -.009l-.052 -.01l-.108 -.032l-.067 -.027l-.132 -.07l-.09 -.065l-.081 -.073l-.083 -.094l-.054 -.077l-.054 -.096l-.017 -.036l-.027 -.067l-.032 -.108l-.01 -.053l-.01 -.06l-.004 -.057l-.002 -12.059z"/>
                    </svg>
                </a>
            </{{ $tag }}>
        @else
            <div class="d-flex">
                <{{ $tag }} @class(['h4', 'm-0', 'flex-grow-1', 'fw-bold', 'text-uppercase'])>
                    {!! $label !!}
                </{{ $tag }}>
                {{ $options ?? '' }}
            </div>
        @endif
    </div>
    @if($slot->isNotEmpty())
        <div class="subheadline-body flex-grow-1">
            {{ $slot }}
        </div>
    @endif
@endif
