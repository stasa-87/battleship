<?php
/**
 * Created by PhpStorm.
 * User: stanislav.yordanov
 * Date: 19.4.2019 г.
 * Time: 13:20
 */

use App\Battleship\Controller\ControllerResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

require __DIR__.'/vendor/autoload.php';

$request = Request::createFromGlobals();

$context = new RequestContext();
$context->fromRequest($request);

$routes = new RouteCollection();
$routes->add('test', new Route('/test', ['_controller' => 'App\\Controller\\TestController::indexAction']));
$routes->add('test_test', new Route('/test/test', ['_controller' => 'App\\Controller\\TestController::testAction']));
$routes->add('demo', new Route('/demo', ['_controller' => 'App\\Controller\\DemoController::indexAction']));

$matcher = new UrlMatcher($routes, $context);
$controllerResolver = new ControllerResolver();

try {

    $routerParameters = $matcher->matchRequest($request);
    $request->attributes->add($routerParameters);

    $controller = $controllerResolver->getController($request);
    $arguments = $controllerResolver->getArguments($request, $controller);

    // call controller
    $response = $controller(...$arguments);

    // view
    if (!$response instanceof Response) {
        $event = new GetResponseForControllerResultEvent($this, $request, $type, $response);
        $this->dispatcher->dispatch(KernelEvents::VIEW, $event);

        if ($event->hasResponse()) {
            $response = $event->getResponse();
        } else {
            $msg = sprintf('The controller must return a "Symfony\Component\HttpFoundation\Response" object but it returned %s.', $this->varToString($response));

            // the user may have forgotten to return something
            if (null === $response) {
                $msg .= ' Did you forget to add a return statement somewhere in your controller?';
            }

            throw new ControllerDoesNotReturnResponseException($msg, $controller, __FILE__, __LINE__ - 17);
        }
    }

} catch (ResourceNotFoundException | MethodNotAllowedException $e) {

    $response = new Response();
    $response->setContent('Page Not Found!');
    $response->setStatusCode(404);
//    dump($e);
} catch (Exception $e) {

    $response = new Response();
    $response->setContent('Internal Server Error');
    $response->setStatusCode(500);
    dump($e);
}

$response->send();