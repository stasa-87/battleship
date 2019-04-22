<?php
/**
 * Created by PhpStorm.
 * User: Stasa
 * Date: 21.4.2019 г.
 * Time: 19:58
 */

namespace App\Controller;


use App\Battleship\Controller\ControllerTrait;
use App\Battleship\Model\Board;
use App\Battleship\Model\HeavyShip;
use App\Battleship\Model\LightShip;

class GameController
{

    use ControllerTrait;

    public function indexAction()
    {

        $ships = [
            new HeavyShip(false),
            new LightShip(false),
            new LightShip(false)
        ];

        $board = new Board(10, 10, $ships);
        $board->init();
        dump($board);

        return $this->render('game/index.html.twig');
    }

    public function shootAction()
    {
    }

    public function cheatAction()
    {
    }

    public function resetAction()
    {
    }

}