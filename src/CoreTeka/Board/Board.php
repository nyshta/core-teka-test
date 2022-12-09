<?php

namespace CoreTeka\Board;

use CoreTeka\Cell\CellInterface;
use CoreTeka\Exception\CellDoesNotExistsException;

class Board implements BoardInterface
{
    /** @var CellInterface[][] */
    private array $cells;

    /**
     * @param CellInterface[][] $cells
     */
    public function __construct(array $cells)
    {
        $this->cells = $cells;
        //todo:
        // Here is a weak spot: cells should be placed on board based on their inner coordinates.
        // But let's pretend it's ok, especially because there is a Builder for this class
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
        $cell = $this->findCell($x, $y);

        if (is_null($cell)) {
            throw new CellDoesNotExistsException();
        }

        return $cell;
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
