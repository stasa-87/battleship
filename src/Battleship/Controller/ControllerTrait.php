<?php
/**
 * Created by PhpStorm.
 * User: Stasa
 * Date: 21.4.2019 г.
 * Time: 22:10
 */

namespace App\Battleship\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Trait ControllerTrait
 *
 * Common controllers features.
 */
trait ControllerTrait
{

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @param Environment $twig
     * @return ControllerTrait
     */
    public function setTwig(Environment $twig): self
    {
        $this->twig = $twig;
        return $this;
    }

    /**
     * Renders a view.
     *
     * @param string $view
     * @param array $parameters
     * @param Response|null $response
     * @return Response
     */
    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        if ($this->twig) {
            $content = $this->twig->render($view, $parameters);
        } else {
            throw new \LogicException('You can not use the "render" method if Twig is not available.');
        }

        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($content);

        return $response;
    }
}