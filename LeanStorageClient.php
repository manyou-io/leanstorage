<?php

declare(strict_types=1);

namespace Manyou\LeanStorage;

use Generator;
use GuzzleHttp\Promise\PromiseInterface;
use Manyou\LeanStorage\Request\Batchable;
use Manyou\LeanStorage\Request\HasFormBody;
use Manyou\LeanStorage\Request\HasJsonBody;
use Manyou\LeanStorage\Request\HasQuery;
use Manyou\LeanStorage\Request\Request;
use Manyou\PromiseHttpClient\PromiseHttpClientInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function array_map;
use function parse_url;

use const PHP_URL_PATH;

class LeanStorageClient
{
    private array $options;
    private string $basePath;

    public function __construct(
        private PromiseHttpClientInterface $httpClient,
        string $endpoint,
        string $appId,
        string $appKey,
        string $sessionToken = '',
    ) {
        $this->basePath = parse_url($endpoint, PHP_URL_PATH);

        $defaultHeaders = [
            'Content-Type' => 'application/json',
            'X-LC-Id' => $appId,
            'X-LC-Key' => $appKey,
        ];

        if ($sessionToken !== '') {
            $defaultHeaders['X-LC-Session'] = $sessionToken;
        }

        $this->options = ['base_uri' => $endpoint, 'headers' => $defaultHeaders];
    }

    public function request(Request $request): PromiseInterface
    {
        $options = [];

        if ($request instanceof HasJsonBody) {
            $options += ['json' => $request->getJsonBody()];
        } elseif ($request instanceof HasFormBody) {
            $options += ['body' => $request->getFormBody()];
        }

        if ($request instanceof HasQuery) {
            $options += ['query' => $request->getQuery()];
        }

        return $this->httpClient->request($request->getMethod(), $request->getPath(), $options + $this->options)
            ->then(static function (ResponseInterface $response) {
                $response = $response->toArray();

                if (isset($response['error'])) {
                    throw RequestException::fromResponse($response);
                }

                return $response;
            });
    }

    #[AsMessageHandler]
    public function handle(Request $request): mixed
    {
        return $this->request($request)->wait();
    }

    /**
     * @param Batchable[] $requests
     *
     * @return Generator|PromiseInterface[]
     */
    public function batch(Batchable ...$requests): Generator
    {
        $batch = $this->httpClient->request('POST', 'batch', [
            'json' => [
                'requests' => array_map(
                    fn (Batchable $request) => $request->toBatchRequest($this->basePath),
                    $requests,
                ),
            ],
        ] + $this->options)->then(static fn (ResponseInterface $response) => $response->toArray());

        foreach ($requests as $i => $request) {
            yield $i => $batch->then(static function (array $responses) use ($i) {
                $response = $responses[$i];

                if (isset($response['error'])) {
                    throw RequestException::fromResponse($response['error']);
                }

                if (isset($response['success'])) {
                    return $response['success'];
                }

                throw new RequestException('Unknown response.');
            });
        }
    }
}
