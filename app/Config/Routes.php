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

$routes->get('/test', 'TestController::index');

$routes->get('/operateur', 'OperateurController::index');
$routes->get('/operateur/create', 'OperateurController::create');
$routes->post('/operateur/store', 'OperateurController::store');
$routes->get('/operateur/edit/(:num)', 'OperateurController::edit/$1');
$routes->post('/operateur/update/(:num)', 'OperateurController::update/$1');
$routes->post('/operateur/delete/(:num)', 'OperateurController::delete/$1');

$routes->get('/TypeOperation', 'TypeOperationController::index');
$routes->post('/TypeOperation/store', 'TypeOperationController::store');
$routes->post('/TypeOperation/update/(:num)', 'TypeOperationController::update/$1');
$routes->post('/TypeOperation/delete/(:num)', 'TypeOperationController::delete/$1');

$routes->post('/frais/store', 'FraisController::store');
$routes->post('/frais/update/(:num)', 'FraisController::update/$1');
$routes->post('/frais/delete/(:num)', 'FraisController::delete/$1');

$routes->get('/SituationGain', 'SituationGainController::index');

$routes->group('', ['filter' => 'client'], static function ($routes) {
    $routes->get('/compte', 'CompteClientController::index');
    $routes->post('/compte/depot', 'CompteClientController::depot');
    $routes->post('/compte/retrait', 'CompteClientController::retrait');
    $routes->post('/compte/transfert', 'CompteClientController::transfert');
});

// $routes->get('/', 'LoginController::index');
// $routes->post('/login/client', 'LoginController::clientConnexion');
// $routes->post('/login/connexion', 'LoginController::clientConnexion');
// $routes->get('/admin/password', 'LoginController::adminPassword');
// $routes->post('/admin/password', 'LoginController::adminPasswordCheck');
// $routes->post('/logout', 'LoginController::logout');

// $routes->group('', ['filter' => 'client'], static function ($routes) {
//     $routes->get('/accueil', 'Home::index');
//     $routes->post('/caisseSelect', 'Home::caisseSelect');
//     $routes->get('/achat', 'AchatController::index');
//     $routes->post('/achat/store', 'AchatController::store');
//     $routes->post('/achat/cloturer', 'AchatController::cloturer');
// });

// $routes->group('admin', ['filter' => 'admin'], static function ($routes) {
//     $routes->get('/', 'AdminController::index');
//     $routes->get('produits', 'AdminController::produits');
//     $routes->get('produits/create', 'AdminController::createProduit');
//     $routes->post('produits/store', 'AdminController::storeProduit');
//     $routes->get('produits/edit/(:num)', 'AdminController::editProduit/$1');
//     $routes->post('produits/update/(:num)', 'AdminController::updateProduit/$1');
//     $routes->post('produits/delete/(:num)', 'AdminController::deleteProduit/$1');
//     $routes->get('clients', 'AdminController::clients');
//     $routes->get('caisses', 'AdminController::caisses');
//     $routes->get('caisses/(:num)', 'AdminController::caisseDetails/$1');
//     $routes->get('achats', 'AdminController::achats');
// });
