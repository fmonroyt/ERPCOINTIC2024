<?php
namespace App\Models;
use CodeIgniter\Model;

class Venta extends Model
{
    function getVentas($page, $pageSize,$idCliente)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('prodtransaccion');
        $builder->select("prodtransaccion.idTransaccion,
		prodtransaccion.estatusCompra,
		prodtransaccion.fecha as fechaCompra,
		prodrentacaja.fechaInicio as inicioRenta,
		prodrentacaja.fechaFinal as finalRenta,
		prodtransaccion.archivoTransferencia");
		$builder->join("prodrentacaja", "prodrentacaja.idTransaccion=prodtransaccion.idTransaccion","LEFT");
		$builder->where("prodtransaccion.idCliente", $idCliente);
        $builder->orderBy('prodtransaccion.idTransaccion', 'asc');
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroVentas($page, $pageSize,$idCliente)
	{

        $db      = \Config\Database::connect();
		$builder = $db->table('prodtransaccion');
		$builder->select("*");
		$builder->where("prodtransaccion.idCliente", $idCliente);
		return $builder->get()->getNumRows();
	}

	function getProductos($page, $pageSize,$idTransaccion)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('prodcompras');
        $builder->select("prodcompras.idCompra,
		prodcompras.idTransaccion,
		prodcompras.idProducto,
		catproductos.nombreProducto,
		prodcompras.cantidad,
		prodcompras.preciounitario");
		$builder->join("catproductos", "catproductos.idProducto=prodcompras.idProducto","LEFT");
		$builder->where("prodcompras.idTransaccion", $idTransaccion);
        $builder->orderBy('prodcompras.idTransaccion', 'asc');
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroProductos($page, $pageSize,$idTransaccion)
	{

        $db      = \Config\Database::connect();
		$builder = $db->table('prodcompras');
		$builder->select("*");
		$builder->where("prodcompras.idTransaccion", $idTransaccion);
		return $builder->get()->getNumRows();
	}

	function getSumaProductos($idTransaccion)
	{

        $db      = \Config\Database::connect();
		$builder = $db->table('prodcompras');
		$builder->select("SUM(prodcompras.cantidad*prodcompras.preciounitario) as totaltransaccion");

		$builder->where("prodcompras.idTransaccion", $idTransaccion);
		return $builder->get()->getRowArray();
	}

	function insertTransaccion($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('prodtransaccion');
		$builder->insert($data);
		return $db->insertID();
	}
    function insert($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('ProdVentas');
		$builder->insert($data);
		return $db->insertID();
	}

    function actualizarVenta($idVenta= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('ProdVentas');
		$builder->where("idVenta", $idVenta);
		$builder->update($data);
	}
    function deleteVenta($idVenta= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('ProdVentas');
		$builder->where("idVenta", $idVenta);
		$builder->delete();
	}
    function selectVentaxid($idVenta)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('ProdVentas');
        $builder->select("ProdVentas.*,G.nombreGasolinera as nombreGasolinera");
		$builder->join("CatGasolineras G", "G.idGasolinera=ProdVentas.idGasolinera","LEFT");
		$builder->where('ProdVentas.idVenta', $idVenta);
		return $builder->get()->getRowArray();
	}

	function crearCompra($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('prodcompras');
		$builder->insert($data);
		return $db->insertID();
	}
	function crearRentaCompra($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('prodrentacaja');
		$builder->insert($data);
		return $db->insertID();
	}
	function crearTempAcunacion($idTransaccion,$productos){
		$db    = \Config\Database::connect();
		foreach($productos as $prod){
			$compra=[
				'idTransaccion'=>$idTransaccion,
				'idProducto'=>$prod['id'],
				'cantidad'=>$prod['quantity'],
				'precio'=>number_format($prod['pricemxn'], 2, '.', ''),
				'precioUsd'=>number_format($prod['price'], 2, '.', ''),
				'fechaCreacion'=>date('Y-m-d'),
			];
			$builder = $db->table('tempprodacunacion');
			$builder->insert($compra);
		}
	}
	function selectProdacunacionTemporal($idPreferencia)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('tempprodacunacion');
        $builder->select("tempprodacunacion.*");
		$builder->join("temprodtransaccion", "temprodtransaccion.idTransaccion=tempprodacunacion.idTransaccion","LEFT");
		$builder->where('temprodtransaccion.idPreferencia', $idPreferencia);
		return $builder->get()->getResultArray();
	}

	function crearAcunacion($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('prodacunacion');
		$builder->insert($data);
		return $db->insertID();
	}

	function actualizarAcunacion($idTransaccion= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('prodacunacion');
		$builder->where("idTransaccion", $idTransaccion);
		$builder->update($data);
	}

