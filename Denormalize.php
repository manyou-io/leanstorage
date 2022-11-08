<?php

declare(strict_types=1);

namespace Manyou\LeanStorage;

use DateTimeImmutable;

class Denormalize
{
    public static function date(array $date): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s.vp', $date['iso']);
    }
}
