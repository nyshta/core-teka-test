<?php

namespace CoreTeka;

use CoreTeka\Board\BoardBuilder;
use CoreTeka\Board\BoardFactory;
use CoreTeka\Board\BoardInterface;
use CoreTeka\Cell\CellFactory;
use CoreTeka\Cell\HoleCellInterface;
use CoreTeka\Cell\NumberedCellInterface;
use CoreTekaException\BoardDoesNotExistException;
use CoreTekaException\CellDoesNotExistsException;
use CoreTekaException\CellIsOutOfTheBoardException;
use function PHPUnit\Framework\throwException;

class Game implements GameInterface
{
    private BoardInterface $board;
    private CellFactory $cellFactory;
    private BoardFactory $boardFactory;
    private BoardBuilder $boardBuilder;
    private bool $gameInProgress = false;
    private bool $won = false;

    /**
     * @param int $width
     * @param int $high
     * @param int $holesNumber
     */
    public function __construct(CellFactory $cellFactory, BoardFactory $boardFactory, BoardBuilder $boardBuilder)
    {
        $this->cellFactory = $cellFactory;
        $this->boardFactory = $boardFactory;
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

        $this->board = $this->boardFactory->createEmptyBoard($width, $high, $holesNumber);
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

        try {
            $cell = $this->board->getCell($x, $y);
        } catch (CellDoesNotExistsException) {
            $this->gameInProgress = true;
            $this->board = $this->boardBuilder->createPopulatedBoardWithInitialPoint($this->board, $x, $y);
        }
        catch (CellIsOutOfTheBoardException) {
            return;
        }

        if (!$this->gameInProgress) {
            return;
        }

        if ($cell->isItOpened()) {
            return;
        }

        $cell = $this->cellFactory->createOpenedCell($cell);
        $this->board = $this->boardFactory->createBoardWithReplacedCell($this->board, $cell);

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

    private function openAllAroundCell($x, $y): void
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
