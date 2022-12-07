<?php

namespace CoreTekaTests\Cell;

use CoreTeka\Board\Board;
use CoreTeka\Cell\NumberedCell;
use CoreTeka\Exception\CellDoesNotExistsException;
use CoreTeka\Exception\CellIsOutOfTheBoardException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CoreTeka\Board\Board
 */
class BoardTest extends TestCase
{
    const WIDTH = 10;
    const HIGH = 10;

    /**
     * @dataProvider holesProvider
     *
     * @param int $putHolesOnBoard
     * @param int $expectedHolesNumber
     *
     * @return void
     */
    public function testConstruct(int $putHolesOnBoard, int $expectedHolesNumber): void
    {
        $board = new Board(self::WIDTH, self::HIGH, $putHolesOnBoard, []);
        $actualHolesNumber = $board->getHolesNumber();

        self::assertEquals($expectedHolesNumber, $actualHolesNumber);

    }

    public function holesProvider(): array
    {
        return [
            'ok holes number' => [
                'putHolesOnBoard' => 30,
                'expectedHolesNumber' => 30,
            ],
            'too much holes' => [
                'putHolesOnBoard' => 200,
                'expectedHolesNumber' => self::WIDTH * self::HIGH,
            ],
        ];
    }

    /**
     * @covers       \CoreTeka\Board\Board::isThePointOnBoard
     *
     * @dataProvider onBoardPointsProvider
     *
     * @param $x
     * @param $y
     * @param $expectedToBeOnBoard
     *
     * @return void
     */
    public function testIsThePointOnBoard(int $x, int $y, bool $expectedToBeOnBoard): void
    {
        $board = new Board(self::WIDTH, self::HIGH, 0, []);

        self::assertEquals($expectedToBeOnBoard, $board->isThePointOnBoard($x, $y));
    }

    public function onBoardPointsProvider(): array
    {
        return [
            'the point is on the board' => [
                'x' => 3,
                'y' => 3,
                'expectedToBeOnBoard' => true,
            ],
            'x is out of the board' => [
                'x' => 200,
                'y' => 3,
                'expectedToBeOnBoard' => false,
            ],
            'y is out of the board' => [
                'x' => 3,
                'y' => 200,
                'expectedToBeOnBoard' => false,
            ],
        ];
    }

    /**
     * @covers \CoreTeka\Board\Board::findCell
     *
     * @return void
     */
    public function testFindCell(): void
    {
        $cell = new NumberedCell(3, 3, 0);
        $board = new Board(self::WIDTH, self::HIGH, 0, [3 => [3 => $cell]]);

        self::assertEquals($cell, $board->findCell(3, 3));
    }

    /**
     * @covers \CoreTeka\Board\Board::findCell
     *
     * @return void
     */
    public function testFindCellWhenCantFind(): void
    {
        $board = new Board(self::WIDTH, self::HIGH, 0, []);

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
        $board = new Board(self::WIDTH, self::HIGH, 0, [3 => [3 => $cell]]);

        self::assertEquals($cell, $board->getCell(3, 3));
    }

    /**
     * @covers \CoreTeka\Board\Board::getCell
     *
     * @return void
     */
    public function testGetCellWhenItOutOfTheBoard(): void
    {
        $board = new Board(self::WIDTH, self::HIGH, 0, []);
        self::expectException(CellIsOutOfTheBoardException::class);

        $board->getCell(200, 200);
    }

    /**
     * @covers \CoreTeka\Board\Board::getCell
     *
     * @return void
     */
    public function testGetCellWhenThereIsNoCell(): void
    {
        $board = new Board(self::WIDTH, self::HIGH, 0, []);
        self::expectException(CellDoesNotExistsException::class);

        $board->getCell(3, 3);
    }
}
