<?php
/**
 * Created by PhpStorm.
 * User: Stasa
 * Date: 21.4.2019 г.
 * Time: 23:30
 */

namespace App\Battleship\Model;


interface ShipInterface
{

    public function getSize(): int;

    public function setIsPlaced(bool $isPlaced): self;

    public function isPlaced(): bool;
}