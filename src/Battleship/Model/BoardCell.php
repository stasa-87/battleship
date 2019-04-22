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

    const BOARD_CELL_NOT_SHOT = 1;
    const BOARD_CELL_SHOT = 2;

    /**
     * @var int
     */
    protected $state;

    /**
     * @var bool
     */
    protected $hasShip;

    /**
     * BoardCell constructor.
     * @param int $state
     * @param bool $hasShip
     */
    public function __construct(int $state, bool $hasShip)
    {
        $this->state = $state;
        $this->hasShip = $hasShip;
    }

    /**
     * @return void
     */
    public function shoot(): void
    {
        $this->state = self::BOARD_CELL_SHOT;
    }

    /**
     * @return bool
     */
    public function isShot(): bool
    {
        return $this->state === self::BOARD_CELL_SHOT;
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