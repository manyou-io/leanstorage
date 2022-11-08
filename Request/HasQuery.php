<?php

declare(strict_types=1);

namespace Manyou\LeanStorage\Request;

interface HasQuery
{
    public function getQuery(): array;
}
