<?php

namespace CoreTeka\Cell;

class CellFactory
{
    public function createHole(int $x, int $y): CellInterface|HoleInterface
    {

    }

    public function createNumberedCell(int $x, int $y): CellInterface|OkCellInterface
    {

    }

    public function createEmptyCell(): CellInterface|OkCellInterface
    {

    }
}
