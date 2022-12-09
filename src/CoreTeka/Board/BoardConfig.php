<?php

namespace CoreTeka\Board;

class BoardConfig implements BoardConfigInterface
{
    private int $width;
    private int $high;
    private int $holesNumber;

    /**
     * @param int $width
     * @param int $high
     * @param int $holesNumber
     */
    public function __construct(int $width, int $high, int $holesNumber)
    {
        $this->width = $width;
        $this->high = $high;
        $this->holesNumber = min($holesNumber, $width * $high);
    }

    /**
     * @inheritDoc
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @inheritDoc
     */
    public function getHigh(): int
    {
        return $this->high;
    }

    /**
     * @inheritDoc
     */
    public function getHolesNumber(): int
    {
        return $this->holesNumber;
    }

    /**
     * @inheritDoc
     */
    public function isCoordinatesOnBoard(int $x, int $y): bool
    {
        $width = $this->getWidth();
        $high = $this->getHigh();

        if ($x < 0 || $x > $width - 1 || $y < 0 || $y > $high - 1) {
            return false;
        }

        return true;
    }
}
