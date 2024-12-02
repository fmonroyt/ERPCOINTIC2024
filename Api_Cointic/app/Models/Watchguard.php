<?php
namespace App\Models;
use CodeIgniter\Model;
class Watchguard extends Model
{
    function getProductos($page, $pageSize, $sort, $order,$filtro)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatProduct_Watchguard');
        $builder->select("CatProduct_Watchguard.*");
		$builder->like("SKU",$filtro,"both");
		$builder->orlike("Description",$filtro,"both");
		$builder->orderBy('CatProduct_Watchguard.idproduct_Watchguard', $order);
		$builder->orderBy('SKU', $order);
		$builder->orderBy('Description', $order);
		$builder->orderBy('Precio', $order);
		$builder->orderBy('Category', $order);
		$builder->orderBy('idrel', $order);
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroProductos($page, $pageSize, $sort, $order,$filtro)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatProduct_Watchguard');
		$builder->select("*");
		$builder->like("SKU",$filtro,"both");
		$builder->orlike("Description",$filtro,"both");
		return $builder->get()->getNumRows();
	}
    function crearProducto($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatProduct_Watchguard');
		$builder->insert($data);
		return $db->insertID();
	}

    function actualizarProductos($idproduct_Watchguard= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatProduct_Watchguard');
		$builder->where("idproduct_Watchguard", $idproduct_Watchguard);
		$builder->update($data);
	}

    
	function deleteProductos($idproduct_Watchguard= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('CatProduct_Watchguard');
		$builder->where("idproduct_Watchguard", $idproduct_Watchguard);
		$builder->delete();
	}
    function selectProductosxid($idproduct_Watchguard)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatProduct_Watchguard');
		$builder->select('CatProduct_Watchguard.*');
		$builder->where('idproduct_Watchguard', $idproduct_Watchguard);
		return $builder->get()->getRowArray();
	}
    function selectProductosxsku($SKU)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatProduct_Watchguard');
		$builder->select('CatProduct_Watchguard.*');
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