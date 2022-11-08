<?php

declare(strict_types=1);

namespace Manyou\LeanStorage\Request;

class CreateObject implements Request, Batchable, HasJsonBody
{
    use BatchableTrait;

    public function __construct(
        public readonly string $collection,
        public readonly array $object,
    ) {
    }

    public function getJsonBody(): array
    {
        return $this->object;
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function getPath(): string
    {
        return "classes/{$this->collection}";
    }
}
