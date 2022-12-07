<?php

namespace CoreTeka\Board;

use CoreTeka\Cell\CellInterface;
use CoreTekaException\CellDoesNotExistsException;
use CoreTekaException\CellIsOutOfTheBoardException;

class Board implements BoardInterface
{
    private int $width;
    private int $high;
    private int $holesNumber;
    /** @var CellInterface[][] */
    private array $cells;

    /**
     * @param int $width
     * @param int $high
     * @param int $holesNumber
     * @param CellInterface[][] $cells
     */
    public function __construct(int $width, int $high, int $holesNumber, array $cells)
    {
        $this->width = $width;
        $this->high = $high;
        $this->holesNumber = min($holesNumber, $width * $high);
        $this->cells = $cells;
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
    public function getCells(): array
    {
        return $this->cells;
    }

    /**
     * @inheritDoc
     */
    public function getCell(int $x, int $y): CellInterface
    {
        if (!$this->isThePointOnBoard($x, $y)) {
            throw new CellIsOutOfTheBoardException();
        }

        $cell = $this->findCell($x, $y);

        if (is_null($cell)) {
            throw new CellDoesNotExistsException();
        }

        return $cell;
    }

    /**
     * @inheritDoc
     */
    public function isThePointOnBoard(int $x, int $y): bool
    {
        if ($x < 0 || $x > $this->width - 1 || $y < 0 || $y > $this->high - 1) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function findCell(int $x, int $y): ?CellInterface
    {
        if (isset($this->cells[$x][$y])) {
            return $this->cells[$x][$y];
        }

        return null;
    }
}
