<?php

namespace CoreTekaTests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use CoreTeka\Board;
use CoreTeka\Cell\CellFactory;

class BoardTest extends TestCase
{
    const WIDTH = 40;
    const HIGH = 40;
    const HOLES = 10;

    private CellFactory | MockObject $cellFactory;
    private Board $board;

    public function setUp(): void
    {
        $this->cellFactory = $this->createMock(CellFactory::class);

        $this->board = new Board(self::WIDTH, self::HIGH, self::HOLES, $this->cellFactory);
    }

    public function testGetAllCellsOnEmptyBoard()
    {
        $cells = $this->board->get_all_cells();
        $this->assertEmpty($cells);
    }
}
