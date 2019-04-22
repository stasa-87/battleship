<?php
/**
 * Created by PhpStorm.
 * User: Stasa
 * Date: 21.4.2019 г.
 * Time: 23:20
 */

namespace App\Battleship\Model;


abstract class AbstractShip implements ShipInterface
{

    /**
     * @var int
     */
    protected $size;

    /**
     * AbstractShip constructor.
     * @param int $size
     */
    public function __construct(int $size)
    {
        $this->size = $size;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

}