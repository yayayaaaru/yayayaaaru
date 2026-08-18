<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\Requests;

use App\Http\Integrations\YandexGames\Responses\FeedResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetFeedRequest extends Request
{
    protected Method $method = Method::GET;

    protected function defaultQuery(): array
    {
        return [
            'lang' => 'ru',
            'tab' => 'new',
        ];
    }

    public function resolveEndpoint(): string
    {
        return '/feed';
    }

    public function createDtoFromResponse(Response $response): FeedResponse
    {
        return FeedResponse::fromSaloonResponse($response);
    }
}
