<?php

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