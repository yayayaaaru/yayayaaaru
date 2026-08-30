@php use App\Enums\SourceName as Source; @endphp

@props(['stats'])

@section('title', 'Игры — Витрина')

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
            <div class="row row-cards">
                @foreach($stats as $sourceName => $count)
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
        </div>
    </div>
</x-layouts::main>
