<?php

namespace CoreTekaTests\Board;

use CoreTeka\Board\BoardBuilder;
use CoreTeka\Cell\CellFactory;
use CoreTeka\Cell\CellInterface;
use CoreTeka\Cell\HoleCellInterface;
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
        $this->cellFactory = new CellFactory();

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
    public function testCreateBoardWithInitialPointWhenTotalCellsNumberShouldBeCorrect(): void
    {
        $config = $this->builder->createBoardConfig(3, 3, 1);
        $board = $this->builder->createBoardWithInitialPoint($config, 2, 2);

        $cells = $board->getCells();
        $cellsNumber = 0;
        array_walk_recursive($cells, function ($value) use (&$cellsNumber) {
            if ($value instanceof CellInterface) {
                $cellsNumber++;
            }
        });

        self::assertEquals(9, $cellsNumber);
    }

    /**
     * @dataProvider allCellsCoordinatesProvider
     * @covers       \CoreTeka\Board\BoardBuilder::createBoardWithInitialPoint
     *
     * @param int $x
     * @param int $y
     *
     * @return void
     */
    public function testCreateBoardWithInitialPointWhenCellsCoordinatesShouldBeCorrect(int $x, int $y): void
    {
        $config = $this->builder->createBoardConfig(3, 3, 1);
        $board = $this->builder->createBoardWithInitialPoint($config, 2, 2);

        $cell = $board->findCell($x, $y);
        self::assertNotEmpty($cell);
        self::assertEquals($x, $cell->getX());
        self::assertEquals($y, $cell->getY());
    }

    public function allCellsCoordinatesProvider(): array
    {
        return [
            ['x' => 0, 'y' => 0],
            ['x' => 0, 'y' => 1],
            ['x' => 0, 'y' => 2],
            ['x' => 1, 'y' => 0],
            ['x' => 1, 'y' => 1],
            ['x' => 1, 'y' => 2],
            ['x' => 2, 'y' => 0],
            ['x' => 2, 'y' => 1],
            ['x' => 2, 'y' => 2],
        ];
    }

    /**
     * @covers \CoreTeka\Board\BoardBuilder::createBoardWithInitialPoint
     *
     * @return void
     */
    public function testCreateBoardWithInitialPointWhenInitialPointOpensCorrectly(): void
    {
        $config = $this->builder->createBoardConfig(3, 3, 1);
        $board = $this->builder->createBoardWithInitialPoint($config, 2, 2);

        /** @var \CoreTeka\Cell\NumberedCellInterface $initialPoint */
        $initialPoint = $board->getCell(2, 2);
        self::assertEquals(0, $initialPoint->getNumber());

        self::assertTrue($initialPoint->isOpened());
        self::assertTrue($board->getCell(1, 2)->isOpened());
        self::assertTrue($board->getCell(1, 1)->isOpened());
        self::assertTrue($board->getCell(2, 1)->isOpened());

        self::assertFalse($board->getCell(0, 2)->isOpened());
        self::assertFalse($board->getCell(0, 1)->isOpened());
        self::assertFalse($board->getCell(0, 0)->isOpened());
        self::assertFalse($board->getCell(1, 0)->isOpened());
        self::assertFalse($board->getCell(2, 0)->isOpened());
    }

    /**
     * @covers \CoreTeka\Board\BoardBuilder::createBoardWithInitialPoint
     *
     * @return void
     */
    public function testCreateBoardWithInitialPointWhenTheHoleExistsWithNumberedCells(): void
    {
        $config = $this->builder->createBoardConfig(3, 3, 1);
        $board = $this->builder->createBoardWithInitialPoint($config, 2, 2);

        $holeField = [
            ['x' => 0, 'y' => 2],
            ['x' => 0, 'y' => 1],
            ['x' => 0, 'y' => 0],
            ['x' => 1, 'y' => 0],
            ['x' => 2, 'y' => 0],
        ];

        $hole = null;
        foreach ($holeField as $coordinates) {
            $cell = $board->getCell($coordinates['x'], $coordinates['y']);
            if ($cell instanceof HoleCellInterface) {
                $hole = $cell;
                break;
            }
        }

        //check the hole exists and it's not opened:
        self::assertNotEmpty($hole);
        self::assertFalse($hole->isOpened());

        $x = $hole->getX();
        $y = $hole->getY();

        $uncheckedPoints = $holeField;
        unset($uncheckedPoints[array_search(['x' => $x, 'y' => $y], $uncheckedPoints)]);

        $pointsAroundHole = [
            ['x' => $x, 'y' => $y + 1],
            ['x' => $x + 1, 'y' => $y + 1],
            ['x' => $x + 1, 'y' => $y],
            ['x' => $x + 1, 'y' => $y - 1],
            ['x' => $x, 'y' => $y - 1],
            ['x' => $x - 1, 'y' => $y - 1],
            ['x' => $x - 1, 'y' => $y],
            ['x' => $x - 1, 'y' => $y + 1],
        ];

        foreach ($pointsAroundHole as $aroundHolePoint) {
            if ($config->isCoordinatesOnBoard($aroundHolePoint['x'], $aroundHolePoint['y'])) {
                /** @var \CoreTeka\Cell\NumberedCellInterface $aroundHoleCell */
                $aroundHoleCell = $board->getCell($aroundHolePoint['x'], $aroundHolePoint['y']);

                if (in_array($aroundHolePoint, $uncheckedPoints)) {
                    //check the cell near the hole is not opened (corner cells could be opened):
                    self::assertFalse($aroundHoleCell->isOpened());
                    unset(
                        $uncheckedPoints[array_search(
                            ['x' => $aroundHolePoint['x'], 'y' => $aroundHolePoint['y']],
                            $uncheckedPoints
                        )]
                    );
                }

                //check the cell around the hole is numbered with 1:
                self::assertEquals(1, $aroundHoleCell->getNumber());
            }
        }

        //check all the rest of unchecked points are numbered with 0 and not opened
        foreach ($uncheckedPoints as $uncheckedPoint)
        {
            /** @var \CoreTeka\Cell\NumberedCellInterface $zeroCell */
            $zeroCell = $board->getCell($uncheckedPoint['x'], $uncheckedPoint['y']);
            self::assertEquals(0, $zeroCell->getNumber());
            self::assertFalse($zeroCell->isOpened());
        }
    }

    /**
     * @covers \CoreTeka\Board\BoardBuilder::createBoardWithReplacedCell()
     *
     * @return void
     */
    public function testCreateBoardWithReplacedCell(): void
    {
        $config = $this->builder->createBoardConfig(3, 3, 1);
        $board = $this->builder->createBoardWithInitialPoint($config, 2, 2);

        $cell = $board->getCell(0,0);
        self::assertFalse($cell->isOpened());

        $newCell = $this->cellFactory->createOpenedCell($cell);

        $newBoard = $this->builder->createBoardWithReplacedCell($board, $newCell);

        self::assertEquals($newCell, $newBoard->getCell(0,0));
        self::assertEquals($board->getCell(0,1), $newBoard->getCell(0,1));
        self::assertEquals($board->getCell(0,2), $newBoard->getCell(0,2));
        self::assertEquals($board->getCell(1,0), $newBoard->getCell(1,0));
        self::assertEquals($board->getCell(1,1), $newBoard->getCell(1,1));
        self::assertEquals($board->getCell(1,2), $newBoard->getCell(1,2));
        self::assertEquals($board->getCell(2,0), $newBoard->getCell(2,0));
        self::assertEquals($board->getCell(2,1), $newBoard->getCell(2,1));
        self::assertEquals($board->getCell(2,2), $newBoard->getCell(2,2));
    }
}
