<?php
namespace App\Models;
use CodeIgniter\Model;
class Producto extends Model
{
    function getProductos($page, $pageSize, $sort, $order,$filtro)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatProduct_Fort');
        $builder->select("CatProduct_Fort.*");
		$builder->like("SKU",$filtro,"both");
		$builder->orlike("Unit",$filtro,"both");
		$builder->orlike("Description",$filtro,"both");
		$builder->orderBy('CatProduct_Fort.	idProducto_Fort', $order);
		// $builder->orderBy('Unit', $order);
		// $builder->orderBy('SKU', $order);
		// $builder->orderBy('Description', $order);
		// $builder->orderBy('Price', $order);
		// $builder->orderBy('Contract_1Yr', $order);
		// $builder->orderBy('Contract_2Yr', $order);
		// $builder->orderBy('Contract_3Yr', $order);
		// $builder->orderBy('Contract_4Yr', $order);
		// $builder->orderBy('Contract_5Yr', $order);
		// $builder->orderBy('Comments', $order);
		// $builder->orderBy('Category', $order);
		$builder->orderBy('idrel', $order);
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroProductos($page, $pageSize, $sort, $order,$filtro)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatProduct_Fort');
		$builder->select("*");
		$builder->like("SKU",$filtro,"both");
		$builder->orlike("Unit",$filtro,"both");
		$builder->orlike("Description",$filtro,"both");
		return $builder->get()->getNumRows();
	}
    function crearProducto($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatProduct_Fort');
		$builder->insert($data);
		return $db->insertID();
	}

    function actualizarProductos($idProducto_Fort= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatProduct_Fort');
		$builder->where("idProducto_Fort", $idProducto_Fort);
		$builder->update($data);
	}

    
	function deleteProductos($idProducto_Fort= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('CatProduct_Fort');
		$builder->where("idProducto_Fort", $idProducto_Fort);
		$builder->delete();
	}
    function selectProductosxid($idProducto_Fort)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatProduct_Fort');
		$builder->select('CatProduct_Fort.*');
		$builder->where('idProducto_Fort', $idProducto_Fort);
		return $builder->get()->getRowArray();
	}
    function selectProductosxsku($SKU)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatProduct_Fort');
		$builder->select('CatProduct_Fort.*');
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