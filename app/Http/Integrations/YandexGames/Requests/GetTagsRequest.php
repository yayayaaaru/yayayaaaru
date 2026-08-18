<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\Requests;

use App\Http\Integrations\YandexGames\Responses\TagsResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetTagsRequest extends Request
{
    protected Method $method = Method::GET;

    protected function defaultQuery(): array
    {
        return [
            'lang' => 'ru',
        ];
    }

    public function resolveEndpoint(): string
    {
        return '/tags';
    }

    public function createDtoFromResponse(Response $response): TagsResponse
    {
        return TagsResponse::fromSaloonResponse($response);
    }
}
