<?php
/**********************************************
 *      FlightPHP Skeleton Sample Config      *
 **********************************************/

// Fuseau horaire
date_default_timezone_set('Indian/Antananarivo');
error_reporting(E_ALL);

// Encodage interne
if (function_exists('mb_internal_encoding')) {
    mb_internal_encoding('UTF-8');
}

// Localisation
if (function_exists('setlocale')) {
    setlocale(LC_ALL, 'fr_FR.UTF-8');
}

// Récupération de l’instance Flight (si pas déjà initialisée)
if (empty($app)) {
    $app = Flight::app();
}

$ds = DIRECTORY_SEPARATOR;

// Déclaration du chemin des classes
$app->path(__DIR__ . $ds . '..' . $ds . '..');

// Calcul dynamique du sous-répertoire (BASE_URL)
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($baseUrl === '\\' || $baseUrl === '.') {
    $baseUrl = '';
}

// Synchronisation avec Flight (pour utilisation interne)
$app->set('flight.base_url', $baseUrl);

// Réglages supplémentaires FlightPHP
$app->set('flight.case_sensitive', false);
$app->set('flight.log_errors', true);
$app->set('flight.handle_errors', false);
$app->set('flight.views.path', __DIR__ . $ds . '..' . $ds . 'views');
$app->set('flight.views.extension', '.php');
$app->set('flight.content_length', false);

// CSP nonce aléatoire
$nonce = bin2hex(random_bytes(16));
$app->set('csp_nonce', $nonce);

// Retour de la configuration utilisateur (base de données etc.)
return [
    'database' => [
        'host'     => 'localhost',
        'dbname'   => 'takalo_db',
        'user'     => 'root',
        'password' => '',
        'charset'  => 'utf8mb4',
        'options'  => [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ],
    ],

    'app' => [
        'name'    => 'Takalo-takalo',
        'version' => '1.0.0',
    ],
];
