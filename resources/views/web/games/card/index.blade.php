@props(['game', 'developer', 'source'])

@section('title', 'Игра')

<x-layouts::main>
{{--    <div class="container mt-4">--}}
{{--        {{ Breadcrumbs::render('games.show', $source, $game) }}--}}
{{--    </div>--}}
    <div class="page-header">
        <div class="container text-uppercase">
            <div class="page-title">Игры</div>
            <div class="text-muted">{{ $source->name->label() }}</div>
        </div>
    </div>
    <div class="page-body">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-7">
                    <div id="carousel-controls" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner rounded-3">
                            <div class="carousel-item active">
                                <img class="d-block w-100" alt="" src="{{ asset('static/media/not-found.png') }}">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carousel-controls" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel-controls" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </a>
                    </div>
                </div>
                <div class="col-12 col-lg-5 d-flex flex-column mt-4 mt-lg-0">
                    <div class="mb-3">
                        <h1 class="m-0">{{ $game->title }}</h1>
                        <div>
                            Разработчик
                            <a
                                href="{{ route('developers.show', [$developer, $developer->slug]) }}"
                                class="link-secondary"
                            >
                                {{ $developer->name }}
                            </a>
                        </div>
                        <hr class="my-3">
                        <div>
                            Дата выхода
                            <span class="text-muted">
                                {{ $game->released_at?->format('d.m.Y') ?? 'нет информации' }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <div class="d-flex">
                            <a href="#" class="btn btn-primary p-3 w-100 disabled">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                    class="icon icon-tabler icons-tabler-filled icon-tabler-player-play m-0"
                                >
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M6 4v16a1 1 0 0 0 1.524 .852l13 -8a1 1 0 0 0 0 -1.704l-13 -8a1 1 0 0 0 -1.524 .852z"/>
                                </svg>
                            </a>
                            @auth
                                <a
                                    href="{{ route('games.votes', [$game, $game->slug]) }}"
                                    class="btn btn-danger ms-3" style="padding: 16px 18px;"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="currentColor"
                                        class="icon icon-tabler icons-tabler-filled icon-tabler-thumb-up m-0"
                                    >
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M13 3a3 3 0 0 1 2.995 2.824l.005 .176v4h2a3 3 0 0 1 2.98 2.65l.015 .174l.005 .176l-.02 .196l-1.006 5.032c-.381 1.626 -1.502 2.796 -2.81 2.78l-.164 -.008h-8a1 1 0 0 1 -.993 -.883l-.007 -.117l.001 -9.536a1 1 0 0 1 .5 -.865a2.998 2.998 0 0 0 1.492 -2.397l.007 -.202v-1a3 3 0 0 1 3 -3z"/>
                                        <path d="M5 10a1 1 0 0 1 .993 .883l.007 .117v9a1 1 0 0 1 -.883 .993l-.117 .007h-1a2 2 0 0 1 -1.995 -1.85l-.005 -.15v-7a2 2 0 0 1 1.85 -1.995l.15 -.005h1z"/>
                                    </svg>
                                </a>
                            @endauth
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            @auth
                                @php($is_favorite = $game->is_favorite)
                                <form action="{{ route('favorites.toggle') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="favoriteable[type]" value="{{ $game::class }}" autocomplete="off">
                                    <input type="hidden" name="favoriteable[id]" value="{{ $game->id }}" autocomplete="off">
                                    <button
                                        type="submit"
                                        @class(['btn', 'me-3', 'p-2', $is_favorite ? 'btn-warning' : 'btn-outline-warning'])
                                        title="Добавить в избранное"
                                        data-loading-text
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-star m-0">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"/>
                                        </svg>
                                    </button>
                                </form>
                            @endauth
                            <div
                                @class(['card', 'h-100', 'w-100', $game->rating_class])
                                title="0 плюсов/ 0 минусов"
                            >
                                @auth
                                    @if($game->is_rating)
                                        <div class="text-center mx-lg-0" style="margin: 10px 0;">
                                            {{ $game->rating_total }}
                                        </div>
                                    @else
                                        <x-ratings-store
                                            :rateable="$game"
                                            @class([
                                                'card-body',
                                                'd-flex',
                                                'align-items-center',
                                                'p-0',
                                                'justify-content-between' => auth()->check(),
                                                'justify-content-center' => !auth()->check(),
                                            ])
                                       />
                                    @endif
                                @else
                                    <div class="text-center mx-lg-0 my-3">
                                        0
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-games-nav :game="$game">
                <x-ui.subheadline label="Описание">
                    <x-ui.card>
                        {{ $game->description }}
                    </x-ui.card>
                </x-ui.subheadline>
                <div class="row row-cards">
                    <div class="col-md-4">
                        <x-ui.subheadline label="Страницу посетили">
                            <x-ui.card>0 пользователей</x-ui.card>
                        </x-ui.subheadline>
                    </div>
                    <div class="col-md-4">
                        <x-ui.subheadline label="Добавили в избранное">
                            <x-ui.card>0 пользователей</x-ui.card>
                        </x-ui.subheadline>
                    </div>
                    <div class="col-md-4">
                        <x-ui.subheadline label="Голосов за всё время">
                            <x-ui.card>0 голоса</x-ui.card>
                        </x-ui.subheadline>
                    </div>
                </div>
            </x-games-nav>

            @if($historyCisScore->isNotEmpty())
            <x-ui.subheadline label="Рейтинг СНГ">
                <x-ui.card style="white-space: normal;">
                    @foreach($historyCisScore as $history)
                        <div>{{ sprintf('Дата: %s, Значение: %d пунктов', $history['date'], $history['value']) }}</div>
                    @endforeach
                </x-ui.card>
            </x-ui.subheadline>
            @endif

            @if($historyMinLoadTime->isNotEmpty())
            <x-ui.subheadline label="Время загрузки">
                <x-ui.card style="white-space: normal;">
                    @foreach($historyMinLoadTime as $history)
                        <div>{{ sprintf('Дата: %s, Значение: %f секунд', $history['date'], $history['value']) }}</div>
                    @endforeach
                </x-ui.card>
            </x-ui.subheadline>
            @endif

            <div class="row">
                @if($historyReviews['count']->isNotEmpty())
                <div class="col">
                    <x-ui.subheadline label="Количество оценок">
                        <x-ui.card style="white-space: normal;">
                            @foreach($historyReviews['count'] as $history)
                                <div>{{ sprintf('Дата: %s, Значение: %d оценок', $history['date'], $history['value']) }}</div>
                            @endforeach
                        </x-ui.card>
                    </x-ui.subheadline>
                </div>
                @endif

                @if($historyReviews['scores_avg']->isNotEmpty())
                <div class="col">
                    <x-ui.subheadline label="Среднее">
                        <x-ui.card style="white-space: normal;">
                            @foreach($historyReviews['scores_avg'] as $history)
                                <div>{{ sprintf('Дата: %s, Значение: %f оценка', $history['date'], $history['value']) }}</div>
                            @endforeach
                        </x-ui.card>
                    </x-ui.subheadline>
                </div>
                @endif

                @if($historyReviews['scores_stat']->isNotEmpty())
                <div class="col-12">
                    <x-ui.subheadline label="Звезды">
                        <x-ui.card style="white-space: normal;">
                            @foreach($historyReviews['scores_stat'] as $history)
                                <div>
                                    Дата: {{ $history['date'] }},
                                    Значение:
                                    @foreach($history['value'] as $star => $value)
                                        <span class="text-yellow badge bg-yellow-lt" style="font-size: 14px;">
                                            <b>{{ $star }}</b>
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="24"
                                                height="24"
                                                viewBox="0 0 24 24"
                                                fill="currentColor"
                                                class="icon icon-tabler icons-tabler-filled icon-tabler-star"
                                            >
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"/>
                                            </svg>
                                            {{ $value }}
                                        </span>
                                    @endforeach
                                </div>
                            @endforeach
                        </x-ui.card>
                    </x-ui.subheadline>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts::main>
