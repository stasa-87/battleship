<?php
/**
 * Created by PhpStorm.
 * User: Stasa
 * Date: 22.4.2019 г.
 * Time: 1:16
 */

namespace App\Battleship\Model;


class BoardCell
{

    /**
     * @var bool
     */
    protected $isShot;

    /**
     * @var bool
     */
    protected $hasShip;

    /**
     * BoardCell constructor.
     *
     * @param bool $isShot
     * @param bool $hasShip
     */
    public function __construct(bool $isShot, bool $hasShip)
    {
        $this->isShot = $isShot;
        $this->hasShip = $hasShip;
    }

    /**
     * @return void
     */
    public function shoot(): void
    {
        $this->isShot = true;
    }

    /**
     * @return bool
     */
    public function isShot(): bool
    {
        return $this->isShot;
    }

    /**
     * @return void
     */
    public function placeShip(): void
    {
        $this->hasShip = true;
    }

    /**
     * @return bool
     */
    public function hasShip(): bool
    {
        return $this->hasShip;
    }

}