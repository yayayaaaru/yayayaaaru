@props(['developers'])

@section('title', )

<x-layouts::main>
{{--    <div class="container mt-4">--}}
{{--        {{ Breadcrumbs::render('games', $source) }}--}}
{{--    </div>--}}
    <div class="page-header">
        <div class="container text-uppercase">
            <div class="page-title">Разработчики</div>
            <div class="text-muted">Глобальный поиск</div>
        </div>
    </div>
    <div class="page-body">
        <div class="container">
            <x-cards-developer :$developers/>
        </div>
    </div>
</x-layouts::main>
