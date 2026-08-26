@props(['tag', 'games'])

@section('title', sprintf('Тег — %s', $tag->title))

<x-layouts::main>
{{--    <div class="container mt-4">--}}
{{--        {{ Breadcrumbs::render('developers', $source) }}--}}
{{--    </div>--}}
    <div class="page-header">
        <div class="container text-uppercase">
            <div class="page-title">Тег</div>
            <div class="text-muted">{{ $tag->title }}</div>
        </div>
    </div>
    <div class="page-body">
        <div class="container">
            <x-ui.subheadline label="Игры">
                <x-cards-game :$games />
            </x-ui.subheadline>
        </div>
    </div>
</x-layouts::main>
