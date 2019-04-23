<?php

namespace App\Controller;


use App\Battleship\Controller\ControllerTrait;
use App\Battleship\Model\Exception\BoardCellAlreadyShotException;
use App\Battleship\Model\Exception\InvalidBoardPositionException;
use App\Battleship\Model\Exception\WrongCoordinatesFormatException;
use App\Battleship\Service\BattleshipGameService;
use Symfony\Component\HttpFoundation\Request;

class GameController
{

    use ControllerTrait;

    public function indexAction(Request $request, BattleshipGameService $battleshipGame)
    {

        try {

            $board = $battleshipGame->startGame();

            $boardView = $battleshipGame->getBoardView($board);

        } catch (InvalidBoardPositionException $e) {

            $request->getSession()->getFlashBag()->add('errors', 'This field does not exist, please choose one from the board!');
        }

        return $this->render('game/index.html.twig', [
            'boardView' => $boardView ?? null,
            'errors' => $request->getSession()->getFlashBag()->get('errors'),
            'notification' => $request->getSession()->getFlashBag()->get('notification'),
            'winMessage' => $request->getSession()->getFlashBag()->get('winMessage')
        ]);
    }

    public function shootAction(Request $request, BattleshipGameService $battleshipGame)
    {
        $position = $request->request->getAlnum('position');

        try {

            if(! preg_match("/^[a-zA-Z][1-9]$/", $position)){
                throw new WrongCoordinatesFormatException();
            }

            $battleshipGame->shoot($position);

        } catch (BoardCellAlreadyShotException $e) {

            $request->getSession()->getFlashBag()->add('errors', 'You have already shot at this field!');

        } catch (InvalidBoardPositionException $e) {

            $request->getSession()->getFlashBag()->add('errors', 'This field does not exist, please choose one from the board!');

        } catch (WrongCoordinatesFormatException $e) {

            $request->getSession()->getFlashBag()->add('errors', 'Wrong coordinates format!');
        }

        return $this->redirectToRoute('game_index');
    }

    public function cheatAction(BattleshipGameService $battleshipGame)
    {

        $battleshipGame->cheat();

        return $this->redirectToRoute('game_index');
    }

    public function resetAction(BattleshipGameService $battleshipGame)
    {

        $battleshipGame->reset();
        
        return $this->redirectToRoute('game_index');
    }

}