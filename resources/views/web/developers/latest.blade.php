@props(['developers', 'source'])

@section('title', sprintf('%s — новые за сегодня, Разработчики', $source->name))

<x-layouts::main>
    {{--    <div class="container mt-4">--}}
    {{--        {{ Breadcrumbs::render('developers.showcase') }}--}}
    {{--    </div>--}}
    <div class="page-header">
        <div class="container">
            <div class="page-title">{{ $source->name }}</div>
            <div class="text-secondary">Новое за сегодня</div>
        </div>
    </div>
    <div class="page-body">
        <div class="container">
            <x-ui.subheadline label="Новые разработчики">
                <div class="card rounded-0 shadow-none">
                    <div class="list-group list-group-flush">
                        @foreach($developers as $d)
                            <a
                                href="{{ sprintf('/developers/%d/%s', $d->id, $d->slug) }}"
                                class="list-group-item list-group-item-action rounded-0"
                            >
                                {{ $d->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </x-ui.subheadline>
        </div>
    </div>
</x-layouts::main>
