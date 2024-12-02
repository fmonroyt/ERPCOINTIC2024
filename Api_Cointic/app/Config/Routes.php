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
 //Marcas
$routes->post('Marcas/obtenerMarcas', 'Marcas::obtenerMarcas');
$routes->post('Marcas/getMarcas', 'Marcas::getMarcas');
$routes->post('Marcas/CrearMarca', 'Marcas::CrearMarca');
$routes->post('Marcas/obtenerNumeroMarcas', 'Marcas::obtenerNumeroMarcas');
$routes->post('Marcas/obtenerMarcaxId', 'Marcas::obtenerMarcaxId');
$routes->post('Marcas/EliminarMarca', 'Marcas::EliminarMarca');
$routes->post('Marcas/ActualizarMarca', 'Marcas::ActualizarMarca');
$routes->resource('Marcas');
//Soluciones
$routes->post('Soluciones/obtenerSoluciones', 'Soluciones::obtenerSoluciones');
$routes->post('Soluciones/getSoluciones', 'Soluciones::getSoluciones');
$routes->post('Soluciones/CrearSolucion', 'Soluciones::CrearSolucion');
$routes->post('Soluciones/obtenerNumeroSoluciones', 'Soluciones::obtenerNumeroSoluciones');
$routes->post('Soluciones/obtenerSolucionxId', 'Soluciones::obtenerSolucionxId');
$routes->post('Soluciones/EliminarSolucion', 'Soluciones::EliminarSolucion');
$routes->post('Soluciones/ActualizarSolucion', 'Soluciones::ActualizarSolucion');
$routes->post('Soluciones/ObtenerMarcas', 'Soluciones::ObtenerMarcas');
$routes->post('Soluciones/ObtenerMarcasAsignadas', 'Soluciones::ObtenerMarcasAsignadas');
$routes->post('Soluciones/AsignarMarcas', 'Soluciones::AsignarMarcas');
$routes->resource('Soluciones');

//Productos
$routes->post('Productos/obtenerProductos', 'Productos::obtenerProductos');
$routes->post('Productos/obtenerNumeroProductos', 'Productos::obtenerNumeroProductos');
$routes->get('Productos/obtenerRelProductosSolucion', 'Productos::obtenerRelProductosSolucion');
$routes->post('Productos/CrearProducto', 'Productos::CrearProducto');
$routes->post('Productos/ActualizarProducto', 'Productos::ActualizarProducto');
$routes->post('Productos/EliminarProducto', 'Productos::EliminarProducto');
$routes->post('Productos/obtenerProductoxId', 'Productos::obtenerProductoxId');
$routes->post('Productos/LeerExcel', 'Productos::LeerExcel');
$routes->resource('Productos');
//SonicWall
$routes->post('Sonics/obtenerProductos', 'Sonics::obtenerProductos');
$routes->post('Sonics/obtenerNumeroProductos', 'Sonics::obtenerNumeroProductos');
$routes->get('Sonics/obtenerRelProductosSolucion', 'Sonics::obtenerRelProductosSolucion');
$routes->post('Sonics/CrearProducto', 'SopSonicshos::CrearProducto');
$routes->post('Sonics/ActualizarProducto', 'Sonics::ActualizarProducto');
$routes->post('Sonics/EliminarProducto', 'Sonics::EliminarProducto');
$routes->post('Sonics/obtenerProductoxId', 'Sonics::obtenerProductoxId');
$routes->post('Sonics/LeerExcel', 'Sonics::LeerExcel');
$routes->resource('Sonics');
//Sophos
$routes->post('Sophos/obtenerProductos', 'Sophos::obtenerProductos');
$routes->post('Sophos/obtenerNumeroProductos', 'Sophos::obtenerNumeroProductos');
$routes->get('Sophos/obtenerRelProductosSolucion', 'Sophos::obtenerRelProductosSolucion');
$routes->post('Sophos/CrearProducto', 'Sophos::CrearProducto');
$routes->post('Sophos/ActualizarProducto', 'Sophos::ActualizarProducto');
$routes->post('Sophos/EliminarProducto', 'Sophos::EliminarProducto');
$routes->post('Sophos/obtenerProductoxId', 'Sophos::obtenerProductoxId');
$routes->post('Sophos/LeerExcel', 'Sophos::LeerExcel');
$routes->resource('Sophos');

//Watchguard
$routes->post('Watchguards/obtenerProductos', 'Watchguards::obtenerProductos');
$routes->post('Watchguards/obtenerNumeroProductos', 'Watchguards::obtenerNumeroProductos');
$routes->get('Watchguards/obtenerRelProductosSolucion', 'Watchguards::obtenerRelProductosSolucion');
$routes->post('Watchguards/CrearProducto', 'Watchguards::CrearProducto');
$routes->post('Watchguards/ActualizarProducto', 'Watchguards::ActualizarProducto');
$routes->post('Watchguards/EliminarProducto', 'Watchguards::EliminarProducto');
$routes->post('Watchguards/obtenerProductoxId', 'Watchguards::obtenerProductoxId');
$routes->post('Watchguards/LeerExcel', 'Watchguards::LeerExcel');
$routes->resource('Watchguards');

//Empresa
$routes->get('EmpresaExts/obtenerIdUsuario', 'EmpresaExts::obtenerIdUsuario');
$routes->post('EmpresaExts/obtenerEmpresaExt', 'EmpresaExts::obtenerEmpresaExt');
$routes->post('EmpresaExts/obtenerNumeroEmpresaExt', 'EmpresaExts::obtenerNumeroEmpresaExt');
$routes->post('EmpresaExts/CrearEmpresaExt', 'EmpresaExts::CrearEmpresaExt');
$routes->post('EmpresaExts/ActualizarEmpresaExt', 'EmpresaExts::ActualizarEmpresaExt');
$routes->post('EmpresaExts/EliminarEmpresaExt', 'EmpresaExts::EliminarEmpresaExt');
$routes->post('EmpresaExts/obtenerEmpresasExt', 'EmpresaExts::obtenerEmpresasExt');
$routes->post('EmpresaExts/LeerExcel', 'EmpresaExts::LeerExcel');
$routes->resource('EmpresaExts');

//Contactos
$routes->get('Contactos/obtenerIdempresaext', 'Contactos::obtenerIdempresaext');
$routes->post('Contactos/obtenerContacto', 'Contactos::obtenerContacto');
$routes->post('Contactos/obtenerNumeroContacto', 'Contactos::obtenerNumeroContacto');
$routes->post('Contactos/CrearContacto', 'Contactos::CrearContacto');
$routes->post('Contactos/ActualizarContactos', 'Contactos::ActualizarContactos');
$routes->post('Contactos/EliminarContacto', 'Contactos::EliminarContacto');
$routes->post('Contactos/obtenerContactos', 'Contactos::obtenerContactos');
// $routes->post('Contactos/obtenerIdContacto', 'Contactos::obtenerIdempresaext');
$routes->post('Contactos/LeerExcel', 'Contactos::LeerExcel');
$routes->resource('Contactos');




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
