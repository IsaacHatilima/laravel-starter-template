<?php

namespace App\Contracts;

interface DtoContract
{
    public static function fromModel(object $model): self;

    /**
     * @return array<string, scalar|null>
     */
    public function toArray(): array;
}
