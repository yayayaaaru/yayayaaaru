@props(['games', 'source'])

@section('title', sprintf('%s — новые за сегодня, Игры', $source->name))

<x-layouts::main>
    {{--    <div class="container mt-4">--}}
    {{--        {{ Breadcrumbs::render('developers.showcase') }}--}}
    {{--    </div>--}}
    <div class="page-header">
        <div class="container text-uppercase">
            <div class="page-title">{{ $source->label() }}</div>
            <div class="text-muted">Свежие релизы сегодняшнего дня — играйте в новинки прямо сейчас.</div>
        </div>
    </div>
    <div class="page-body">
        <div class="container">
            <x-ui.subheadline label="Новые игры">
                <div class="card rounded-0 shadow-none">
                    <div class="list-group list-group-flush">
                        @foreach($games as $g)
                            <a
                                href="{{ route('games.show', [$g->id, $g->slug]) }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded-0"
                            >
                                {{ $g->title }}<br>
                                <span class="text-azure" title="Разработчик">
                                    {{ $g->developer->name }}
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-code"
                                    >
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M7 8l-4 4l4 4"/><path d="M17 8l4 4l-4 4"/>
                                        <path d="M14 4l-4 16"/>
                                    </svg>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </x-ui.subheadline>
        </div>
    </div>
</x-layouts::main>
