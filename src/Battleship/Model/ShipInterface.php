<?php

namespace App\Battleship\Model;


interface ShipInterface
{

    public function getSize(): int;

    public function setIsPlaced(bool $isPlaced): self;

    public function isPlaced(): bool;
}