<?php

declare(strict_types=1);

namespace Manyou\LeanStorage\Request;

use InvalidArgumentException;

trait BatchableTrait
{
    public function toBatchRequest(string $basePath): array
    {
        if (! $this instanceof Request) {
            throw new InvalidArgumentException('Not a request.');
        }

        $request = [
            'method' => $this->getMethod(),
            'path' => $basePath . $this->getPath(),
        ];

        if ($this instanceof HasJsonBody) {
            $request += [
                'body' => $this->getJsonBody(),
            ];
        }

        return $request;
    }
}
