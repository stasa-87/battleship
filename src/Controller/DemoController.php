<?php
/**
 * Created by PhpStorm.
 * User: stanislav.yordanov
 * Date: 19.4.2019 г.
 * Time: 15:07
 */

namespace App\Controller;


use App\Battleship\Controller\ControllerTrait;
use App\Battleship\Game\BattleshipGame;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class DemoController
{

    use ControllerTrait;

    public function indexAction()
    {

        return $this->render('index.html.twig', ['the' => 'variables', 'go' => 'here']);
//        return new Response($this->twig->render('index.html.twig', ['the' => 'variables', 'go' => 'here']));
    }

    public function gameAction()
    {

        $game = $this->getGame();
        dump($game);
        return new Response($this->twig->render('game.html.twig', [
            'game' => $game->generateGameLayout()
        ]));
    }

    private function getGame()
    {
        if (!isset($_SESSION['layout'])) {
            // Start new game
            $game = BattleshipGame::initCustomGame(10, 10);
            $_SESSION['layout'] = $game->getBoardLayout();
            $_SESSION['shots'] = 0; // Shots fired counter
        } else {
            $game = BattleshipGame::resumeGame($_SESSION['layout']);
        }

        return $game;
    }

    public function gamePostAction()
    {

        $position = $_REQUEST['pos'];
        $game = $this->getGame();

        $message = '';
        $messageWin = '';
        if ($position === 'xx') {
            $layout = $game->generateRevealedGameLayout();
        } else {
            $message = $game->registerShot($position);
            $_SESSION['shots']++; // Increase number of shots fired

            if ($game->allShipsSunk()) {
                $messageWin = 'Well done! You completed the game in ' . $_SESSION['shots'] . ' shots.';
            }

            $layout = $game->generateGameLayout();
            // Board layout changed so we must save it
            $_SESSION['layout'] = $game->getBoardLayout();
        }

        return new Response($this->twig->render('game.html.twig', [
            'game' => $layout,
            'message' => $message,
            'messageWin' => $messageWin,
        ]));
    }

    public function resetAction()
    {
        unset($_SESSION['layout']);
        return $this->gameAction();
    }

}