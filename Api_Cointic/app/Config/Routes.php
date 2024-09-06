<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
 $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
 $routes->get('/', 'Home::index');
 //Usuarios
 $routes->post('Usuarios/Autenticar', 'Usuarios::Autenticar');
 $routes->post('Usuarios/Usuario', 'Usuarios::Usuario');
 $routes->post('Usuarios/update', 'Usuarios::update');
 $routes->post('Usuarios/Usuario', 'Usuarios::Usuario');
 $routes->post('Usuarios/UsuarioAngular', 'Usuarios::UsuarioAngular');
 $routes->post('Usuarios/ActualizarFotoPerfil', 'Usuarios::ActualizarFotoPerfil');
 $routes->post('Usuarios/getUsuarioxid', 'Usuarios::getUsuarioxid');
 $routes->post('Usuarios/getUsuarios', 'Usuarios::ObtenerUsuarios');
 $routes->post('Usuarios/getUsuariosNumeros', 'Usuarios::ObtenerUsuariosNumeros');
 $routes->post('Usuarios/cambiarStatusUsuarios', 'Usuarios::cambiarStatusUsuarios');
 $routes->get('Usuarios/getPerfiles', 'Usuarios::getPerfiles');
 $routes->get('Usuarios/getDepartamentos', 'Usuarios::getDepartamentos');
 $routes->post('Usuarios/crearusuario', 'Usuarios::crearusuario');
 $routes->post('Usuarios/actualizarUsuario', 'Usuarios::actualizarUsuario');
 $routes->post('Usuarios/editarusuario', 'Usuarios::editarusuario');
 $routes->resource('Usuarios');

//Clientes
$routes->post('Clientes/obtenerClientes', 'Clientes::obtenerClientes');
$routes->post('Clientes/obtenerNumeroClientes', 'Clientes::obtenerNumeroClientes');
$routes->post('Clientes/obtenerClientexId', 'Clientes::obtenerClientexId');
$routes->post('Clientes/cambiarEstatus', 'Clientes::cambiarEstatus');
$routes->post('Clientes/actualizarCliente', 'Clientes::actualizarCliente');
$routes->post('Clientes/deleteClientes', 'Clientes::deleteClientes');
$routes->post('Clientes/crearcliente', 'Clientes::crearcliente');
$routes->post('Clientes/obtenerpaises', 'Clientes::obtenerpaises');
$routes->post('Clientes/aceptarFlujo', 'Clientes::aceptarFlujo');
$routes->resource('Clientes');

 //Ventas
 $routes->post('Permisos/getPermisosUsuario', 'Permisos::getPermisosUsuario');
 $routes->post('Permisos/asignarPermiso', 'Permisos::asignarPermiso');
 $routes->resource('Permisos');


//perfi
 $routes->post('Perfiles/obtenerPerfiles', 'Perfiles::obtenerPerfiles');
 $routes->post('Perfiles/CrearPerfil', 'Perfiles::CrearPerfil');
 $routes->post('Perfiles/ActualizarPerfil', 'Perfiles::ActualizarPerfil');
 $routes->post('Perfiles/obtenerNumeroPerfiles', 'Perfiles::obtenerNumeroPerfiles');
 $routes->post('Perfiles/EliminarPerfil', 'Perfiles::EliminarPerfil');
 $routes->post('Perfiles/obtenerPerfilxId', 'Perfiles::obtenerPerfilxId');
 $routes->get('Perfiles/obtenerModulos', 'Perfiles::obtenerModulos');
 $routes->resource('Perfiles');
 
 //Departamentos
$routes->post('Departamentos/obtenerDepartamentos', 'Departamentos::obtenerDepartamentos');
$routes->post('Departamentos/getDepartamentos', 'Departamentos::getDepartamentos');
$routes->post('Departamentos/CrearDepartamento', 'Departamentos::CrearDepartamento');
$routes->post('Departamentos/obtenerNumeroDepartamentos', 'Departamentos::obtenerNumeroDepartamentos');
$routes->post('Departamentos/obtenerDepartamentoxId', 'Departamentos::obtenerDepartamentoxId');
$routes->post('Departamentos/EliminarDepartamento', 'Departamentos::EliminarDepartamento');
$routes->post('Departamentos/ActualizarDepartamento', 'Departamentos::ActualizarDepartamento');
$routes->resource('Departamentos');
 
 
 
 
 
 
 /*



 
 
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
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
