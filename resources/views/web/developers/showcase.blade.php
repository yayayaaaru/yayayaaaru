@php use App\Enums\SourceName as Source; @endphp

@props(['stats'])

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
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="row row-cards">
                        @foreach($stats as $sourceName => $count)
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
        </div>
    </div>
</x-layouts::main>
