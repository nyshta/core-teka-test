<?php

namespace CoreTeka;

use CoreTeka\Board\BoardInterface;
use CoreTeka\Cell\CellInterface;
use CoreTeka\Cell\NumberedCellInterface;
use CoreTeka\Cell\HoleCellInterface;
use CoreTekaException\BoardDoesNotExistException;

interface GameInterface
{
    /**
     * Start the game with the defined board parameters
     *
     * @param int $width
     * @param int $high
     * @param int $holesNumber
     *
     * @return void
     */
    public function initiateBoard(int $width, int $high, int $holesNumber): void;

    /**
     * Open the cell
     *
     * @param int $x
     * @param int $y
     *
     * @return void
     * @throws BoardDoesNotExistException if open the cell before initiate a board
     */
    public function openCell(int $x, int $y): void;

    /**
     * Returns the board in the actual state
     *
     * @return BoardInterface
     */
    public function getBoard(): BoardInterface;

//    public function isGameInProgress(): bool;
//
//    public function haveYouWonTheGame(): bool;
}
