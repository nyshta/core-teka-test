<?php

namespace CoreTeka\Board;

use CoreTeka\Cell\CellInterface;
use CoreTeka\Exception\CellDoesNotExistsException;
use CoreTeka\Exception\CellIsOutOfTheBoardException;

interface BoardInterface
{
    public function getWidth(): int;

    public function getHigh(): int;

    public function getHolesNumber(): int;

    /**
     * @return CellInterface[][]
     */
    public function getCells(): array;

    public function isThePointOnBoard(int $x, int $y): bool;

    /**
     * @param int $x
     * @param int $y
     *
     * @throws CellIsOutOfTheBoardException
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
