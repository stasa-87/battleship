<?php

namespace App\Battleship\Model;


class LightShip extends AbstractShip
{

    /**
     * LightShip constructor.
     * @param bool $isPlaced
     */
    public function __construct(bool $isPlaced)
    {
        parent::__construct(4, $isPlaced);
    }

}