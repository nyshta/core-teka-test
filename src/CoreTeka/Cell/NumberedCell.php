<?php

namespace CoreTeka\Cell;

class NumberedCell implements NumberedCellInterface
{
    private int $x;
    private int $y;
    private int $number;
    private bool $opened;

    /**
     * @param int $x
     * @param int $y
     * @param int $number
     * @param bool $opened
     */
    public function __construct(int $x, int $y, int $number, bool $opened = false)
    {
        $this->x = $x;
        $this->y = $y;
        $this->number = $number;
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

    public function isOpened(): bool
    {
        return $this->opened;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}
