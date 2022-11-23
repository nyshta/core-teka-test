<?php

namespace CoreTeka;

use CoreTeka\Cell\CellFactory;

class Board implements BoardInterface
{
    private int $width = 0;
    private int $high = 0;
    private int $holesNumber = 0;
    private CellFactory $cellFactory;
    private bool $isGameInProgress = false;
    private array $cells = [];

    /**
     * @param int $width
     * @param int $high
     * @param int $holesNumber
     */
    public function __construct(int $width, int $high, int $holesNumber, CellFactory $cellFactory)
    {
        $this->width = $width;
        $this->high = $high;
        $this->holesNumber = $holesNumber;
        $this->cellFactory = $cellFactory;
    }

    public function get_all_cells(): array
    {
        return $this->cells;
    }

    public function open(int $x, int $y): ?array
    {
        // first click : populate, start game??
        // clickCell:
        //check is it on board
        //if the cell is already open: do nothing, return null
        //check is it a hole: return the hole end game
        //mark the cell as open
        $openedCells = [];

        //if it is 0 - recursively open all around: add them to the openedCells
        return array_merge($this->cells[$x][$y], $openedCells);
    }

    private function checkForTheFirstClick(int $x, int $y): void
    {
        if (!empty($this->cells)) {
            return;
        }

        $this->isGameInProgress = true;
        $this->populate($x,$y);
    }

    private function populate(int $x, int $y): void
    {
        $this->cells[$x][$y] = $this->cellFactory->createEmptyCell();
        //todo
    }
}
