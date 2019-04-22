<?php
/**
 * Created by PhpStorm.
 * User: Stasa
 * Date: 21.4.2019 г.
 * Time: 23:23
 */

namespace App\Battleship\Model;


class HeavyShip extends AbstractShip
{

    /**
     * HeavyShip constructor.
     * @param bool $isPlaced
     */
    public function __construct(bool $isPlaced)
    {
        parent::__construct(5, $isPlaced);
    }

}