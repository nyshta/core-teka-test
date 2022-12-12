<?php

namespace CoreTekaTests;

use CoreTeka\Board\BoardBuilder;
use CoreTeka\Board\BoardConfigInterface;
use CoreTeka\Exception\BoardDoesNotExistException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use CoreTeka\Game;
use CoreTeka\Cell\CellFactory;

/**
 * @covers \CoreTeka\Game
 */
class GameTest extends TestCase
{
//    private BoardConfigInterface|MockObject $config;
//    private BoardInterface|MockObject $board;
    private CellFactory|MockObject $cellFactory;
    private BoardBuilder|MockObject $boardBuilder;
    private Game $game;

    public function setUp(): void
    {
//        $this->config = $this->createMock(BoardConfigInterface::class);
//        $this->board = $this->createMock(BoardInterface::class);
        $this->cellFactory = $this->createMock(CellFactory::class);
        $this->boardBuilder = $this->createMock(BoardBuilder::class);

        $this->game = new Game($this->cellFactory, $this->boardBuilder);
    }

    /**
     * @covers       \CoreTeka\Game::initiateBoard
     * @dataProvider holesNumberProvider
     *
     * @param int $width
     * @param int $high
     * @param int $holesNumber
     * @param int $expectedHolesNumber
     *
     * @return void
     */
    public function testInitiateBoard(int $width, int $high, int $holesNumber, int $expectedHolesNumber): void
    {
        $this->boardBuilder->expects(self::exactly(1))
            ->method('createBoardConfig')
            ->with($width, $high, $expectedHolesNumber);

        $this->game->initiateBoard($width, $high, $holesNumber);
    }

    public function holesNumberProvider(): array
    {
        return [
            'too much holes' => [
                'width' => 4,
                'high' => 4,
                'holesNumber' => 100,
                'expectedHolesNumber' => 7,
            ],
            'ok holes number' => [
                'width' => 4,
                'high' => 4,
                'holesNumber' => 3,
                'expectedHolesNumber' => 3,
            ],
        ];
    }

    /**
     * @covers \CoreTeka\Game::openCell
     * @return void
     */
    public function testOpenCellWhenNoConfigIsSet()
    {
        self::expectException(BoardDoesNotExistException::class);
        $this->game->openCell(2, 2);
    }

    /**
     * @covers \CoreTeka\Game::openCell
     * @return void
     */
    public function testOpenCellWhenClickIsOutOfTheBoard()
    {
        $config = $this->createMock(BoardConfigInterface::class);
        $config->expects(self::exactly(1))
            ->method('isCoordinatesOnBoard')
            ->willReturn(false);

        $this->boardBuilder->expects(self::any())
            ->method('createBoardConfig')
            ->willReturn($config);

        $this->boardBuilder->expects(self::never())->method('createBoardWithInitialPoint');
        $this->cellFactory->expects(self::never())->method('createOpenedCell');
        $this->boardBuilder->expects(self::never())->method('createBoardWithReplacedCell');

        $this->game->initiateBoard(3, 3, 1);
        $this->game->openCell(2, 2);
    }

    /**
     * @covers \CoreTeka\Game::openCell
     * @return void
     */
    public function testOpenCellWhenFirstClick(): void
    {
        //todo
    }
}
