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
use App\Battleship\Model\Exception\BoardCellAlreadyShotException;
use App\Battleship\Model\Exception\InvalidBoardPositionException;
use App\Battleship\Model\Exception\WrongCoordinatesFormatException;
use App\Battleship\Model\HeavyShip;
use App\Battleship\Model\LightShip;
use Symfony\Component\HttpFoundation\Request;

class GameController
{

    use ControllerTrait;

    public function indexAction(Request $request)
    {

        try {

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

        } catch (InvalidBoardPositionException $e) {

            $request->getSession()->getFlashBag()->add('errors', 'This field does not exist, please choose one from the board!');
        }

        return $this->render('game/index.html.twig', [
            'board' => $board,
            'errors' => $request->getSession()->getFlashBag()->get('errors'),
            'notification' => $request->getSession()->getFlashBag()->get('notification'),
            'winMessage' => $request->getSession()->getFlashBag()->get('winMessage')
        ]);
    }

    public function shootAction(Request $request)
    {
        $position = $request->request->getAlnum('position');

        try {

            if(! preg_match("/^[a-zA-Z][1-9]$/", $position)){
                throw new WrongCoordinatesFormatException();
            }

            $row = ord(strtolower($position[0])) - 97;
            $col = (int) $position[1] - 1;

            /**
             * @var $board Board
             */
            $board = unserialize($request->getSession()->get('battleship_game'));
            $board->shootAtPosition($row, $col);

            if ($board->hasShipAtPosition($row, $col)) {

                $request->getSession()->getFlashBag()->set('notification', '*** Sunk ***');
            } else {

                $request->getSession()->getFlashBag()->set('notification', '*** Miss ***');
            }

            if($board->checkWin()){
                $winMessage = sprintf('Well done! You completed the game in %s shots.', $board->getTotalShots());
                $request->getSession()->getFlashBag()->set('winMessage', $winMessage);
            }

            $request->getSession()->set('battleship_game', serialize($board));

        } catch (BoardCellAlreadyShotException $e) {

            $request->getSession()->getFlashBag()->add('errors', 'You have already shot at this field!');

        } catch (InvalidBoardPositionException $e) {

            $request->getSession()->getFlashBag()->add('errors', 'This field does not exist, please choose one from the board!');

        } catch (WrongCoordinatesFormatException $e) {

            $request->getSession()->getFlashBag()->add('errors', 'Wrong coordinates format!');
        }

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