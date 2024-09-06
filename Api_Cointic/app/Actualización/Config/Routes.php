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
 $routes->setAutoRoute(True);

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
 $routes->post('Usuarios/Usuario', 'Usuarios::Usuario');
 $routes->post('Usuarios/UsuarioAngular', 'Usuarios::UsuarioAngular');
 $routes->post('Usuarios/ActualizarFotoPerfil', 'Usuarios::ActualizarFotoPerfil');
 $routes->post('Usuarios/Usuarioxid', 'Usuarios::Usuarioxid');
 $routes->post('Usuarios/ObtenerUsuarios', 'Usuarios::ObtenerUsuarios');
 $routes->post('Usuarios/ObtenerUsuariosNumeros', 'Usuarios::ObtenerUsuariosNumeros');
 $routes->post('Usuarios/CambiarStatusUsuarios', 'Usuarios::CambiarStatusUsuarios');
 $routes->get('Usuarios/CatGasolineras', 'Usuarios::CatGasolineras');
 $routes->get('Usuarios/Perfiles', 'Usuarios::Perfiles');
 $routes->resource('Usuarios');

//Perfiles
 $routes->post('Perfiles/obtenerPerfiles', 'Perfiles::obtenerPerfiles');
 $routes->post('Perfiles/obtenerNumeroPerfiles', 'Perfiles::obtenerNumeroPerfiles');
 $routes->post('Perfiles/deletePerfil', 'Perfiles::deletePerfil');
 $routes->post('Perfiles/obtenerPerfilxId', 'Perfiles::obtenerPerfilxId');
 $routes->get('Perfiles/obtenerModulos', 'Perfiles::obtenerModulos');
 $routes->resource('Perfiles');

//Permisos
$routes->post('Permisos/getPermisosUsuario', 'Permisos::getPermisosUsuario');
$routes->post('Permisos/asignarPermiso', 'Permisos::asignarPermiso');
$routes->resource('Permisos');

//Gasolineras
$routes->post('Gasolineras/obtenerGasolineras', 'Gasolineras::obtenerGasolineras');
$routes->post('Gasolineras/obtenerNumeroGasolineras', 'Gasolineras::obtenerNumeroGasolineras');
$routes->post('Gasolineras/obtenerGasolineraxId', 'Gasolineras::obtenerGasolineraxId');
$routes->post('Gasolineras/deleteGasolinera', 'Gasolineras::deleteGasolinera');
$routes->resource('Gasolineras');

//Compras
$routes->post('Compras/obtenerCompras', 'Compras::obtenerCompras');
$routes->post('Compras/obtenerNumeroCompras', 'Compras::obtenerNumeroCompras');
$routes->post('Compras/obtenerCompraxId', 'Compras::obtenerCompraxId');
$routes->post('Compras/deleteCompra', 'Compras::deleteCompra');
$routes->resource('Compras');
 //Ventas
 $routes->post('Ventas/obtenerVentas', 'Ventas::obtenerVentas');
 $routes->post('Ventas/obtenerNumeroVentas', 'Ventas::obtenerNumeroVentas');
 $routes->post('Ventas/obtenerVentaxId', 'Ventas::obtenerVentaxId');
 $routes->post('Ventas/deleteVenta', 'Ventas::deleteVenta');
 $routes->resource('Ventas');

 //Precios
 $routes->post('Precios/obtenerPrecios', 'Precios::obtenerPrecios');
 $routes->post('Precios/obtenerNumeroPrecios', 'Precios::obtenerNumeroPrecios');
 $routes->post('Precios/AltaPreciosPropiosM', 'Precios::AltaPreciosPropiosM');
 $routes->post('Precios/AltaPreciosCompetenciaM', 'Precios::AltaPreciosCompetenciaM');
 $routes->post('Precios/ActualizarPreciosPropiosM', 'Precios::ActualizarPreciosPropiosM');
 $routes->post('Precios/ActualizarPreciosCompetenciaM', 'Precios::ActualizarPreciosCompetenciaM');
 $routes->post('Precios/obtenerPrecioxConsecutivo', 'Precios::obtenerPrecioxConsecutivo');
 $routes->post('Precios/obtenerGasolinerasCompetenciaxId', 'Precios::obtenerGasolinerasCompetenciaxId');
 $routes->post('Precios/deletePrecio', 'Precios::deletePrecio');
 $routes->resource('Precios');

 //Proyecciones
 $routes->post('Proyecciones/obtenerProyecciones', 'Proyecciones::obtenerProyecciones');
 $routes->post('Proyecciones/obtenerNumeroProyecciones', 'Proyecciones::obtenerNumeroProyecciones');
 $routes->post('Proyecciones/obtenerProyeccionesxConsecutivo', 'Proyecciones::obtenerProyeccionesxConsecutivo');
 $routes->post('Proyecciones/obtenerProyeccionesxidProyeccion', 'Proyecciones::obtenerProyeccionesxidProyeccion');
 $routes->post('Proyecciones/deleteProyeccionxId', 'Proyecciones::deleteProyeccionxId');
 $routes->post('Proyecciones/deleteProyeccion', 'Proyecciones::deleteProyeccion');
 $routes->post('Proyecciones/ModificarInventario', 'Proyecciones::ModificarInventario');
 $routes->post('Proyecciones/ModificarCompra', 'Proyecciones::ModificarCompra');
 $routes->resource('Proyecciones');
 //Inventario
 $routes->post('Inventarios/obtenerTotales', 'Inventarios::obtenerTotales');
 $routes->post('Inventarios/obtenerTotalesxfecha', 'Inventarios::obtenerTotalesxfecha');
 $routes->post('Inventarios/obtenerVentaxfecha', 'Inventarios::obtenerVentaxfecha');
 $routes->post('Inventarios/ObtenerCompraxFecha', 'Inventarios::ObtenerCompraxFecha');
 $routes->resource('Inventarios');
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
