<?php

namespace CoreTeka\Cell;

interface NumberedCellInterface extends CellInterface
{
    public function getNumber(): int;
}
