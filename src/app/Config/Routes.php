<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */
$routes->get('/', 'Species::index');
$routes->post('/species', 'Species::index');

$routes->post('/sites', 'Sites::index');

//adding in
$routes->get('/species', 'Species::index');
$routes->get('/sites', 'Sites::index');
$routes->get('/squares', 'Squares::index');
$routes->post('/squares', 'Squares::index');
$routes->get('/about', 'About::index');

// Lists of species for the county, a site and a square
$routes->add('species/(:segment)/group/(:segment)/type/(:segment)/axiophyte/(:segment)', 'Species::listForCounty/$1/$2/$3/$4');
$routes->add('site/(:segment)/group/(:segment)/type/(:segment)/axiophyte/(:segment)', 'Species::listForSite/$1/$2/$3/$4');
$routes->add('square/(:segment)/group/(:segment)/type/(:segment)/axiophyte/(:segment)', 'Species::listForSquare/$1/$2/$3/$4');
// List of sites in the county
$routes->add('sites/(:segment)', 'Sites::listForCounty/$1');


// Lists of records for the county, a site and a square
$routes->add('species/(:segment)', 'Records::singleSpeciesForCounty/$1');
$routes->add('site/(:segment)/species/(:segment)', 'Records::singleSpeciesForSite/$1/$2');
$routes->add('square/(:segment)/species/(:segment)', 'Records::singleSpeciesForSquare/$1/$2');

// A single record (= occurrence in NBN terms)
$routes->add('record/(:segment)', 'Records::singleRecord/$1');


/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
