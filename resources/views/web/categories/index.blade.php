@props(['categories'])

@section('title', 'Все категории')

<x-layouts::main>
{{--    <div class="container mt-4">--}}
{{--        {{ Breadcrumbs::render('developers', $source) }}--}}
{{--    </div>--}}
    <div class="page-header">
        <div class="container">
            <div class="page-title">Все категории</div>
            <div class="text-secondary">Все категории в одном месте: от свежих релизов до проверенной классики.</div>
        </div>
    </div>
    <div class="page-body">
        <div class="container">
            <x-ui.subheadline label="Категории">
                <div class="card rounded-0 shadow-none">
                    <div class="list-group list-group-flush">
                        @foreach($categories as $c)
                            <a
                                href="{{ sprintf('/categories/%d/%s', $c->id, $c->slug) }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded-0"
                            >
                                {{ $c->title }}<br>
                                <span class="text-azure" title="{{ $c->games_count }} игр(ы) в категории {{ $c->title }}">
                                    {{ $c->games_count }}
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
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-box"
                                    >
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"/>
                                        <path d="M12 12l8 -4.5"/>
                                        <path d="M12 12l0 9"/>
                                        <path d="M12 12l-8 -4.5"/>
                                    </svg>
                                    @if($period_games_count = $c->period_games_count)
                                        <b class="text-success">+{{ $period_games_count }}</b>
                                    @endif
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </x-ui.subheadline>
        </div>
    </div>
</x-layouts::main>
