@props(['developer', 'source', 'views_count'])

@section('title', sprintf('Разработчик — %s, %s', $developer->name, ($sourceName = $source->name->label())))

<x-layouts::main>
{{--    <div class="container mt-4">--}}
{{--        {{ Breadcrumbs::render('developers.show', $source, $developer) }}--}}
{{--    </div>--}}
    <div class="page-header">
        <div class="container text-uppercase">
            <div class="page-title">Разработчики</div>
            <div class="text-muted">{{ $sourceName }}</div>
        </div>
    </div>
    <article class="page-body">
        <div class="container">
            <div class="d-flex flex-md-row flex-column">
                <div
                    @class([
                        'card',
                        'me-md-3',
                        'mb-3',
                        'mb-md-0',
                        'm-0',
                        'justify-content-center',
                        $developer->rating_class,
                    ])
                    title="0 плюсов / 0 минусов"
                >
                    @auth
                        @if($developer->is_rating)
                            <div class="text-center mx-lg-0 my-3" style="min-width: 40px;">
                                0
                            </div>
                        @else
                            <x-ratings-store
                                :rateable="$developer"
                                @class([
                                    'card-body',
                                    'd-flex',
                                    'flex-row',
                                    'flex-md-column',
                                    'align-items-center',
                                    'w-100',
                                    'p-0',
                                    'justify-content-between' => auth()->check(),
                                    'justify-content-center' => !auth()->check(),
                                ])
                            />
                        @endif
                    @else
                        <div class="text-center mx-lg-0 my-3" style="min-width: 40px;">
                            0
                        </div>
                    @endauth
                </div>
                <div class="d-flex flex-column mb-md-0 mb-3">
                    <div
                        class="avatar"
                        style="--tblr-avatar-size: 10rem; background-image: url('{{ asset('static/media/avatar/not-found.png') }}'); background-size: cover;"
                    >
                        @if(! is_null($developer->removed_at))
                            <div class="ribbon ribbon-top bg-danger" title="Удалён {{ $developer->removed_at->ago() }}">
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
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-trash"
                                >
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M4 7l16 0"/>
                                    <path d="M10 11l0 6"/>
                                    <path d="M14 11l0 6"/>
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="badge badge-outline text-default mt-3 p-2">0 подписчиков</div>
                </div>
                <div class="ms-md-3 m-0 mb-md-0 mb-3">
                    <h1 class="m-0">{{ $developer->name }}</h1>
                    <div class="text-muted">Анонимный разработчик</div>
                </div>
{{--                @auth--}}
                    <div class="ms-md-auto ms-0">
                        @php($is_favorite = $developer->is_favorite)
                        <form action="{{--{{ route('favorites.toggle') }}--}}" method="post">
                            @csrf
                            <input type="hidden" name="favoriteable[type]" value="{{ $developer::class }}" autocomplete="off">
                            <input type="hidden" name="favoriteable[id]" value="{{ $developer->id }}" autocomplete="off">
                            <button
                                @class(['justify-content-start', 'btn', 'mb-2', 'w-100', $is_favorite ? 'btn-warning' : 'btn-outline-warning'])
                                type="submit"
                                data-loading-text="Выполнение..."
                                disabled
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-star">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"></path>
                                </svg>
                                {{ $is_favorite ? 'Убрать из избранного' : 'Добавить в избранное' }}
                            </button>
                        </form>
                        <div class="btn-list ms-auto">
                            <a href="#" class="btn btn-primary flex-grow-1 justify-content-start disabled">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-heart-plus">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M12 20l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.96 6.053"></path>
                                    <path d="M16 19h6"></path>
                                    <path d="M19 16v6"></path>
                                </svg>
                                Подписаться
                            </a>
                            <a href="#" class="dropdown btn btn-2 btn-icon" aria-label="Button" data-bs-toggle="dropdown" title="Ещё..." aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-2">
                                    <path d="M5 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                    <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                    <path d="M19 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                </svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow shadow-none my-2">
                                <a href="#" class="dropdown-item disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-alert-triangle">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 9v4"/>
                                        <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0"/>
                                        <path d="M12 16h.01"/>
                                    </svg>
                                    Пожаловаться
                                </a>
                            </div>
                        </div>
                    </div>
{{--                @endauth--}}
            </div>
            <div class="card mt-3">
                <p class="card-body m-0">Биографии пока нет</p>
            </div>
            <x-developers-nav :developer="$developer">
                <x-ui.subheadline label="Описание">
                    <x-ui.card>Нет описания</x-ui.card>
                </x-ui.subheadline>
                <div class="row row-cards">
                    <div class="col-md-4">
                        <x-ui.subheadline label="Страницу посетили" :level="3">
                            <x-ui.card>{{ $views_count }} раз(а)</x-ui.card>
                        </x-ui.subheadline>
                    </div>
                    <div class="col-md-4">
                        <x-ui.subheadline label="Добавили в избранное" :level="3">
                            <x-ui.card>0 пользователя</x-ui.card>
                        </x-ui.subheadline>
                    </div>
                    <div class="col-md-4">
                        <x-ui.subheadline label="Голосов за всё время" :level="3">
                            <x-ui.card>0 голоса</x-ui.card>
                        </x-ui.subheadline>
                    </div>
                </div>
            </x-developers-nav>
        </div>
    </article>
    @pushonce('head-link')
        <link rel="canonical" href="{{ route('developers.show', [$developer, $developer->slug]) }}">
    @endpushonce
</x-layouts::main>
