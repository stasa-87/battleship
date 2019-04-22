<?php
/**
 * Created by PhpStorm.
 * User: Stasa
 * Date: 21.4.2019 г.
 * Time: 23:29
 */

namespace App\Battleship\Model;


use App\Battleship\Model\Exception\BoardCellAlreadyShotException;
use LogicException;

class Board
{

    const BOARD_ORIENTATION_HORIZONTAL = 1;

    const BOARD_ORIENTATION_VERTICAL = 2;

    const BOAR_ORIENTATION_LIST = [self::BOARD_ORIENTATION_HORIZONTAL, self::BOARD_ORIENTATION_VERTICAL];

    /**
     * @var int
     */
    protected $rows;

    /**
     * @var int
     */
    protected $cols;

    /**
     * @var array
     */
    private $board = [];

    /**
     * @var array
     */
    private $ships = [];

    /**
     * Board constructor.
     * @param int $rows
     * @param int $cols
     * @param array $ships
     */
    public function __construct(int $rows, int $cols, $ships)
    {
        $this->rows = $rows;
        $this->cols = $cols;
        $this->ships = $ships;
    }

    /**
     * Create the board and place the ships
     */
    public function init()
    {

        for ($i = 0; $i <= $this->rows - 1; $i++) {
            for ($j = 0; $j <= $this->cols - 1; $j++) {

                $this->board[$i][$j] = new BoardCell(BoardCell::BOARD_CELL_NOT_SHOT, false);
            }
        }

        $this->placeShipsOnBoard();

    }

    protected function placeShipsOnBoard()
    {

        while ($ships = $this->getNotPlacedShips()) {

            $position = [
                'row' => rand(0, $this->rows - 1),
                'col' => rand(0, $this->cols - 1),
                'orientation' => self::BOAR_ORIENTATION_LIST[rand(0, 0)],
            ];

            $ship = $ships[0];

            $emptyBoardCells = [];
            if ($position['orientation'] == self::BOARD_ORIENTATION_HORIZONTAL) {

                //check if we can place the ship on the board
                if (($position['col'] + $ship->getSize()) > $this->cols) {
                    continue;
                }

                //check if there are already placed ships
                foreach (range($position['col'], $position['col'] + $ship->getSize() - 1) as $col) {

                    if ($this->hasShipAtPosition($position['row'], $col)) {
                        continue 2;
                    }

                    $emptyBoardCells[] = $this->getBoardCellAtPosition($position['row'], $col);
                }

            } elseif ($position['orientation'] == self::BOARD_ORIENTATION_VERTICAL) {

                //check if we can place the ship on the board
                if (($position['row'] + $ship->getSize()) > $this->rows) {
                    continue;
                }

                //check if there are already placed ships
                foreach (range($position['row'], $position['row'] + $ship->getSize() - 1) as $row) {

                    if ($this->hasShipAtPosition($row, $position['col'])) {
                        continue 2;
                    }

                    $emptyBoardCells[] = $this->getBoardCellAtPosition($row, $position['col']);
                }

            } else {

                $msg = sprintf('The available board orientations are %s. But you have provided "%s".',
                    join(' and ', self::BOAR_ORIENTATION_LIST), $position['orientation']);

                throw new LogicException($msg);
            }


            /**
             * @var $boardCell BoardCell
             */
            foreach ($emptyBoardCells as $boardCell) {

                $boardCell->placeShip();
            }

            $ship->setIsPlaced(true);
        }
    }

    /**
     * @return ShipInterface[]
     */
    protected function getNotPlacedShips(): array
    {
        return array_values(array_filter($this->ships, function (ShipInterface $ship) {
            return $ship->isPlaced() === false;
        }));
    }

    /**
     * @param int $row
     * @param int $col
     * @return bool
     */
    protected function hasShipAtPosition(int $row, int $col): bool
    {
        //check board rows and cols
        return $this->getBoardCellAtPosition($row, $col)->hasShip();
    }

    /**
     * @param int $row
     * @param int $col
     * @return BoardCell
     */
    protected function getBoardCellAtPosition(int $row, int $col): BoardCell
    {
        //check board rows and cols
        return $this->board[$row][$col];
    }

    public function load()
    {

        return true;
    }

    /**
     * @param int $row
     * @param int $col
     * @throws BoardCellAlreadyShotException
     */
    public function shootAtPosition(int $row, int $col): void
    {
        //check board rows and cols
        $boardCell = $this->getBoardCellAtPosition($row, $col);

        if($boardCell->isShot()){
            throw new BoardCellAlreadyShotException();
        }

        $boardCell->shoot();
    }

}