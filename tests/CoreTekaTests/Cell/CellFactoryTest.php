<?php

namespace CoreTekaTests\Cell;

use CoreTeka\Cell\CellFactory;
use CoreTeka\Cell\CellInterface;
use CoreTeka\Cell\HoleCell;
use CoreTeka\Cell\NumberedCell;
use CoreTeka\Exception\CantOpenTheCellException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CoreTeka\Cell\CellFactory
 */
class CellFactoryTest extends TestCase
{
    private CellFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new CellFactory();
    }

    /**
     * @covers CellFactory::createHole()
     * @return void
     */
    public function testCreateHole(): void
    {
        $hole = $this->factory->createHole(3, 5);

        self::assertEquals($hole->getX(), 3);
        self::assertEquals($hole->getY(), 5);
        self::assertFalse($hole->isOpened());
    }

    /**
     * @covers CellFactory::createNumberedCell()
     * @return void
     */
    public function testCreateNumberedCell(): void
    {
        $cell = $this->factory->createNumberedCell(3, 5, 2);

        self::assertEquals($cell->getX(), 3);
        self::assertEquals($cell->getY(), 5);
        self::assertEquals($cell->getNumber(), 2);
        self::assertFalse($cell->isOpened());
    }

    /**
     * @covers       CellFactory::createOpenedCell()
     * @dataProvider cellProvider
     *
     * @param CellInterface $cell
     *
     * @return void
     */
    public function testCreateOpenedCell(CellInterface $cell): void
    {
        $result = $this->factory->createOpenedCell($cell);

        self::assertTrue($result->isOpened());
    }

    public function cellProvider(): array
    {
        return [
            [new HoleCell(3, 3)],
            [new NumberedCell(3, 3, 3)],
        ];
    }

    public function testCreateOpenedCellWhenUnexpectedType()
    {
        $cell = $this->createMock(CellInterface::class);
        self::expectException(CantOpenTheCellException::class);

        $this->factory->createOpenedCell($cell);
    }
}
