<?php
namespace App\Models;
use CodeIgniter\Model;
class Sonic extends Model
{
    function getProductos($page, $pageSize, $sort, $order,$filtro)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatProduct_Sonic');
        $builder->select("CatProduct_Sonic.*");
		$builder->like("SKU",$filtro,"both");
		// $builder->orlike("Unit",$filtro,"both");
		$builder->orlike("Description",$filtro,"both");
		$builder->orderBy('CatProduct_Sonic.idproducto_Sonic', $order);
		// $builder->orderBy('Unit', $order);
		$builder->orderBy('SKU', $order);
		$builder->orderBy('Description', $order);
		$builder->orderBy('Precio', $order);
		// $builder->orderBy('Contract_1Yr', $order);
		// $builder->orderBy('Contract_2Yr', $order);
		// $builder->orderBy('Contract_3Yr', $order);
		// $builder->orderBy('Contract_4Yr', $order);
		// $builder->orderBy('Contract_5Yr', $order);
		// $builder->orderBy('Comments', $order);
		$builder->orderBy('HardwareorSoftware', $order);
		$builder->orderBy('idrel', $order);
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroProductos($page, $pageSize, $sort, $order,$filtro)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatProduct_Sonic');
		$builder->select("*");
		$builder->like("SKU",$filtro,"both");
		// $builder->orlike("Unit",$filtro,"both");
		$builder->orlike("Description",$filtro,"both");
		return $builder->get()->getNumRows();
	}
    function crearProducto($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatProduct_Sonic');
		$builder->insert($data);
		return $db->insertID();
	}

    function actualizarProductos($idproducto_Sonic= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatProduct_Sonic');
		$builder->where("idproducto_Sonic", $idproducto_Sonic	);
		$builder->update($data);
	}

    
	function deleteProductos($idproducto_Sonic= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('CatProduct_Sonic');
		$builder->where("idproducto_Sonic", $idproducto_Sonic);
		$builder->delete();
	}
    function selectProductosxid($idproducto_Sonic)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatProduct_Sonic');
		$builder->select('CatProduct_Sonic.*');
		$builder->where('idproducto_Sonic', $idproducto_Sonic);
		return $builder->get()->getRowArray();
	}
    function selectProductosxsku($SKU)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatProduct_Sonic');
		$builder->select('CatProduct_Sonic.*');
		$builder->where('SKU', $SKU);
		return $builder->get()->getRowArray();
	}

    function selectRelProductosSolucion()
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('relmarcasolucion');
		$builder->select("relmarcasolucion.idrel,CONCAT(CatMarcas.Nombre,' - ',CatSoluciones.nombre ) as nombreSolucion");
		$builder->join("CatSoluciones","CatSoluciones.idsoluciones=relmarcasolucion.idsoluciones","left");
		$builder->join("CatMarcas","CatMarcas.idMarcas=relmarcasolucion.idmarca","left");
		return $builder->get()->getResultArray();
	}
}
?>