	function crearTempTransacciones($data = NULL,$returnID = true){
		$db      = \Config\Database::connect();
		$forge = \Config\Database::forge();
		$forge->addField('idTransaccion INT NOT NULL AUTO_INCREMENT');
		$forge->addField('idPreferencia varchar(50)');
		$forge->addField('idCliente int DEFAULT NULL');
		$forge->addField('fecha date DEFAULT NULL');
		$forge->addPrimaryKey('idTransaccion', 'transaccion');
		$forge->addForeignKey('idCliente', 'clientes', 'id_cliente');
		$forge->createTable('temprodtransaccion', true,['TEMPORARY TABLE']);
		$builder = $db->table('temprodtransaccion');
		$builder->insert($data);
		return $db->insertID();
	}

	function crearTempCompra($idTransaccion,$productos){
		$db    = \Config\Database::connect();
		$forge = \Config\Database::forge();
		$forge->addField('idCompra int NOT NULL AUTO_INCREMENT');
		$forge->addField('idTransaccion int DEFAULT NULL');
		$forge->addField('idProducto int NOT NULL');
		$forge->addField('cantidad int DEFAULT NULL');
		$forge->addField('preciounitarioUSD double DEFAULT NULL');
		$forge->addField('preciounitario double DEFAULT NULL');
		$forge->addPrimaryKey('idCompra', 'compra');
		$forge->addForeignKey('idTransaccion', 'temprodtransaccion', 'idTransaccion');
		$forge->addForeignKey('idProducto', 'catproductos', 'idProducto');
		$forge->createTable('temprodcompra', true,['TEMPORARY TABLE']);
		foreach($productos as $prod){
			$compra=[
				'idTransaccion'=>$idTransaccion,
				'idProducto'=>$prod['id'],
				'cantidad'=>$prod['quantity'],
				'preciounitario'=>number_format($prod['pricemxn'], 2, '.', ''),
				'preciounitarioUSD'=>number_format($prod['price'], 2, '.', '')
			];
			$builder = $db->table('temprodcompra');
			$builder->insert($compra);
		}
	}
	function selecttemporaltransaccion($idPreferencia)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('temprodtransaccion');
        $builder->select("temprodtransaccion.*");
		$builder->where('temprodtransaccion.idPreferencia', $idPreferencia);
		return $builder->get()->getRowArray();
	}
	function selectCompraTemporal($idPreferencia)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('temprodcompra');
        $builder->select("temprodcompra.*");
		$builder->join("temprodtransaccion", "temprodtransaccion.idTransaccion=temprodcompra.idTransaccion","LEFT");
		$builder->where('temprodtransaccion.idPreferencia', $idPreferencia);
		return $builder->get()->getResultArray();
	}
	function selecttransaccionxPreferencia($idPreferencia)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('prodtransaccion');
        $builder->select("prodtransaccion.*");
		$builder->where('prodtransaccion.idPreferencia', $idPreferencia);
		return $builder->get()->getRowArray();
	}
	function selecttransaccionxidCliente($idCliente)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('prodtransaccion');
        $builder->select("prodtransaccion.*");
		$builder->where('prodtransaccion.idCliente', $idCliente);
		$builder->whereNotIn('prodtransaccion.estatusCompra', array(2,3));
		return $builder->get()->getResultArray();
	}

	function deletetemprodcompra($idCliente= NULL,$purge = false)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('temprodtransaccion');
        $builder->select("temprodtransaccion.idTransaccion");
		$builder->where('temprodtransaccion.idCliente', $idCliente);
		$comp= $builder->get()->getResultArray();
		$id=[];
		foreach($comp as $ids){
			array_push($id,$ids['idTransaccion']);
		}
		$builder = $db->table('temprodcompra');
		$builder->where_in("idTransaccion", $id);
		$builder->delete();
	}
	function deletetemprodtransaccion($idCliente= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('temprodtransaccion');
		$builder->where("idCliente", $idCliente);
		$builder->delete();
	}

	function selectProductosCompradosxCliente($idCliente)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('prodtransaccion');
        $builder->select("prodtransaccion.idTransaccion,
						prodtransaccion.idPreferencia,
						prodcompras.idCompra,
						prodcompras.idProducto,
						catproductos.nombreProducto,
						prodcompras.cantidad,
						prodcompras.preciounitario,
						prodtransaccion.idCliente");
		$builder->join("prodcompras", "prodcompras.idTransaccion=prodtransaccion.idTransaccion","LEFT");
		$builder->join("catproductos", "prodcompras.idProducto=catproductos.idProducto","LEFT");
		$builder->where('prodtransaccion.estatusCompra', 1);
		$builder->where('prodtransaccion.idCliente', $idCliente);
		return $builder->get()->getResultArray();
	}

	
    function actualizarCompra($idTransaccion= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('prodtransaccion');
		$builder->where("idTransaccion", $idTransaccion);
		$builder->update($data);
	}

	function selectultimatransaccionxidCliente($idCliente)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('prodtransaccion');
        $builder->select("prodtransaccion.*");
		$builder->where('prodtransaccion.idCliente', $idCliente);
        $builder->orderBy('prodtransaccion.idTransaccion', 'desc');		
		$builder->limit(1);
		return $builder->get()->getRowArray();
	}

	function selectProductosxid($id_cliente)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('catproductos');
		$builder->select('catproductos.*');
		$builder->where('catproductos.idProducto', $id_cliente);
		return $builder->get()->getRowArray();
	}
	function selecttransaccion($idTransaccion)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('prodtransaccion');
        $builder->select("prodtransaccion.*");
		$builder->where('prodtransaccion.idTransaccion', $idTransaccion);
		return $builder->get()->getRowArray();
	}
	function selectCompratransaccion($idTransaccion)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('prodcompras');
        $builder->select("prodcompras.*");
		$builder->join("prodtransaccion", "prodtransaccion.idTransaccion=prodcompras.idTransaccion","LEFT");
		$builder->where('prodtransaccion.idTransaccion', $idTransaccion);
		return $builder->get()->getResultArray();
	}

	function obtenerUsuariosAdmin()
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('clientes');
		$builder->select('clientes.*');
		$builder->whereIn('clientes.idtipoUsuario ', array(1,3));
		return $builder->get()->getResultArray();
	}

	function selectEmpresaEnvioxidEmpresa($idEmpresa)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('catempresaenvio');
        $builder->select("catempresaenvio.*");
		$builder->where('catempresaenvio.idEmpresa', $idEmpresa);
		return $builder->get()->getRowArray();
	}

	function selectEmpresaEnvioListado()
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('catempresaenvio');
        $builder->select("catempresaenvio.*");
		return $builder->get()->getResultArray();
	}

	function getAcunacionxcliente($page, $pageSize,$idCliente)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('prodtransaccion');
        $builder->select("prodtransaccion.idTransaccion,
		prodtransaccion.estatusCompra,
		prodtransaccion.fecha as fechaCompra,
		prodrentacaja.fechaInicio as inicioRenta,
		prodrentacaja.fechaFinal as finalRenta,
		prodtransaccion.archivoTransferencia,
		prodenvio.fechaCreacion,
		prodenvio.fechaEnvio,
		prodenvio.empresaEnvio,
		prodenvio.guiaEnvio");
		$builder->join("prodacunacion", "prodacunacion.idTransaccion=prodtransaccion.idTransaccion");
		$builder->join("prodenvio", "prodenvio.idTransaccion=prodtransaccion.idTransaccion","LEFT");
		$builder->where("prodtransaccion.idCliente", $idCliente);
        $builder->orderBy('prodtransaccion.idTransaccion', 'asc');
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroAcunacionxcliente($page, $pageSize,$idCliente)
	{

        $db      = \Config\Database::connect();
		$builder = $db->table('prodtransaccion');
		$builder->select("*");
		$builder->join("prodacunacion", "prodacunacion.idTransaccion=prodtransaccion.idTransaccion");
		$builder->join("prodenvio", "prodenvio.idTransaccion=prodtransaccion.idTransaccion","LEFT");
		
		$builder->where("prodtransaccion.idCliente", $idCliente);
		return $builder->get()->getNumRows();
	}

	
	function getProductoAcunacion($page, $pageSize,$idTransaccion)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('prodacunacion');
        $builder->select("prodacunacion.idCompra,
		prodacunacion.idTransaccion,
		prodacunacion.idProducto,
		catproductos.nombreProducto,
		prodacunacion.cantidad,
		prodacunacion.preciounitario");
		$builder->join("catproductos", "catproductos.idProducto=prodacunacion.idProducto","LEFT");
		$builder->where("prodacunacion.idTransaccion", $idTransaccion);
        $builder->orderBy('prodacunacion.idTransaccion', 'asc');
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroProductoAcunacion($page, $pageSize,$idTransaccion)
	{

        $db      = \Config\Database::connect();
		$builder = $db->table('prodacunacion');
		$builder->select("*");
		$builder->where("prodacunacion.idTransaccion", $idTransaccion);
		return $builder->get()->getNumRows();
	}

	function crearEnvio($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('prodenvio');
		$builder->insert($data);
		return $db->insertID();
	}

	function actualizarEnvio($idTransaccion= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('prodenvio');
		$builder->where("idTransaccion", $idTransaccion);
		$builder->update($data);
	}

	function selectEnviotransaccion($idTransaccion)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('prodenvio');
        $builder->select("prodenvio.*");
		$builder->join("prodtransaccion", "prodtransaccion.idTransaccion=prodenvio.idTransaccion","LEFT");
		$builder->where('prodenvio.idTransaccion', $idTransaccion);
		return $builder->get()->getRowArray();
	}

	
}
?>