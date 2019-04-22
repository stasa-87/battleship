<?php
/**
 * Created by PhpStorm.
 * User: Stasa
 * Date: 21.4.2019 г.
 * Time: 23:29
 */

namespace App\Battleship\Model;


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

        while ($this->getNotPlacedShips()) {

            $position = [
                'row' => rand(0, $this->rows - 1),
                'col' => rand(0, $this->cols - 1),
                'orientation' => self::BOAR_ORIENTATION_LIST[rand(0, 1)],
            ];

            /**
             * @var $ship ShipInterface
             */
            $ship = $this->getNotPlacedShips()[0];
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

    protected function placeShip()
    {
    }
    
    public function load()
    {
    }

    protected function hasShipAtPositions()
    {
    }

}