<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\SourceName as Source;
use App\Models\Developer;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DeveloperController extends Controller
{
    public function showcase()
    {
        $ttl = now()->addHours(3);

        /** @var array $statsBySource */
        $statsBySource = Cache::remember(cache_key('developers_showcase'), $ttl, function () {

            $stats = [];

            foreach (Source::cases() as $source) {

                $q = Developer::whereHasSourceNamed($source);

                $stats[$source->value]['total'] = $q->count();
                $stats[$source->value]['today'] = $q->whereDate('created_at', today())->count();
            }

            return $stats;
        });

        $stats = Cache::remember(cache_key('developers_showcase_all'), $ttl, function () {

            $muted = ['title' => 'Всего разработчиков', 'count' => Developer::count()];
            $red = ['title' => 'Удалено', 'count' => Developer::whereNotNull('removed_at')->count()];

            return [
                'bg-muted-lt' => $muted,
                'bg-red-lt' => $red,
            ];
        });

        SEOMeta::setTitle('Разработчики — Витрина', false)->setDescription('Витрина');

        return view('web.developers.showcase', compact('statsBySource', 'stats'));
    }

    public function latest(Source $source)
    {
        $q = Developer::query();

        $developers = $q
            ->whereHasSourceNamed($source)
            ->whereDate('created_at', today())
            ->latest('id')
            ->get();

        SEOMeta::setTitle(sprintf('%s — новые за сегодня, Разработчики', $source->name), false)->setDescription('Свежие студии, которые только что добавили свои игры на платформу.');

        return view('web.developers.latest', compact('source', 'developers'));
    }

    public function games(Developer $developer)
    {
        $source = $developer->sources->first(static fn($s) => $s->name === Source::YANDEXGAMES);

        $games = $developer->games()->orderByDesc('id')->paginate(30);

        // --- Базовые мета-теги ---
        SEOMeta::setTitle(sprintf('Игры разработчика %s — %s', $developer->name, $source->name->label()), false)->setDescription('Игры.');

        return view('web.developers.card.games', compact('source', 'developer', 'games'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Source $source)
    {
        $q = Developer::query();

        $developers = $q
            ->whereHasSourceNamed($source)
            ->orderByDesc('created_at')
            ->paginate(30);

        // --- Базовые мета-теги ---
        SEOMeta::setTitle(sprintf('Разработчики — %s', $source->label()), false)->setDescription($source->label());

        return view('web.developers.index', compact('developers', 'source'));
    }

    /**
     * Display the specified resource.
     * @todo какашка - переделать
     */
    public function show(Developer $developer)
    {
        views($developer)->cooldown((int)config('viewable.cooldown_minutes'))->record();
        $views_count = views($developer)->count(); // @todo

        $source = $developer->sources->first(static fn($s) => $s->name === Source::YANDEXGAMES);

        $title = sprintf('Разработчик — %s, %s', $developer->name, ($sourceName = $source->name->label()));
        $description = sprintf('Профиль разработчика %s от %s', $developer->name, $sourceName);
        $canonical = route('developers.show', [$developer, $developer->slug]);
        $image = asset('static/media/avatar/not-found.png');

        // --- Базовые мета-теги ---
        SEOMeta::setTitle($title)->setDescription($description)->setCanonical($canonical);

        // --- Open Graph (для соцсетей, но также влияет на карточки в поиске) ---
        OpenGraph::setTitle($title)
            ->setDescription($description)
            ->setUrl($canonical)
            ->setType('profile')
            ->addImage($image)
            ->addProperty('locale', 'ru_RU')
            ->addProperty('profile:username', $developer->name);

        // --- Twitter Card ---
        TwitterCard::setType('summary')
            ->setTitle($title)
            ->setDescription($description)
            ->setImage($image);

        // --- JSON-LD: schema.org/VideoGame ---
        JsonLd::setType('ProfilePage');
        JsonLd::setTitle($title);
        JsonLd::setDescription($description);
        JsonLd::addImage($image);
        JsonLd::addValue('url', $canonical);
        JsonLd::addValue('mainEntity', [
            '@type' => 'Organization',
            'name' => $developer->name,
            'url' => $canonical,
        ]);

        return view('web.developers.card.index', compact('developer', 'source', 'views_count'));
    }
}
