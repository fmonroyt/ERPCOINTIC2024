<?php
namespace App\Models;
use CodeIgniter\Model;
class Contacto extends Model
{
    function getContactos($page, $pageSize, $sort, $order,$filtro)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatContactos');
        $builder->select("CatContactos.*");
		$builder->like("nombre",$filtro,"both");
		//$builder->orlike("Description",$filtro,"both");
		$builder->orderBy('CatContactos.idcontacto', $order);
		$builder->orderBy('nombre', $order);
		$builder->orderBy('apellido', $order); 
		$builder->orderBy('correo', $order);
		$builder->orderBy('telefono', $order);
		$builder->orderBy('idempresaext', $order);
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroContactos($page, $pageSize, $sort, $order,$filtro)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatContactos');
		$builder->select("*");
		$builder->like("nombre",$filtro,"both");
		$builder->orlike("telefono",$filtro,"both");
		return $builder->get()->getNumRows();
	}
    function crearContacto($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatContactos');
		$builder->insert($data);
		return $db->insertID();
	}

    function actualizarContacto($idcontacto= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatContactos');
		$builder->where("idcontacto", $idcontacto);
		$builder->update($data);
	}

    
	function deleteContacto($idcontacto= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('CatContactos');
		$builder->where("idcontacto", $idcontacto);
		$builder->delete();
	}
    function selectContactoxid($idcontacto)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatContactos');
		$builder->select('CatContactos.*');
		$builder->where('idcontacto', $idcontacto);
		return $builder->get()->getRowArray();
	}
    function selectContactoxnombre($nombre)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatContactos');
		$builder->select('CatContactos.*');
		$builder->where('nombre', $nombre);
		return $builder->get()->getRowArray();
	}

    function selectIdempresaext()
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatEmpresaExterna');
        $builder->select("CatEmpresaExterna.*"); 
		
        return $builder->get()->getResultArray();
	}
	
}
?>