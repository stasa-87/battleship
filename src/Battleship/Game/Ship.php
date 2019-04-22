<?php
/**
 * Created by PhpStorm.
 * User: Stasa
 * Date: 19.4.2019 г.
 * Time: 21:32
 */

namespace App\Battleship\Game;


class Ship
{
    protected $length;
    protected $type;

    /**
     * @return int Length of the ship
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}