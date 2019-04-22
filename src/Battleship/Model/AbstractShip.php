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
     * @var bool
     */
    protected $isPlaced;

    /**
     * AbstractShip constructor.
     * @param int $size
     * @param bool $isPlaced
     */
    public function __construct(int $size, bool $isPlaced)
    {
        $this->size = $size;
        $this->isPlaced = $isPlaced;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return bool
     */
    public function isPlaced(): bool
    {
        return $this->isPlaced;
    }

    public function setIsPlaced(bool $isPlaced): ShipInterface
    {
        $this->isPlaced = $isPlaced;

        return $this;
    }

}