<?php

namespace CoreTekaTests\Board;

use CoreTeka\Board\BoardConfig;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CoreTeka\Board\BoardConfig
 */
class BoardConfigTest extends TestCase
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
        $config = new BoardConfig(self::WIDTH, self::HIGH, $putHolesOnBoard);
        $actualHolesNumber = $config->getHolesNumber();

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
     * @covers       \CoreTeka\Board\BoardConfig::isCoordinatesOnBoard
     *
     * @dataProvider onBoardCoordinatesProvider
     *
     * @param $x
     * @param $y
     * @param $expectedToBeOnBoard
     *
     * @return void
     */
    public function testIsCoordinatesOnBoard(int $x, int $y, bool $expectedToBeOnBoard): void
    {
        $config = new BoardConfig(self::WIDTH, self::HIGH, 0);

        self::assertEquals($expectedToBeOnBoard, $config->isCoordinatesOnBoard($x, $y));
    }

    public function onBoardCoordinatesProvider(): array
    {
        return [
            'on the board' => [
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
}
