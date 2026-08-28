@props(['games', 'source'])

@section('title', sprintf('Игры — %s', $source->label()))

<x-layouts::main>
{{--    <div class="container mt-4">--}}
{{--        {{ Breadcrumbs::render('games', $source) }}--}}
{{--    </div>--}}
    <div class="page-header">
        <div class="container text-uppercase">
            <div class="page-title">Игры</div>
            <div class="text-muted">{{ $source->label() }}</div>
        </div>
    </div>
    <div class="page-body">
        <div class="container">
            <x-cards-game :$games/>
        </div>
    </div>
</x-layouts::main>
