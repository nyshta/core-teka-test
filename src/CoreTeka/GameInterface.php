<?php

namespace CoreTeka;

use CoreTeka\Board\BoardInterface;
use CoreTeka\Exception\BoardDoesNotExistException;

interface GameInterface
{
    /**
     * Start the game with the defined board parameters
     *
     * @param int $width
     * @param int $high
     * @param int $holesNumber
     *
     * @throws \CoreTeka\Exception\TooMuchHolesException
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
}
