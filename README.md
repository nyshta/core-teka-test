
#TEST task for CoreTeka

###1. Data structures:
- NxN board - see \CoreTeka\Board\BoardConfigInterface
- Location of Black Holes - see \CoreTeka\Board\BoardInterface
- Counts of # of adjacent black holes - see \CoreTeka\Cell\NumberedCellInterface
- Whether a cell is open - see \CoreTeka\Cell\CellInterface

###2. Populate data structure with K black holes randomly
Realization is here: \CoreTeka\Board\BoardBuilder::createBoard

###3. Logic that updates which cells become visible when clicked
Realization is here: \CoreTeka\Game::openCell