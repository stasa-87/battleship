<?php
/**
 * Created by PhpStorm.
 * User: Stasa
 * Date: 21.4.2019 г.
 * Time: 22:10
 */

namespace App\Battleship\Controller;

use LogicException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Twig\Environment;

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
     * @var UrlGenerator
     */
    private $router;

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
     * @param UrlGenerator $router
     * @return ControllerTrait
     */
    public function setRouter(UrlGenerator $router): self
    {
        $this->router = $router;
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
            throw new LogicException('You can not use the "render" method if Twig is not available.');
        }

        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($content);

        return $response;
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @param string $route
     * @param array $parameters
     * @param int $referenceType
     * @return string
     */
    protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        if(! $this->router){
            throw new LogicException('You can not use the "generateUrl" method if Router is not available.');
        }
        return $this->router->generate($route, $parameters, $referenceType);
    }

    /**
     * Returns a RedirectResponse to the given URL.
     *
     * @param string $url
     * @param int $status
     * @return RedirectResponse
     */
    protected function redirect(string $url, int $status = 302): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * Returns a RedirectResponse to the given route with the given parameters.
     *
     * @param string $route
     * @param array $parameters
     * @param int $status
     * @return RedirectResponse
     */
    protected function redirectToRoute(string $route, array $parameters = [], int $status = 302): RedirectResponse
    {
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }
}