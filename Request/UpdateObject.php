<?php

declare(strict_types=1);

namespace Manyou\LeanStorage\Request;

class UpdateObject implements Request, Batchable, HasJsonBody
{
    use BatchableTrait;

    public function __construct(
        public readonly string $collection,
        public readonly string $objectId,
        public readonly array $object,
    ) {
    }

    public function getJsonBody(): array
    {
        return $this->object;
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    public function getPath(): string
    {
        return "classes/{$this->collection}/{$this->objectId}";
    }
}
