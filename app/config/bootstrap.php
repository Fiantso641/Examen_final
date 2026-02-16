<?php
/*
 * Bootstrap file - initialise l'application Flight
 */

$ds = DIRECTORY_SEPARATOR;

// Autoload Composer
require(__DIR__ . $ds . '..' . $ds . '..' . $ds . 'vendor' . $ds . 'autoload.php');

// Vérification config.php
if (!file_exists(__DIR__ . $ds . 'config.php')) {
    Flight::halt(500, 'Config file not found. Please create app/config/config.php');
}

// Initialisation Flight
$app = Flight::app();

// Indique à Flight où trouver les classes (controllers, models, etc)
$app->path(__DIR__ . $ds . '..' . $ds . '..');

// Charger la configuration
$config = require 'config.php';

/*
 * ===========================
 *  Connexion Base de Données
 * ===========================
 */
try {
    $dbConfig = $config['database'];

    // DSN CORRECT
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        $dbConfig['host'],
        $dbConfig['dbname'],
        $dbConfig['charset']
    );

    // Enregistrement PDO dans Flight
    $app->register(
        'db',
        PDO::class,
        [
            $dsn,
            $dbConfig['user'],
            $dbConfig['password'],
            $dbConfig['options']
        ]
    );

} catch (PDOException $e) {
    error_log($e->getMessage());
    Flight::halt(500, 'Erreur de connexion à la base de données');
}

/*
 * ===========================
 *  Services (optionnel)
 * ===========================
 */
if (file_exists(__DIR__ . $ds . 'services.php')) {
    require 'services.php';
}

/*
 * ===========================
 *  Routes
 * ===========================
 */
$router = $app->router();
require 'routes.php';

// Lancer l'application
$app->start();
