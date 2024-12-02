<?php
namespace App\Models;
use CodeIgniter\Model;
class Sopho extends Model
{
    function getProductos($page, $pageSize, $sort, $order,$filtro)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatProduct_Sophos');
        $builder->select("CatProduct_Sophos.*");
		$builder->like("SKU",$filtro,"both");
		// $builder->orlike("Unit",$filtro,"both");
		$builder->orlike("Description",$filtro,"both");
		$builder->orderBy('CatProduct_Sophos.idproduct_Sophos', $order);
		// $builder->orderBy('Unit', $order);
		$builder->orderBy('SKU', $order);
		$builder->orderBy('Description', $order);
		$builder->orderBy('Precio', $order);
		$builder->orderBy('idrel', $order);
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroProductos($page, $pageSize, $sort, $order,$filtro)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatProduct_Sophos');
		$builder->select("*");
		$builder->like("SKU",$filtro,"both");
		// $builder->orlike("Unit",$filtro,"both");
		$builder->orlike("Description",$filtro,"both");
		return $builder->get()->getNumRows();
	}
    function crearProducto($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatProduct_Sophos');
		$builder->insert($data);
		return $db->insertID();
	}

    function actualizarProductos($idproduct_Sophos= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatProduct_Sophos');
		$builder->where("idproduct_Sophos", $idproduct_Sophos);
		$builder->update($data);
	}

    
	function deleteProductos($idproduct_Sophos= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('CatProduct_Sophos');
		$builder->where("idproduct_Sophos", $idproduct_Sophos);
		$builder->delete();
	}
    function selectProductosxid($idproduct_Sophos)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatProduct_Sophos');
		$builder->select('CatProduct_Sophos.*');
		$builder->where('idproduct_Sophos', $idproduct_Sophos);
		return $builder->get()->getRowArray();
	}
    function selectProductosxsku($SKU)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatProduct_Sophos');
		$builder->select('CatProduct_Sophos.*');
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