@php use App\Enums\SourceName as Source; @endphp

@props(['statsBySource', 'stats'])

<x-layouts::main>
{{--    <div class="container mt-4">--}}
{{--        {{ Breadcrumbs::render('developers.showcase') }}--}}
{{--    </div>--}}
    <div class="page-header">
        <div class="container text-uppercase">
            <div class="page-title">Разработчики</div>
            <div class="text-muted">Витрина</div>
        </div>
    </div>
    <div class="page-body">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 col-lg-8">
                    <div class="row row-cards">
                        @foreach($statsBySource as $sourceName => $count)
                            @php($source = Source::from($sourceName))
                            <div class="col-12">
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
                                                        разработчиков
                                                    </div>
                                                    <small class="text-muted">в каталоге</small>
                                                </div>
                                                <div class="font-weight-medium">{{ $source->label() }}</div>
                                                <div class="text-secondary">
                                                    <a href="{{ route('developers', $source) }}">Перейти</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card card-md h-100 mt-4 m-lg-0 bg-secondary-lt">
                        <div class="card-body text-center">
                            <b class="text-uppercase">Место пустует ;(</b>
                        </div>
                    </div>
                </div>
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
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M7 8l-4 4l4 4"></path>
                                                <path d="M17 8l4 4l-4 4"></path>
                                                <path d="M14 4l-4 16"></path>
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
