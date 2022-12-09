<?php

namespace CoreTekaTests\Board;

use CoreTeka\Board\Board;
use CoreTeka\Cell\NumberedCell;
use CoreTeka\Exception\CellDoesNotExistsException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CoreTeka\Board\Board
 */
class BoardTest extends TestCase
{
    /**
     * @covers \CoreTeka\Board\Board::findCell
     *
     * @return void
     */
    public function testFindCell(): void
    {
        $cell = new NumberedCell(3, 3, 0);
        $board = new Board([3 => [3 => $cell]]);

        self::assertEquals($cell, $board->findCell(3, 3));
    }

    /**
     * @covers \CoreTeka\Board\Board::findCell
     *
     * @return void
     */
    public function testFindCellWhenCantFind(): void
    {
        $board = new Board([]);

        self::assertNull($board->findCell(3, 3));
    }

    /**
     * @covers \CoreTeka\Board\Board::getCell
     *
     * @return void
     */
    public function testGetCell(): void
    {
        $cell = new NumberedCell(3, 3, 0);
        $board = new Board([3 => [3 => $cell]]);

        self::assertEquals($cell, $board->getCell(3, 3));
    }

    /**
     * @covers \CoreTeka\Board\Board::getCell
     *
     * @return void
     */
    public function testGetCellWhenThereIsNoCell(): void
    {
        $board = new Board([]);
        self::expectException(CellDoesNotExistsException::class);

        $board->getCell(3, 3);
    }
}
