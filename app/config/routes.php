<?php

use app\controllers\AdminAuthController;
use app\controllers\AdminCategoryController;
use app\controllers\AdminStatsController;
use app\controllers\AuthController;
use app\controllers\EchangeController;
use app\controllers\HomeController;
use app\controllers\ObjetController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/**
 * @var Router $router
 * @var Engine $app
 */

// Toutes les routes passent par ce groupe avec middleware
$router->group('', function (Router $router) use ($app) {

    // Accueil
    $router->get('/', [HomeController::class, 'index']);

    // Auth user
    $router->get('/login', [AuthController::class, 'showLogin']);
    $router->post('/login', [AuthController::class, 'login']);
    $router->get('/logout', [AuthController::class, 'logout']);
    $router->get('/register', [AuthController::class, 'showRegister']);
    $router->post('/register', [AuthController::class, 'register']);

    // Objets
    $router->get('/mes-objets', [ObjetController::class, 'mine']);
    $router->post('/mes-objets/add', [ObjetController::class, 'store']);
    $router->post('/mes-objets/update/@id:[0-9]+', [ObjetController::class, 'update']);
    $router->post('/mes-objets/delete/@id:[0-9]+', [ObjetController::class, 'delete']);

    $router->get('/objets', [ObjetController::class, 'list']);
    $router->get('/objet/@id:[0-9]+', [ObjetController::class, 'show']);

    // Echanges
    $router->get('/echanges', [EchangeController::class, 'index']);
    $router->post('/echanges/proposer', [EchangeController::class, 'proposer']);
    $router->post('/echanges/accepter/@id:[0-9]+', [EchangeController::class, 'accepter']);
    $router->post('/echanges/refuser/@id:[0-9]+', [EchangeController::class, 'refuser']);

    // Admin
    $router->get('/admin/login', [AdminAuthController::class, 'showLogin']);
    $router->post('/admin/login', [AdminAuthController::class, 'login']);
    $router->get('/admin/logout', [AdminAuthController::class, 'logout']);

    $router->get('/admin', [AdminStatsController::class, 'dashboard']);
    $router->get('/admin/categories', [AdminCategoryController::class, 'index']);
    $router->post('/admin/categories/add', [AdminCategoryController::class, 'store']);
    $router->post('/admin/categories/update/@id:[0-9]+', [AdminCategoryController::class, 'update']);
    $router->post('/admin/categories/delete/@id:[0-9]+', [AdminCategoryController::class, 'delete']);

}, [SecurityHeadersMiddleware::class]);
