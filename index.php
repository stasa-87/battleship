<?php
/**
 * Created by PhpStorm.
 * User: stanislav.yordanov
 * Date: 19.4.2019 г.
 * Time: 13:20
 */

error_reporting(E_ALL);

use App\Battleship\Controller\ControllerResolver;
use App\Battleship\Controller\ControllerTrait;
use App\Battleship\Twig\RoutingExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;


require __DIR__ . '/vendor/autoload.php';

$session = new Session();
$session->start();

$request = Request::createFromGlobals();
$request->setSession($session);

$context = new RequestContext();
$context->fromRequest($request);

$routes = new RouteCollection();
$routes->add('game_index', new Route('/', ['_controller' => 'App\\Controller\\GameController::indexAction']));
$routes->add('game_shoot', new Route('/shoot', ['_controller' => 'App\\Controller\\GameController::shootAction']));
$routes->add('game_cheat', new Route('/cheat', ['_controller' => 'App\\Controller\\GameController::cheatAction']));
$routes->add('game_reset', new Route('/reset', ['_controller' => 'App\\Controller\\GameController::resetAction']));

$router = new UrlGenerator($routes, $context);
$matcher = new UrlMatcher($routes, $context);
$controllerResolver = new ControllerResolver();

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, [
    'cache' => __DIR__ . '/cache',
    'debug' => true
]);
$twig->addExtension(new DebugExtension());
$twig->addExtension(new RoutingExtension($router));

try {

    $routerParameters = $matcher->matchRequest($request);
    $request->attributes->add($routerParameters);

    $controller = $controllerResolver->getController($request);
    if(in_array(ControllerTrait::class, class_uses($controller[0]))){
        $controller[0]->setTwig($twig);
//        $controller[0]->setRouter($router);
    }

    $arguments = $controllerResolver->getArguments($request, $controller);

    // call controller
    $response = $controller(...$arguments);

    // view
    if (!$response instanceof Response) {

        $msg = sprintf('The controller must return a "Response" object but it returned %s.',
            gettype($response));
        if (null === $response) {
            $msg .= ' Did you forget to add a return statement somewhere in your controller?';
        }

        throw new LogicException($msg);
    }

} catch (ResourceNotFoundException | MethodNotAllowedException $e) {

    $response = new Response($twig->render('error/error_404.html.twig', [
        'status_text' => $e->getMessage(),
    ]));
    $response->setStatusCode(404);

} catch (Throwable $e) {

    $response = new Response($twig->render('error/error_500.html.twig', [
        'status_text' => $e->getMessage()
    ]));
    $response->setStatusCode(500);
}

$response->send();