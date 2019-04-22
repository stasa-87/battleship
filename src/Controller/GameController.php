<?php
/**
 * Created by PhpStorm.
 * User: Stasa
 * Date: 21.4.2019 г.
 * Time: 19:58
 */

namespace App\Controller;


use App\Battleship\Controller\ControllerTrait;

class GameController
{

    use ControllerTrait;

    public function indexAction()
    {

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