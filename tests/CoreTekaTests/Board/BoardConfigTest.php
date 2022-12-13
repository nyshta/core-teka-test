<?php

namespace CoreTekaTests\Board;

use CoreTeka\Board\BoardConfig;
use CoreTeka\Exception\TooMuchHolesException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CoreTeka\Board\BoardConfig
 */
class BoardConfigTest extends TestCase
{
    const WIDTH = 10;
    const HIGH = 10;

    /**
     * @covers \CoreTeka\Board\BoardConfig::__construct
     * @return void
     */
    public function testConstruct(): void
    {
        $config = new BoardConfig(self::WIDTH, self::HIGH, 30);
        $actualHolesNumber = $config->getHolesNumber();

        self::assertEquals(30, $actualHolesNumber);

    }
    /**
     * @covers \CoreTeka\Board\BoardConfig::__construct
     * @return void
     */
    public function testConstructWhenTooMuchHoles(): void
    {
        self::expectException(TooMuchHolesException::class);
        new BoardConfig(self::WIDTH, self::HIGH, 200);
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
