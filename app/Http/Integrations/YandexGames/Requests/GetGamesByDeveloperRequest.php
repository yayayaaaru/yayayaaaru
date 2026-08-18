<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\Requests;

use App\Http\Integrations\YandexGames\Responses\GamesByDeveloperResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetGamesByDeveloperRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public int $developer_id
    )
    {
    }

    protected function defaultQuery(): array
    {
        return [
            'lang' => 'ru',
            'developer_id' => $this->developer_id,
        ];
    }

    public function resolveEndpoint(): string
    {
        return '/developer_games';
    }

    public function createDtoFromResponse(Response $response): GamesByDeveloperResponse
    {
        return GamesByDeveloperResponse::fromSaloonResponse($response);
    }
}
