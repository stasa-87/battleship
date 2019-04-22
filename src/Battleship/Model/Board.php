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

    const BOARD_ROWS = 10;
    const BOARD_COLS = 10;

    /**
     * @var array
     */
    protected $board;

    /**
     * Board constructor.
     * @param array $board
     */
    public function __construct(array $board)
    {
        $this->board = $board;
    }


    public function placeShip(ShipInterface $ship)
    {

    }

    public function hasShipAtPosition()
    {

    }
}