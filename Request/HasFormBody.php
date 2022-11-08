<?php

declare(strict_types=1);

namespace Manyou\LeanStorage\Request;

interface HasFormBody
{
    public function getFormBody(): array;
}
