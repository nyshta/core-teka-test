<?php

namespace CoreTeka\Board;

use CoreTeka\Cell\CellInterface;
use CoreTeka\Exception\CellDoesNotExistsException;

interface BoardInterface
{
    /**
     * @return CellInterface[][]
     */
    public function getCells(): array;

    /**
     * @param int $x
     * @param int $y
     *
     * @throws CellDoesNotExistsException
     *
     * @return CellInterface
     */
    public function getCell(int $x, int $y): CellInterface;

    /**
     * @param int $x
     * @param int $y
     *
     * @return CellInterface|null
     */
    public function findCell(int $x, int $y): ?CellInterface;
}
