<?php

declare(strict_types=1);

namespace Manyou\LeanStorage;

use Generator;
use InvalidArgumentException;
use Manyou\LeanStorage\Request\QueryCollection;

class CollectionIterator
{
    public function __construct(private LeanStorageClient $client)
    {
    }

    /** @return Generator|array[] */
    public function __invoke(string $collection, array $where = [], int $limit = 1000): Generator
    {
        if (isset($where['objectId'])) {
            throw new InvalidArgumentException('"objectId" will be used as pagination cursor.');
        }

        do {
            $count = 0;

            $query = ['order' => 'objectId', 'limit' => $limit];
            if ($where !== []) {
                $query += ['where' => $where];
            }

            $response = $this->client->request(new QueryCollection($collection, $query))->wait();

            foreach ($response['results'] as $result) {
                $objectId = $result['objectId'];
                $count++;

                yield $result;
            }

            $where['objectId'] = ['$gt' => $objectId];
        } while ($count === $limit);
    }
}
