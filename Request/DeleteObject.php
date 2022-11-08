<?php

declare(strict_types=1);

namespace Manyou\LeanStorage\Request;

class DeleteObject implements Request, Batchable
{
    use BatchableTrait;

    public function __construct(
        public readonly string $collection,
        public readonly string $objectId,
    ) {
    }

    public function getMethod(): string
    {
        return 'DELETE';
    }

    public function getPath(): string
    {
        return "classes/{$this->collection}/{$this->objectId}";
    }
}
