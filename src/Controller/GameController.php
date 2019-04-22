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
use Symfony\Component\HttpFoundation\Request;

class GameController
{

    use ControllerTrait;

    public function indexAction(Request $request)
    {

        if (!$request->getSession()->has('battleship_game')) {

            $ships = [
                new HeavyShip(false),
                new LightShip(false),
                new LightShip(false)
            ];

            $board = new Board(10, 10, $ships);
            $board->init();

            $request->getSession()->set('battleship_game', serialize($board));

        } else {

            $board = unserialize($request->getSession()->get('battleship_game'));
        }


//        $board->shootAll();
//        $board->shootAtPosition(0,0);
//        $board->shootAtPosition(1,1);
//        $board->shootAtPosition(1,1);
        dump($board);



        return $this->render('game/index.html.twig');
    }

    public function shootAction(Request $request)
    {
        $position = $request->request->getAlnum('position');

        //regular expression check for one letter and one number

        $row = ord(strtolower($position[0])) - 97;
        $col = (int) $position[1] - 1;

        /**
         * @var $board Board
         */
        $board = unserialize($request->getSession()->get('battleship_game'));
        $board->shootAtPosition($row, $col);

        $request->getSession()->set('battleship_game', serialize($board));

        return $this->redirectToRoute('game_index');
    }

    public function cheatAction(Request $request)
    {
        /**
         * @var $board Board
         */
        $board = unserialize($request->getSession()->get('battleship_game'));
        $board->shootAll();
        $request->getSession()->set('battleship_game', serialize($board));

        return $this->redirectToRoute('game_index');
    }

    public function resetAction(Request $request)
    {
        $request->getSession()->remove('battleship_game');
        return $this->redirectToRoute('game_index');
    }

}