<?php

namespace CoreTeka\Cell;

use CoreTeka\Exception\CantOpenTheCellException;

class CellFactory
{
    public function createHole(int $x, int $y): HoleCellInterface
    {
        return new HoleCell($x, $y);
    }

    public function createNumberedCell(int $x, int $y, int $number, $opened = false): NumberedCellInterface
    {
        return new NumberedCell($x, $y, $number, $opened);
    }

    public function createOpenedCell(CellInterface $cell): CellInterface
    {
        if ($cell instanceof HoleCellInterface) {
            return new HoleCell($cell->getX(), $cell->getY(), true);
        }
        if ($cell instanceof NumberedCellInterface) {
            return new NumberedCell($cell->getX(), $cell->getY(), $cell->getNumber(), true);
        }

        throw new CantOpenTheCellException('Cell type has not been defined');
    }
}
