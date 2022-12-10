<?php

namespace CoreTeka\Board;

use CoreTeka\Cell\CellFactory;
use CoreTeka\Cell\CellInterface;
use CoreTeka\Cell\HoleCellInterface;
use CoreTeka\Exception\CellIsOutOfTheBoardException;

class BoardBuilder
{
    private array $cells;
    private CellFactory $cellFactory;

    /**
     * @param CellFactory $cellFactory
     */
    public function __construct(CellFactory $cellFactory)
    {
        $this->cellFactory = $cellFactory;
    }

    /**
     * @param int $width
     * @param int $high
     * @param int $holesNumber
     *
     * @return \CoreTeka\Board\BoardConfigInterface
     */
    public function createBoardConfig(int $width, int $high, int $holesNumber): BoardConfigInterface
    {
        return new BoardConfig($width, $high, $holesNumber);
    }

    /**
     * @param \CoreTeka\Board\BoardInterface $board
     * @param int $x
     * @param int $y
     *
     * @return \CoreTeka\Board\BoardInterface
     */
    public function createBoardWithInitialPoint(BoardConfigInterface $config, int $x, int $y): BoardInterface
    {
        $this->cells = [];

        if (!$config->isCoordinatesOnBoard($x, $y)) {
            throw new CellIsOutOfTheBoardException();
        }

        $board = $this->populateInitialZeroCells($config, new Board([]), $x, $y);
        $board = $this->populateHoles($config, $board);
        $board = $this->populateOkCells($config, $board);

        return $board;
    }

    private function populateInitialZeroCells(BoardConfigInterface $config, BoardInterface $board, int $x, int $y): BoardInterface
    {
        $points = array_merge([['x' => $x, 'y' => $y]], $this->getPointsAround($config, $x, $y));

        foreach ($points as $point) {
            $cell = $this->cellFactory->createNumberedCell($point['x'], $point['y'], 0);
            $board = $this->insertCell($board, $cell);
        }

        return $board;
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return \int[][]
     */
    private function getPointsAround(BoardConfigInterface $config, int $x, int $y): array
    {
        $allPoints = [
            ['x' => $x, 'y' => $y + 1],
            ['x' => $x + 1, 'y' => $y + 1],
            ['x' => $x + 1, 'y' => $y],
            ['x' => $x + 1, 'y' => $y - 1],
            ['x' => $x, 'y' => $y - 1],
            ['x' => $x - 1, 'y' => $y - 1],
            ['x' => $x - 1, 'y' => $y],
            ['x' => $x - 1, 'y' => $y + 1],
        ];
        $points = [];

        foreach ($allPoints as $point){
            if($config->isCoordinatesOnBoard($point['x'], $point['y'])){
                $points[] = $point;
            }
        }
        return $points;
    }

    private function insertCell(BoardInterface $board, CellInterface $cell): BoardInterface
    {
        $cells = $board->getCells();

        $cells[$cell->getX()][$cell->getY()] = $cell;

        return new Board($cells);
    }

    private function populateHoles(BoardConfigInterface $config, BoardInterface $board): BoardInterface
    {
        for ($holesOnBoard = 0; $holesOnBoard < $config->getHolesNumber(); $holesOnBoard++) {
            $board = $this->drawRandomHole($config, $board);
        }

        return $board;
    }

    private function drawRandomHole(BoardConfigInterface $config, BoardInterface $board): BoardInterface
    {
        $randX = rand(0, $config->getWidth() - 1);
        $randY = rand(0, $config->getHigh() - 1);

        if (isset($this->cells[$randX][$randY])) {
            $board = $this->drawRandomHole($config, $board);
        }

        $holeCell = $this->cellFactory->createHole($randX, $randY);

        return $this->insertCell($board, $holeCell);
    }

    private function populateOkCells(BoardConfigInterface $config, BoardInterface $board): BoardInterface
    {
        for ($x = 0; $x < $config->getWidth() - 1; $x++) {
            for ($y = 0; $y < $config->getHigh() - 1; $y++) {

                $cell = $board->findCell($x, $y);
                if ($cell) {
                    continue;
                }

                $holesCount = $this->countHolesAroundPoint($config, $board, $x, $y);
                $cell = $this->cellFactory->createNumberedCell($x, $y, $holesCount);
                $board = $this->insertCell($board, $cell);
            }
        }

        return $board;
    }

    private function countHolesAroundPoint(BoardConfigInterface $config, BoardInterface $board, int $x, int $y): int
    {
        $pointsAround = $this->getPointsAround($config, $x, $y);
        $holesNumber = 0;

        foreach ($pointsAround as $point) {
            $cell = $board->findCell($point['x'], $point['y']);
            if ($cell instanceof HoleCellInterface) {
                $holesNumber++;
            }
        }

        return $holesNumber;
    }

    public function createBoardWithReplacedCell(BoardInterface $board, CellInterface $replaceWithCell): BoardInterface
    {
        $x = $replaceWithCell->getX();
        $y = $replaceWithCell->getY();

        $oldCell = $board->findCell($x, $y);

        if (is_null($oldCell)) {
            return $board;
        }

        $cells = $board->getCells();

        $cells[$x][$y] = $replaceWithCell;

        return new Board($cells);
    }
}
