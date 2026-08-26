@php use App\Enums\SourceName as Source; @endphp
@section('title', 'Главная')

<x-layouts::main>
    {{--    <div class="container mt-4">--}}
    {{--        {{ Breadcrumbs::render('home') }}--}}
    {{--    </div>--}}
    <div class="page-header">
        <div class="container">
            <div class="page-title">
                <div class="h1 text-uppercase">Почему {!! brand() !!}?</div>
            </div>
            <div class="text-secondary">
                <div>Мы собираем и анализируем данные о тысячах браузерных игр: рейтинги, оценки игроков, динамику популярности, обновления и тренды.</div>
                <div>Наша цель — помочь вам выбрать лучшую игру или отследить состояние любимого проекта.</div>
            </div>
        </div>
        <div class="container mt-4">
            <div class="page-title">
                <div class="h1 text-uppercase">Присоединяйтесь!</div>
            </div>
            <div class="text-secondary">
                <div>Зарегистрируйтесь, чтобы сохранять избранные игры, получать уведомления о изменениях рейтингов и делиться собственными оценками.</div>
                <div class="mt-3 text-uppercase">
                    <a href="/" class="btn btn-primary w-100 disabled">Создать аккаунт</a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container">
            <div class="row">
                <div class="col">
                    <x-ui.subheadline
                        label="Новые разработчики"
                        href="{{ route('developers.latest', Source::YANDEXGAMES) }}"
                    >
                        <div class="card rounded-0 shadow-none">
                            <div class="list-group list-group-flush">
                                @foreach($developers as $d)
                                    <a
                                        href="{{ route('developers.show', [$d->id, $d->slug]) }}"
                                        class="list-group-item list-group-item-action rounded-0"
                                    >
                                        {{ $d->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </x-ui.subheadline>
                </div>
                <div class="col">
                    <x-ui.subheadline
                        label="Новые игры"
                        href="{{ route('games.latest', Source::YANDEXGAMES) }}"
                    >
                        <div class="card rounded-0 shadow-none">
                            <div class="list-group list-group-flush">
                                @foreach($games as $g)
                                    <a
                                        href="{{ route('games.show', [$g->id, $g->slug]) }}"
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded-0"
                                    >
                                        {{ $g->title }}<br>
                                        <span class="text-azure" title="Разработчик">
                                            {{ $g->developer->name }}
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
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M7 8l-4 4l4 4"/><path d="M17 8l4 4l-4 4"/>
                                                <path d="M14 4l-4 16"/>
                                            </svg>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </x-ui.subheadline>
                </div>
            </div>
            <x-ui.subheadline
                label="Категории"
                href="/categories"
            >
                <div class="card rounded-0 shadow-none">
                    <div class="list-group list-group-flush">
                        @foreach($categories as $c)
                            <a
                                href="{{ sprintf('/categories/%d/%s', $c->id, $c->slug) }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded-0"
                            >
                                {{ $c->title }}<br>
                                <span
                                    class="text-azure"
                                    title="{{ $c->games_count }} игр(ы) в категории {{ $c->title }}"
                                >
                                    {{ $c->games_count }}
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
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-box"
                                    >
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"/>
                                        <path d="M12 12l8 -4.5"/>
                                        <path d="M12 12l0 9"/>
                                        <path d="M12 12l-8 -4.5"/>
                                    </svg>
                                    @if($period_games_count = $c->period_games_count)
                                        <b class="text-success">+{{ $period_games_count }}</b>
                                    @endif
                                </span>
                            </a>
                        @endforeach
                        <button
                            class="list-group-item btn btn-sm btn-primary rounded-0 disabled"
                        >
                            <b class="text-uppercase">Загрузить больше</b>
                        </button>
                    </div>
                </div>
            </x-ui.subheadline>
            <x-ui.subheadline
                label="Теги"
                href="/tags"
            >
                <div class="card rounded-0 shadow-none">
                    <div class="list-group list-group-flush">
                        @foreach($tags as $t)
                            <a
                                href="{{ sprintf('/tags/%d/%s', $t->id, $t->slug) }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded-0"
                            >
                                {{ $t->title }}<br>
                                <span class="text-azure" title="{{ $t->games_count }} игр(ы) тегом {{ $t->title }}">
                                    {{ $t->games_count }}
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
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-box"
                                    >
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"/>
                                        <path d="M12 12l8 -4.5"/>
                                        <path d="M12 12l0 9"/>
                                        <path d="M12 12l-8 -4.5"/>
                                    </svg>
                                    @if($period_games_count = $t->period_games_count)
                                        <b class="text-success">+{{ $period_games_count }}</b>
                                    @endif
                                </span>
                            </a>
                        @endforeach
                        <button
                            class="list-group-item btn btn-sm btn-primary rounded-0 disabled"
                        >
                            <b class="text-uppercase">Загрузить больше</b>
                        </button>
                    </div>
                </div>
            </x-ui.subheadline>
        </div>
    </div>
</x-layouts::main>
