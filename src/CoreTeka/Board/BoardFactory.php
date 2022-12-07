<?php

namespace CoreTeka\Board;

use CoreTeka\Cell\CellInterface;

class BoardFactory
{
    /**
     * @param int $width
     * @param int $high
     * @param int $holesNumber
     *
     * @return BoardInterface
     */
    public function createEmptyBoard(int $width, int $high, int $holesNumber): BoardInterface
    {
        return new Board($width, $high, $holesNumber, []);
    }

    /**
     * @param BoardInterface $board
     * @param CellInterface[][] $cells
     *
     * @return BoardInterface
     */
    public function createBoardWithReplacedCell(BoardInterface $board, CellInterface $replaceWithCell): BoardInterface
    {
        $x = $replaceWithCell->getX();
        $y = $replaceWithCell->getY();

        $oldCell = $board->findCell($x, $y);

        if(is_null($oldCell)) {
            return $board;
        }

        $cells = $board->getCells();

        $cells[$x][$y] = $replaceWithCell;

        return new Board($board->getWidth(), $board->getHigh(), $board->getHolesNumber(), $cells);
    }
}
