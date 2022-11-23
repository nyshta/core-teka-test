<?php

namespace CoreTeka;

interface BoardInterface
{
    /**
     * Returns an array of the newly opened cells or void if the cell is already opened
     *
     * @param int $x
     * @param int $y
     *
     * @return \CellInterface | \OkCellInterface | \HoleInterface [] | null
     */
    public function open(int $x, int $y): ?array;

    /**
     * Returns an array or the all cells on board
     */
    public function get_all_cells(): array;
}
