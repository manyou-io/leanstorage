<?php

declare(strict_types=1);

namespace Manyou\LeanStorage;

use RuntimeException;

class RequestException extends RuntimeException
{
    public static function fromResponse(array $response): self
    {
        return new self($response['error'] ?? '', $response['code'] ?? 0);
    }
}
