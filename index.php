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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

require __DIR__ . '/vendor/autoload.php';

session_start();

$request = Request::createFromGlobals();

$context = new RequestContext();
$context->fromRequest($request);

$routes = new RouteCollection();
$routes->add('game_index', new Route('/', ['_controller' => 'App\\Controller\\GameController::indexAction']));
$routes->add('game_shoot', new Route('/', ['_controller' => 'App\\Controller\\GameController::shootAction']));
$routes->add('game_cheat', new Route('/', ['_controller' => 'App\\Controller\\GameController::cheatAction']));
$routes->add('game_reset', new Route('/', ['_controller' => 'App\\Controller\\GameController::resetAction']));

$routes->add('test', new Route('/test', ['_controller' => 'App\\Controller\\TestController::indexAction']));
$routes->add('test_test', new Route('/test/test', ['_controller' => 'App\\Controller\\TestController::testAction']));
$routes->add('demo', new Route('/demo', ['_controller' => 'App\\Controller\\DemoController::indexAction']));
$routes->add('demo_game', new Route('/demo/game', ['_controller' => 'App\\Controller\\DemoController::gameAction']));
$routes->add('demo_game_post',
    new Route('/demo/post', ['_controller' => 'App\\Controller\\DemoController::gamePostAction']));
$routes->add('demo_game_reset',
    new Route('/demo/reset', ['_controller' => 'App\\Controller\\DemoController::resetAction']));

$matcher = new UrlMatcher($routes, $context);
$controllerResolver = new ControllerResolver();

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, [
    'cache' => __DIR__ . '/cache',
    'debug' => true
]);
$twig->addExtension(new DebugExtension());

try {

    $routerParameters = $matcher->matchRequest($request);
    $request->attributes->add($routerParameters);

    $controller = $controllerResolver->getController($request);
    if(in_array(ControllerTrait::class, class_uses($controller[0]))){
        $controller[0]->setTwig($twig);
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