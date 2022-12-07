<?php

namespace CoreTeka\Cell;

class HoleCell implements HoleCellInterface
{
    private int $x;
    private int $y;
    private bool $opened;

    /**
     * @param int $x
     * @param int $y
     * @param bool $opened
     */
    public function __construct(int $x, int $y, bool $opened = false)
    {
        $this->x = $x;
        $this->y = $y;
        $this->opened = $opened;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function isItOpened(): bool
    {
        return $this->opened;
    }
}
