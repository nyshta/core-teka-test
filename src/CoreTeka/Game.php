<?php

namespace CoreTeka;

use CoreTeka\Board\BoardBuilder;
use CoreTeka\Board\BoardConfigInterface;
use CoreTeka\Board\BoardInterface;
use CoreTeka\Cell\CellFactory;
use CoreTeka\Cell\HoleCellInterface;
use CoreTeka\Cell\NumberedCellInterface;
use CoreTeka\Exception\BoardDoesNotExistException;
use CoreTeka\Exception\CellDoesNotExistsException;
use function PHPUnit\Framework\throwException;

class Game implements GameInterface
{
    private BoardConfigInterface $config;
    private BoardInterface $board;
    private CellFactory $cellFactory;
    private BoardBuilder $boardBuilder;
    private bool $gameInProgress = false;
//    private bool $won = false;

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
        $holesNumber = $holesNumber >= $width * $high
            ? $width * $high - 9  // at least first click on board should be ok
            : $holesNumber;

        $this->config = $this->boardBuilder->createBoardConfig($width, $high, $holesNumber);
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
        if (empty($this->board)) {
            throwException(new BoardDoesNotExistException('Cant open any cell before the game started'));
        }

        if (!$this->config->isCoordinatesOnBoard($x, $y)) {
            return;
        }

        try {
            $cell = $this->board->getCell($x, $y);
        } catch (CellDoesNotExistsException) {
            $this->gameInProgress = true;
            $this->board = $this->boardBuilder->createBoardWithInitialPoint($this->config, $x, $y);
        }

        if (!$this->gameInProgress) {
            return;
        }

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

        //todo check the game been won
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
