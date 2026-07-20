<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// $routes->get('/seed', 'SeedController::index');
$routes->get('/test', 'TestController::index');

$routes->get('/operateur', 'OperateurController::index');
$routes->get('/operateur/create', 'OperateurController::create');
$routes->get('/operateur/edit', 'OperateurController::edit');

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
