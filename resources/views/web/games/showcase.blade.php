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
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span @class(['avatar', $bg])>
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-box"
                                                width="24"
                                                height="24"
                                                viewBox="0 0 24 24"
                                                stroke-width="2"
                                                stroke="currentColor"
                                                fill="none"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"></path>
                                                <path d="M12 12l8 -4.5"></path>
                                                <path d="M12 12l0 9"></path><path d="M12 12l-8 -4.5"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            <b>{{ number_format($data['count'], 0, '', ' ') }}</b>
                                        </div>
                                        <div class="text-secondary text-uppercase">
                                            {{ $data['title'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts::main>
