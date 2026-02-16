<?php

use app\controllers\BesoinController;
use app\controllers\DashboardController;
use app\controllers\DispatchController;
use app\controllers\DonController;
use app\controllers\VilleController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/**
 * @var Router $router
 * @var Engine $app
 */

// Toutes les routes passent par ce groupe avec middleware
$router->group('', function (Router $router) use ($app) {

    // Tableau de bord
    $router->get('/', [DashboardController::class, 'index']);
    $router->get('/dashboard', [DashboardController::class, 'index']);

    // Villes
    $router->get('/villes', [VilleController::class, 'index']);
    $router->post('/villes/add', [VilleController::class, 'store']);
    $router->post('/villes/update/@id:[0-9]+', [VilleController::class, 'update']);
    $router->post('/villes/delete/@id:[0-9]+', [VilleController::class, 'delete']);

    // Besoins
    $router->get('/besoins', [BesoinController::class, 'index']);
    $router->post('/besoins/add', [BesoinController::class, 'store']);
    $router->post('/besoins/delete/@id:[0-9]+', [BesoinController::class, 'delete']);

    // Dons
    $router->get('/dons', [DonController::class, 'index']);
    $router->post('/dons/add', [DonController::class, 'store']);
    $router->post('/dons/delete/@id:[0-9]+', [DonController::class, 'delete']);

    // Simulation dispatch
    $router->post('/dispatch/simuler', [DispatchController::class, 'simuler']);

}, [SecurityHeadersMiddleware::class]);
