<?php

namespace CoreTeka\Board;

use CoreTeka\Cell\CellFactory;
use CoreTeka\Cell\CellInterface;
use CoreTeka\Cell\HoleCellInterface;
use CoreTeka\Exception\CellIsOutOfTheBoardException;

class BoardBuilder
{
    private $cells = [];

    private CellFactory $cellFactory;

    /**
     * @param CellFactory $cellFactory
     */
    public function __construct(CellFactory $cellFactory)
    {
        $this->cellFactory = $cellFactory;
    }

    public function createPopulatedBoardWithInitialPoint(BoardInterface $board, int $x, int $y): BoardInterface
    {
        if (!$board->isThePointOnBoard($x, $y)) {
            throw new CellIsOutOfTheBoardException();
        }

        $initialZeroCell = $this->cellFactory->createNumberedCell($x, $y, 0);
        $board = $this->insertCell($board, $initialZeroCell);

        $board = $this->populateHolesAroundPoint($board, $x, $y);
        $board = $this->populateOkCells($board);

        return $board;
    }

    private function insertCell(BoardInterface $board, CellInterface $cell): BoardInterface
    {

        $cells = $board->getCells();

        $cells[$cell->getX()][$cell->getY()] = $cell;

        return new Board($board->getWidth(), $board->getHigh(), $board->getHolesNumber(), $cells);
    }

    private function populateHolesAroundPoint(BoardInterface $board, int $x, int $y): BoardInterface
    {
        for ($i = 0; $i < $board->getHolesNumber(); $i++) {
            $board = $this->drawRandomHoleAroundPoint($board, $x, $y);
        }

        return $board;
    }

    private function drawRandomHoleAroundPoint(BoardInterface $board, int $x, int $y): BoardInterface
    {
        $x = $this->getRandExceptAroundPoint($x, $board->getWidth() - 1);
        $y = $this->getRandExceptAroundPoint($y, $board->getHigh() - 1);

        if (isset($this->cells[$x][$y])) {
            $board = $this->drawRandomHoleAroundPoint($board, $x, $y);
        }

        $holeCell = $this->cellFactory->createHole($x, $y);

        return $this->insertCell($board, $holeCell);
    }

    private function getRandExceptAroundPoint(int $point, int $maxNumber): int
    {
        $rand = rand(0, $maxNumber);

        if ($rand >= $point - 1 && $rand <= $point + 1) {
            return $this->getRandExceptAroundPoint($point, $maxNumber);
        }

        return $rand;
    }

    private function populateOkCells(BoardInterface $board): BoardInterface
    {
        for ($x = 0; $x < $board->getWidth() - 1; $x++) {
            for ($y = 0; $y < $board->getHigh() - 1; $y++) {

                $cell = $board->findCell($x, $y);
                if ($cell instanceof HoleCellInterface) {
                    continue;
                }

                $holesCount = $this->countHolesAroundPoint($board, $x, $y);
                $cell = $this->cellFactory->createNumberedCell($x, $y, $holesCount);
                $board = $this->insertCell($board, $cell);
            }
        }

        return $board;
    }

    private function countHolesAroundPoint(BoardInterface $board, int $x, int $y): int
    {
        $pointsAround = [
            ['x' => [$x], 'y' => [$y + 1]],
            ['x' => [$x + 1], 'y' => [$y + 1]],
            ['x' => [$x + 1], 'y' => [$y]],
            ['x' => [$x + 1], 'y' => [$y - 1]],
            ['x' => [$x], 'y' => [$y - 1]],
            ['x' => [$x - 1], 'y' => [$y - 1]],
            ['x' => [$x - 1], 'y' => [$y]],
            ['x' => [$x - 1], 'y' => [$y + 1]],
        ];
        $holesNumber = 0;
        foreach ($pointsAround as $point) {
            $cell = $board->findCell($point['x'], $point['y']);
            if ($cell instanceof HoleCellInterface) {
                $holesNumber++;
            }
        }

        return $holesNumber;
    }
}
