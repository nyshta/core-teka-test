<?php

use CoreTeka\Board\Board;
use CoreTeka\Board\BoardBuilder;
use CoreTeka\Cell\CellFactory;
use CoreTeka\Cell\HoleCell;
use CoreTeka\Cell\NumberedCell;
use CoreTeka\Exception\BoardDoesNotExistException;
use CoreTeka\Game;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CoreTeka\Game
 */
class GameTest extends TestCase
{
    private BoardBuilder|MockObject $boardBuilder;
    private Game $game;

    public function setUp(): void
    {
        $cellFactory = new CellFactory();
        $this->boardBuilder = $this->getMockBuilder(BoardBuilder::class)
        ->disableOriginalConstructor()
        ->onlyMethods(['createBoardWithInitialPoint'])->getMock();

        $this->game = new Game($cellFactory, $this->boardBuilder);
    }

    /**
     * @covers \CoreTeka\Game::openCell
     * @return void
     */
    public function testOpenCellWhenNotInitializedGame(): void
    {
        self::expectException(BoardDoesNotExistException::class);

        $this->game->openCell(2,2);
    }

    /**
     * @covers \CoreTeka\Game::openCell
     * @return void
     */
    public function testOpenCellOnFirstClick(): void
    {
        $board = $this->createBoard();
        $this->boardBuilder->expects(self::once())
            ->method('createBoardWithInitialPoint')
            ->willReturn($this->createBoard());

        $this->game->initiateBoard(3,3,1);
        $this->game->openCell(2,2);
        $result = $this->game->getBoard();

        self::assertEquals($board, $result);
    }

    /**
     * @covers \CoreTeka\Game::openCell
     * @return void
     */
    public function testOpenCellOnHole(): void
    {
        $board = $this->createBoard();
        $this->boardBuilder->expects(self::once())
            ->method('createBoardWithInitialPoint')
            ->willReturn($this->createBoard());

        $this->game->initiateBoard(3,3,1);
        //click at the hole:
        $this->game->openCell(0,0);

        $expectedCells = $board->getCells();
        $expectedCells[0][0] = new HoleCell(0, 0, true);
        $expected = new Board($expectedCells);

        $result = $this->game->getBoard();

        self::assertEquals($expected, $result);

        //click again at the other cell, the board won't change:
        $this->game->openCell(0,1);
        $resultAfterSecondClick = $this->game->getBoard();

        self::assertEquals($expected, $resultAfterSecondClick);
    }

    /**
     * @covers \CoreTeka\Game::openCell
     * @return void
     */
    public function testOpenCellOnNumberedNotZeroCell(): void
    {
        $board = $this->createBoard();
        $this->boardBuilder->expects(self::once())
            ->method('createBoardWithInitialPoint')
            ->willReturn($this->createBoard());

        $this->game->initiateBoard(3,3,1);
        //click at the numbered not zero cell:
        $this->game->openCell(0,1);

        $expectedCells = $board->getCells();
        $expectedCells[0][1] = new NumberedCell(0, 1, 1, true);
        $expected = new Board($expectedCells);

        $result = $this->game->getBoard();

        self::assertEquals($expected, $result);
    }

    /**
     * @covers \CoreTeka\Game::openCell
     * @return void
     */
    public function testOpenCellOnZeroCell(): void
    {
        $board = $this->createBoard();
        $this->boardBuilder->expects(self::once())
            ->method('createBoardWithInitialPoint')
            ->willReturn($this->createBoard());

        $this->game->initiateBoard(3,3,1);
        //click at the zero cell, nearest numbered cells should be opened:
        $this->game->openCell(0,2);

        $expectedCells = $board->getCells();
        $expectedCells[0][1] = new NumberedCell(0, 1, 1, true);
        $expectedCells[0][2] = new NumberedCell(0, 2, 0, true);
        $expected = new Board($expectedCells);

        $result = $this->game->getBoard();

        self::assertEquals($expected, $result);
    }

    private function createBoard(): Board
    {
        return new Board(
            [
                0 => [
                    0 => new HoleCell(0, 0, 0),
                    1 => new NumberedCell(0, 1, 1, false),
                    2 => new NumberedCell(0, 2, 0, false),
                ],
                1 => [
                    0 => new NumberedCell(1, 0, 1, false),
                    1 => new NumberedCell(1, 1, 1, true),
                    2 => new NumberedCell(1, 2, 0, true),
                ],
                2 => [
                    0 => new NumberedCell(2, 0, 0, false),
                    1 => new NumberedCell(2, 1, 0, true),
                    2 => new NumberedCell(2, 2, 0, true),
                ],
            ]
        );
    }
}
