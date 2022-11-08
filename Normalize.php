<?php

declare(strict_types=1);

namespace Manyou\LeanStorage;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

class Normalize
{
    public static function date(DateTimeInterface $date): array
    {
        $date = DateTimeImmutable::createFromInterface($date)
            ->setTimezone(new DateTimeZone('UTC'))
            ->format('Y-m-d\TH:i:s.vp');

        return ['__type' => 'Date', 'iso' => $date];
    }

    public static function pointer(string $className, string $objectId): array
    {
        return ['__type' => 'Pointer', 'className' => $className, 'objectId' => $objectId];
    }
}
