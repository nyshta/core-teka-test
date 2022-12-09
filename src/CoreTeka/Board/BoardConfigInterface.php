<?php

namespace CoreTeka\Board;

interface BoardConfigInterface
{
    public function getWidth(): int;

    public function getHigh(): int;

    public function getHolesNumber(): int;

    public function isCoordinatesOnBoard(int $x, int $y): bool;
}
