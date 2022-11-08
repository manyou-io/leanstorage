<?php

declare(strict_types=1);

namespace Manyou\LeanStorage\Request;

interface HasJsonBody extends Request
{
    public function getJsonBody(): array;
}
