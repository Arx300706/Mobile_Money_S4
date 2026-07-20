<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// $routes->get('/seed', 'SeedController::index');
$routes->get('/', 'LoginController::index');
$routes->post('/login/client', 'LoginController::clientConnexion');
$routes->post('/login/connexion', 'LoginController::clientConnexion');
$routes->get('/admin/password', 'LoginController::adminPassword');
$routes->post('/admin/password', 'LoginController::adminPasswordCheck');
$routes->post('/logout', 'LoginController::logout');

$routes->group('', ['filter' => 'admin'], static function ($routes) {
    $routes->get('/test', 'TestController::index');

    $routes->get('/operateur', 'OperateurController::index');
    $routes->get('/operateur/create', 'OperateurController::create');
    $routes->post('/operateur/store', 'OperateurController::store');
    $routes->get('/operateur/edit/(:num)', 'OperateurController::edit/$1');
    $routes->post('/operateur/update/(:num)', 'OperateurController::update/$1');
    $routes->get('/operateur/delete/(:num)', 'OperateurController::delete/$1');
    $routes->post('/operateur/delete/(:num)', 'OperateurController::delete/$1');

    $routes->get('/TypeOperation', 'TypeOperationController::index');
    $routes->get('/TypeOperation/create', 'TypeOperationController::create');
    $routes->post('/TypeOperation/store', 'TypeOperationController::store');
    $routes->post('/TypeOperation/update/(:num)', 'TypeOperationController::update/$1');
    $routes->post('/TypeOperation/delete/(:num)', 'TypeOperationController::delete/$1');

    $routes->get('/frais/store', 'FraisController::create');
    $routes->post('/frais/store', 'FraisController::store');
    $routes->post('/frais/update/(:num)', 'FraisController::update/$1');
    $routes->post('/frais/delete/(:num)', 'FraisController::delete/$1');

    $routes->get('/SituationGain', 'SituationGainController::index');
    $routes->get('/SituationClient', 'SituationClientController::index');
});

$routes->group('', ['filter' => 'client'], static function ($routes) {
    $routes->get('/compte', 'CompteClientController::index');
    $routes->post('/compte/depot', 'CompteClientController::depot');
    $routes->post('/compte/retrait', 'CompteClientController::retrait');
    $routes->post('/compte/transfert', 'CompteClientController::transfert');
});
