<?php

namespace App\Battleship\Service;


use App\Battleship\Model\Board;
use App\Battleship\Model\Exception\BoardCellAlreadyShotException;
use App\Battleship\Model\Exception\InvalidBoardPositionException;
use App\Battleship\Model\HeavyShip;
use App\Battleship\Model\LightShip;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BattleshipGameService
{

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * BattleshipGameService constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @return Board
     * @throws InvalidBoardPositionException
     */
    public function startGame(): Board
    {
        if (!$this->session->has('battleship_game')) {

            $ships = [
                new HeavyShip(false),
                new LightShip(false),
                new LightShip(false),
            ];

            $board = new Board(10, 10, ...$ships);
            $board->init();

            $this->session->set('battleship_game', serialize($board));

        } else {

            $board = unserialize($this->session->get('battleship_game'));
        }

        return $board;
    }

    /**
     * @param Board $board
     * @return array
     * @throws InvalidBoardPositionException
     */
    public function getBoardView(Board $board): array
    {

        $boardView = [];
        for ($i = 0; $i <= $board->getRows(); $i++) {
            for ($j = 0; $j <= $board->getCols(); $j++) {

                if ($i == 0 && $j == 0) {

                    $cellView = '#';
                } elseif ($i == 0) {

                    $cellView = $j;
                } elseif ($j == 0) {

                    $cellView = range('A', 'Z')[$i - 1];
                } else {

                    $cellView = null;
                    if (!$board->isPositionHit($i - 1, $j - 1)) {
                        $cellView = '.';
                    } else {
                        if ($board->isPositionHit($i - 1, $j - 1) && $board->hasShipAtPosition($i - 1, $j - 1)) {
                            $cellView = 'x';
                        } else {
                            if ($board->isPositionHit($i - 1, $j - 1) && !$board->hasShipAtPosition($i - 1,
                                    $j - 1) && !$board->isCheat()) {
                                $cellView = '-';
                            } else {
                                if ($board->isPositionHit($i - 1, $j - 1) && !$board->hasShipAtPosition($i - 1,
                                        $j - 1) && $board->isCheat()) {
                                    $cellView = ' ';
                                }
                            }
                        }
                    }
                }

                $boardView[$i][$j] = $cellView;
            }
        }

        return $boardView;
    }

    /**
     * @return void
     */
    public function cheat(): void
    {

        /**
         * @var $board Board
         */
        $board = unserialize($this->session->get('battleship_game'));
        $board->shootAll();

        $this->session->set('battleship_game', serialize($board));
    }

    /**
     * @param string $position
     * @throws InvalidBoardPositionException
     * @throws BoardCellAlreadyShotException
     */
    public function shoot(string $position): void
    {
        $row = ord(strtolower($position[0])) - 97;
        $col = (int)$position[1] - 1;

        /**
         * @var $board Board
         */
        $board = unserialize($this->session->get('battleship_game'));
        $board->shootAtPosition($row, $col);

        if ($board->hasShipAtPosition($row, $col)) {

            $this->session->getFlashBag()->set('notification', '*** Sunk ***');
        } else {

            $this->session->getFlashBag()->set('notification', '*** Miss ***');
        }

        if ($board->checkWin()) {
            $winMessage = sprintf('Well done! You completed the game in %s shots.', $board->getTotalShots());
            $this->session->getFlashBag()->set('winMessage', $winMessage);
        }

        $this->session->set('battleship_game', serialize($board));
    }

    /**
     * @return void
     */
    public function reset(): void
    {
        $this->session->remove('battleship_game');
    }

}