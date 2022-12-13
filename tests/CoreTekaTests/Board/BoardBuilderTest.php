<?php

namespace CoreTekaTests\Board;

use CoreTeka\Board\BoardBuilder;
use CoreTeka\Cell\CellFactory;
use CoreTeka\Cell\CellInterface;
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
     * @covers \CoreTeka\Board\BoardBuilder::createBoard
     *
     * @return void
     */
    public function testCreateBoardWhenCheckingTotalCellsNumber(): void
    {
        $config = $this->builder->createBoardConfig(3, 3, 1);
        $board = $this->builder->createBoard($config);

        $cells = $board->getCells();
        $cellsNumber = 0;
        array_walk_recursive($cells, function ($cell) use (&$cellsNumber) {
            if ($cell instanceof CellInterface) {
                $cellsNumber++;
            }
        });

        self::assertEquals(9, $cellsNumber);
    }

    /**
     * @dataProvider allCellsCoordinatesProvider
     * @covers       \CoreTeka\Board\BoardBuilder::createBoard
     *
     * @param int $x
     * @param int $y
     *
     * @return void
     */
    public function testCreateBoardWhenCheckingCellsMapping(int $x, int $y): void
    {
        $config = $this->builder->createBoardConfig(3, 3, 1);
        $board = $this->builder->createBoard($config);

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

    public function testCreateBoardWhenCheckingHoleNumbers()
    {
        $config = $this->builder->createBoardConfig(3, 3, 4);
        $board = $this->builder->createBoard($config);

        $cells = $board->getCells();
        $totalHolesNumber = 0;

        array_walk_recursive($cells, function ($cell) use (&$totalHolesNumber) {
            if ($cell instanceof HoleCellInterface) {
                $totalHolesNumber++;
            }
        });

        self::assertEquals(4, $totalHolesNumber);
    }

    /**
     * @covers \CoreTeka\Board\BoardBuilder::createBoard
     *
     * @return void
     */
    public function testCreateBoardWhenCheckingNumberedCells(): void
    {
        $config = $this->builder->createBoardConfig(3, 3, 4);
        $board = $this->builder->createBoard($config);

        $cells = $board->getCells();

        array_walk_recursive($cells, function ($cell) use ($board) {
            if ($cell instanceof HoleCellInterface) {
                return;
            }
            $holeNumbers = 0;
            foreach ($this->pointsAround($cell->getX(), $cell->getY()) as $point) {
                if ($board->findCell($point['x'], $point['y']) instanceof HoleCellInterface) {
                    $holeNumbers++;
                }
            }
            self::assertEquals($holeNumbers, $cell->getNumber());
        });
    }

    private function pointsAround(int $x, int $y): array
    {
        return [
            ['x' => $x, 'y' => $y + 1],
            ['x' => $x + 1, 'y' => $y + 1],
            ['x' => $x + 1, 'y' => $y],
            ['x' => $x + 1, 'y' => $y - 1],
            ['x' => $x, 'y' => $y - 1],
            ['x' => $x - 1, 'y' => $y - 1],
            ['x' => $x - 1, 'y' => $y],
            ['x' => $x - 1, 'y' => $y + 1],
        ];
    }

    /**
     * @covers \CoreTeka\Board\BoardBuilder::createBoardWithReplacedCell()
     *
     * @return void
     */
    public function testCreateBoardWithReplacedCell(): void
    {
        $config = $this->builder->createBoardConfig(3, 3, 1);
        $board = $this->builder->createBoard($config);

        $cell = $board->getCell(0, 0);
        self::assertFalse($cell->isOpened());

        $newCell = $this->cellFactory->createOpenedCell($cell);

        $newBoard = $this->builder->createBoardWithReplacedCell($board, $newCell);

        self::assertEquals($newCell, $newBoard->getCell(0, 0));
        self::assertEquals($board->getCell(0, 1), $newBoard->getCell(0, 1));
        self::assertEquals($board->getCell(0, 2), $newBoard->getCell(0, 2));
        self::assertEquals($board->getCell(1, 0), $newBoard->getCell(1, 0));
        self::assertEquals($board->getCell(1, 1), $newBoard->getCell(1, 1));
        self::assertEquals($board->getCell(1, 2), $newBoard->getCell(1, 2));
        self::assertEquals($board->getCell(2, 0), $newBoard->getCell(2, 0));
        self::assertEquals($board->getCell(2, 1), $newBoard->getCell(2, 1));
        self::assertEquals($board->getCell(2, 2), $newBoard->getCell(2, 2));
    }
}
