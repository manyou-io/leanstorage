<?php

declare(strict_types=1);

namespace Manyou\LeanStorage\Request;

interface Request
{
    public function getMethod(): string;

    public function getPath(): string;
}
