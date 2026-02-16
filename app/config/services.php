<?php

use flight\Engine;
use flight\database\PdoWrapper;
use flight\debug\database\PdoQueryCapture;
use flight\debug\tracy\TracyExtensionLoader;
use Tracy\Debugger;

/*********************************************
 *      Initialisation commune / config      *
 *********************************************/

// Fuseau horaire
date_default_timezone_set('Indian/Antananarivo');
error_reporting(E_ALL);

// Encodage interne UTF-8
if (function_exists('mb_internal_encoding')) {
    mb_internal_encoding('UTF-8');
}

// Localisation française
if (function_exists('setlocale')) {
    setlocale(LC_ALL, 'fr_FR.UTF-8');
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Récupération instance Flight (ou injection)
if (empty($app)) {
    $app = Flight::app();
}

$ds = DIRECTORY_SEPARATOR;

// Déclaration chemin classes
$app->path(__DIR__ . $ds . '..' . $ds . '..');

// Calcul BASE_URL dynamique
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($baseUrl === '\\' || $baseUrl === '.') {
    $baseUrl = '';
}
// Définit la constante BASE_URL accessible partout
define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));

$app->set('flight.base_url', $baseUrl);

// Réglages FlightPHP
$app->set('flight.case_sensitive', false);
$app->set('flight.log_errors', true);
$app->set('flight.handle_errors', false);
$app->set('flight.views.path', __DIR__ . $ds . '..' . $ds . 'views');
$app->set('flight.views.extension', '.php');
$app->set('flight.content_length', false);

// CSP nonce
$nonce = bin2hex(random_bytes(16));
$app->set('csp_nonce', $nonce);

/*********************************************
 *       Configuration utilisateur           *
 *********************************************/

if (empty($config) || !is_array($config)) {
    $configPath = __DIR__ . $ds . 'config.php';
    if (file_exists($configPath)) {
        $config = require $configPath;
    } else {
        $config = [];
    }
}

if (empty($config['app']['name'])) {
    $config['app']['name'] = 'Takalo-takalo';
}


/*********************************************
 *           Debugger Tracy Setup            *
 *********************************************/
Debugger::enable(); // auto-detect environment (dev/prod)
Debugger::$logDirectory = __DIR__ . $ds . '..' . $ds . 'log';
Debugger::$strictMode = true; // show all errors

if (!function_exists('mb_strlen')) {
    Debugger::$showBar = false;
}

if (Debugger::$showBar === true && php_sapi_name() !== 'cli') {
    (new TracyExtensionLoader($app));
}

/*********************************************
 *           Session Service Setup           *
 *********************************************/
// Exemple d'enregistrement session FlightPHP (décommenter si besoin)
/*
$app->register('session', \flight\Session::class, [
    [
        'prefix'    => 'flight_session_',
        'save_path' => sys_get_temp_dir(),
        // autres options...
    ]
]);
*/

/*********************************************
 *           Database Service Setup          *
 *********************************************/
if (!empty($config['database']['dbname'])) {
    // Forcer TCP (éviter socket unix) si host 'localhost'
    $dbHost = $config['database']['host'] === 'localhost' ? '127.0.0.1' : $config['database']['host'];
    $dbName = $config['database']['dbname'] ?? '';
    $dbCharset = $config['database']['charset'] ?? 'utf8mb4';

    $dsn = "mysql:host={$dbHost};dbname={$dbName};charset={$dbCharset}";

    $pdoClass = Debugger::$showBar === true ? PdoQueryCapture::class : PdoWrapper::class;
    $dbUser = $config['database']['user'] ?? null;
    $dbPass = $config['database']['password'] ?? null;
    $dbOptions = $config['database']['options'] ?? [];

    $trackApm = Debugger::$showBar === true;

    try {
        $app->register('db', $pdoClass, [ $dsn, $dbUser, $dbPass, $dbOptions, $trackApm ]);
    } catch (PDOException $e) {
        // Gestion simple d'erreur (tu peux logger ou afficher selon contexte)
        Debugger::log($e->getMessage(), 'database');
        die('Erreur de connexion à la base de données. Veuillez vérifier la configuration.');
    }
}

/*********************************************
 *        Retourner la config globale        *
 *********************************************/
return $config;
