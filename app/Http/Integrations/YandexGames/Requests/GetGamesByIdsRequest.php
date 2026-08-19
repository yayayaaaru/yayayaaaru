<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\Requests;

use App\Http\Integrations\YandexGames\Responses\GamesByIdsResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class GetGamesByIdsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param int[] $ids
     */
    public function __construct(
        public array $ids
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/get_games';
    }

    protected function defaultQuery(): array
    {
        return [
            'lang' => 'ru',
            'draft' => 'false',
        ];
    }

    protected function defaultBody(): array
    {
        return [
            'appIDs' => array_map('intval', $this->ids),
            'format' => 'long',
        ];
    }

    public function createDtoFromResponse(Response $response): GamesByIdsResponse
    {
        return GamesByIdsResponse::fromSaloonResponse($response);
    }
}
