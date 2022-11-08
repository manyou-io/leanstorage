<?php

declare(strict_types=1);

namespace Manyou\LeanStorage\Request;

use function is_scalar;
use function json_encode;

use const JSON_PRESERVE_ZERO_FRACTION;
use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;

class QueryCollection implements Request, HasFormBody, HasQuery
{
    public function __construct(
        public readonly string $collection,
        public readonly array $query = [],
        public readonly array $body = [],
    ) {
    }

    public function getFormBody(): array
    {
        return $this->encodeQuery($this->body);
    }

    public function getQuery(): array
    {
        return $this->encodeQuery($this->query);
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function getPath(): string
    {
        return "classes/{$this->collection}";
    }

    private function encodeQuery(array $query)
    {
        foreach ($query as $key => $value) {
            if ($value === null) {
                unset($query[$key]);
                continue;
            }

            if (! is_scalar($value)) {
                $value = $this->jsonEncode($value);
            }

            $query[$key] = $value;
        }

        return $query;
    }

    private function jsonEncode(array $value)
    {
        return json_encode($value, JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
