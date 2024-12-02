<?php
namespace App\Models;
use CodeIgniter\Model;
class EmpresaExt extends Model
{
    function getEmpresas($page, $pageSize, $sort, $order,$filtro)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatEmpresaExterna');
        $builder->select("CatEmpresaExterna.*");
		$builder->like("nombre",$filtro,"both");
		//$builder->orlike("Description",$filtro,"both");
		$builder->orderBy('CatEmpresaExterna.idEmpresaExt', $order);
		$builder->orderBy('nombre', $order);
		$builder->orderBy('telefono', $order); 
		$builder->orderBy('ciudad', $order);
		$builder->orderBy('pais', $order);
		$builder->orderBy('idusuario', $order);
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroEmpresas($page, $pageSize, $sort, $order,$filtro)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatEmpresaExterna');
		$builder->select("*");
		$builder->like("nombre",$filtro,"both");
		$builder->orlike("telefono",$filtro,"both");
		return $builder->get()->getNumRows();
	}
	
    function crearEmpresa($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatEmpresaExterna');
		$builder->insert($data);
		return $db->insertID();
	}

    function actualizarEmpresa($idEmpresaExt= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatEmpresaExterna');
		$builder->where("idEmpresaExt", $idEmpresaExt);
		$builder->update($data);
	}

    
	function deleteEmpresa($idEmpresaExt= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('CatEmpresaExterna');
		$builder->where("idEmpresaExt", $idEmpresaExt);
		$builder->delete();
	}
    function selectEmpresaxid($idEmpresaExt)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatEmpresaExterna');
		$builder->select('CatEmpresaExterna.*');
		$builder->where('idEmpresaExt', $idEmpresaExt);
		return $builder->get()->getRowArray();
	}
    function selectEmpresaxnombre($nombre)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatEmpresaExterna');
		$builder->select('CatEmpresaExterna.*');
		$builder->where('nombre', $nombre);
		return $builder->get()->getRowArray();
	}

    function selectIdUsuario()
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatUsuarios');
        $builder->select("CatUsuarios.*"); 
		$builder->where('idPerfil', 10);
        return $builder->get()->getResultArray();
	}
	
}
?>