<?php

namespace CoreTeka;

use CoreTeka\Board\BoardBuilder;
use CoreTeka\Board\BoardConfigInterface;
use CoreTeka\Board\BoardInterface;
use CoreTeka\Cell\CellFactory;
use CoreTeka\Cell\HoleCellInterface;
use CoreTeka\Cell\NumberedCellInterface;
use CoreTeka\Exception\BoardDoesNotExistException;

class Game implements GameInterface
{
    private BoardConfigInterface $config;
    private BoardInterface $board;
    private CellFactory $cellFactory;
    private BoardBuilder $boardBuilder;
    private bool $gameInProgress = false;

    /**
     * @param CellFactory $cellFactory
     * @param BoardBuilder $boardBuilder
     */
    public function __construct(CellFactory $cellFactory, BoardBuilder $boardBuilder)
    {
        $this->cellFactory = $cellFactory;
        $this->boardBuilder = $boardBuilder;
    }

    /**
     * @inheritDoc
     */
    public function initiateBoard(int $width, int $high, int $holesNumber): void
    {
        $this->config = $this->boardBuilder->createBoardConfig($width, $high, $holesNumber);
        $this->board = $this->boardBuilder->createBoard($this->config);
        $this->gameInProgress = true;
    }

    /**
     * @inheritDoc
     */
    public function getBoard(): BoardInterface
    {
        return $this->board;
    }

    /**
     * @inheritDoc
     */
    public function openCell(int $x, int $y): void
    {
        if (empty($this->config)) {
            throw new BoardDoesNotExistException('Cant open any cell before the game started');
        }

        if (!$this->config->isCoordinatesOnBoard($x, $y)) {
            return;
        }

        if (!$this->gameInProgress) {
            return;
        }

        $cell = $this->board->getCell($x, $y);

        if ($cell->isOpened()) {
            return;
        }

        $cell = $this->cellFactory->createOpenedCell($cell);
        $this->board = $this->boardBuilder->createBoardWithReplacedCell($this->board, $cell);

        if ($cell instanceof NumberedCellInterface) {
            if ($cell->getNumber() == 0) {
                $this->openAllAroundCell($x, $y);
            }
        }

        if ($cell instanceof HoleCellInterface) {
            $this->gameInProgress = false;
        }
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return void
     */
    private function openAllAroundCell(int $x, int $y): void
    {
        $this->openCell($x, $y + 1);
        $this->openCell($x + 1, $y + 1);
        $this->openCell($x + 1, $y);
        $this->openCell($x + 1, $y - 1);
        $this->openCell($x, $y - 1);
        $this->openCell($x - 1, $y - 1);
        $this->openCell($x - 1, $y);
        $this->openCell($x - 1, $y + 1);
    }
}
