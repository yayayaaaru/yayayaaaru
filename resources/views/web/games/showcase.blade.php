@php use App\Enums\SourceName as Source; @endphp

@props(['statsBySource', 'stats'])

<x-layouts::main>
    {{--    <div class="container mt-4">--}}
    {{--        {{ Breadcrumbs::render('games.showcase') }}--}}
    {{--    </div>--}}
    <div class="page-header">
        <div class="container text-uppercase">
            <div class="page-title">Игры</div>
            <div class="text-muted">Витрина</div>
        </div>
    </div>
    <div class="page-body">
        <div class="container">
            <div class="row row-cards mb-4">
                @foreach($statsBySource as $sourceName => $count)
                    @php($source = Source::from($sourceName))
                    <div class="col-12 col-lg-4">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto d-none d-sm-block">
                                        <span class="bg-white text-white avatar avatar-2xl p-3">
                                            <img src="{{ $source->logo() }}" alt="{{ $source->label() }}">
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="h1 mb-3">
                                            <div style="margin-bottom: -10px;">
                                                {{ number_format($count['total'], 0, '', ' ') }}
                                                @if($count['today'])
                                                <small class="text-success">+{{ $count['today'] }}</small>
                                                @endif
                                                игр
                                            </div>
                                            <small class="text-muted">в каталоге</small>
                                        </div>
                                        <div class="font-weight-medium">{{ $source->label() }}</div>
                                        <div class="text-secondary">
                                            <a href="{{ route('games', $source) }}">Перейти</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <x-ui.subheadline label="Статистика"/>

            <div class="row">
                @foreach($stats as $bg => $data)
                    <div class="col">
                        <a href="{{ $data['search'] }}" class="card card card-link card-link-pop card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span @class(['avatar', $bg])>
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
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-device-gamepad-2"
                                            >
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M12 5h3.5a5 5 0 0 1 0 10h-5.5l-4.015 4.227a2.3 2.3 0 0 1 -3.923 -2.035l1.634 -8.173a5 5 0 0 1 4.904 -4.019h3.4"></path>
                                                <path d="M14 15l4.07 4.284a2.3 2.3 0 0 0 3.925 -2.023l-1.6 -8.232"></path>
                                                <path d="M8 9v2"></path>
                                                <path d="M7 10h2"></path>
                                                <path d="M14 10h2"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            <b>{{ number_format($data['count'], 0, '', ' ') }}</b>
                                            @if($data['today'])
                                                <span class="text-success">+{{ $data['today'] }}</span>
                                            @endif
                                        </div>
                                        <div class="text-secondary text-uppercase">
                                            {{ $data['title'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts::main>
