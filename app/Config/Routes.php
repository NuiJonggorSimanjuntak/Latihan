<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Latihan::index');
$routes->get('/latihan/transaksi', 'Latihan::transaksi');
$routes->get('/latihan/dataUsers', 'Latihan::dataUsers', ['filter' => 'role:admin']);
$routes->get('/latihan/tambahUsers', 'Latihan::tambahUsers', ['filter' => 'role:admin']);
$routes->post('/latihan/simpanUsers', 'Latihan::simpanUsers', ['filter' => 'role:admin']);
$routes->get('/latihan/editUsers/(:num)', 'Latihan::editUsers/$1', ['filter' => 'role:admin']);
$routes->post('/latihan/ubahUsers/(:num)', 'Latihan::ubahUsers/$1', ['filter' => 'role:admin']);
$routes->delete('/latihan/hapusUsers/(:num)', 'Latihan::hapusUsers/$1', ['filter' => 'role:admin']);

$routes->get('/latihan/dataBiaya', 'Latihan::dataBiaya', ['filter' => 'role:admin']);
$routes->get('/latihan/tambahBarang', 'Latihan::tambahBarang', ['filter' => 'role:admin']);
$routes->post('/latihan/simpanBarang', 'Latihan::simpanBarang', ['filter' => 'role:admin']);
$routes->get('/latihan/editBarang/(:num)', 'Latihan::editBarang/$1', ['filter' => 'role:admin']);
$routes->post('/latihan/ubahBarang/(:num)', 'Latihan::ubahBarang/$1', ['filter' => 'role:admin']);
$routes->delete('/latihan/hapusBarang/(:num)', 'Latihan::hapusBarang/$1', ['filter' => 'role:admin']);

$routes->get('/latihan/dataTransaksi', 'Latihan::dataTransaksi');
$routes->get('/latihan/tambahTransaksi', 'Latihan::tambahTransaksi');
$routes->post('/latihan/simpanTransaksi', 'Latihan::simpanTransaksi');
$routes->get('/latihan/editTransaksi/(:num)', 'Latihan::editTransaksi/$1');
$routes->post('/latihan/ubahTransaksi/(:num)', 'Latihan::ubahTransaksi/$1');