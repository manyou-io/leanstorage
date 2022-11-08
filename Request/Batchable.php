<?php

declare(strict_types=1);

namespace Manyou\LeanStorage\Request;

interface Batchable
{
    public function toBatchRequest(string $basePath): array;
}
