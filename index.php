<?php
/**
 * Created by PhpStorm.
 * User: stanislav.yordanov
 * Date: 19.4.2019 г.
 * Time: 13:20
 */

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

require __DIR__ . '/vendor/autoload.php';

//$request = Request::createFromGlobals();
//dump($request);

$request = Request::createFromGlobals();

$context = new RequestContext();
$context->fromRequest($request);

//dump($context);

$routes = new RouteCollection();
$routes->add('route_name', new Route('/test', ['_controller' => 'TestController']));

$matcher = new UrlMatcher($routes, $context);

//try {
//
//    $parameters = $matcher->matchRequest($request);
//    dump($matcher);
//    dump($parameters);
//
//} catch (Exception $e){
//    dump($e);
//}
