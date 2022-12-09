<?php

namespace CoreTekaTests\Board;

use CoreTeka\Board\BoardBuilder;
use CoreTeka\Cell\CellFactory;
use CoreTeka\Cell\HoleCellInterface;
use CoreTeka\Cell\NumberedCellInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CoreTeka\Board\BoardBuilder
 */
class BoardBuilderTest extends TestCase
{
    private CellFactory $cellFactory;
    private BoardBuilder $builder;

    protected function setUp(): void
    {
        $this->cellFactory = self::createMock(CellFactory::class);

        $this->builder = new BoardBuilder($this->cellFactory);
    }

    /**
     * @covers \CoreTeka\Board\BoardBuilder::createBoardConfig
     *
     * @return void
     */
    public function testCreateBoardConfig(): void
    {
        $config = $this->builder->createBoardConfig(10, 20, 3);

        self::assertEquals(10, $config->getWidth());
        self::assertEquals(20, $config->getHigh());
        self::assertEquals(3, $config->getHolesNumber());
    }

    /**
     * @covers \CoreTeka\Board\BoardBuilder::createBoardWithInitialPoint
     *
     * @return void
     */
    public function testCreateBoardWithInitialPoint(): void
    {
        $this->cellFactory->expects(self::exactly(8))
            ->method('createNumberedCell')
            ->willReturn(self::createMock(NumberedCellInterface::class));

        $this->cellFactory->expects(self::exactly(1))
            ->method('createHole')
            ->willReturn(self::createMock(HoleCellInterface::class));

        $config = $this->builder->createBoardConfig(3, 3, 1);
        $board = $this->builder->createBoardWithInitialPoint($config, 2, 2);

    }

//    public function testCreateBoardWithReplacedCell(): void
//    {
//        //todo
//    }
}
