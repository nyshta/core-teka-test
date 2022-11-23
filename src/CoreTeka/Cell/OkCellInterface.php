<?php

namespace CoreTeka\Cell;

interface OkCellInterface
{
    public function getHolesNumber(): int;

    public function setAsOpen(): void;

    public function isItOpen(): bool;
}
