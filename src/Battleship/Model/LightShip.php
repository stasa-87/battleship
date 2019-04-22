<?php
/**
 * Created by PhpStorm.
 * User: Stasa
 * Date: 21.4.2019 г.
 * Time: 23:23
 */

namespace App\Battleship\Model;


class LightShip extends AbstractShip
{

    /**
     * LightShip constructor.
     */
    public function __construct()
    {
        parent::__construct(4);
    }
}