<?php
namespace App\Models;
use CodeIgniter\Model;
class Cliente extends Model
{
    function getClientes($page, $pageSize,)
	{		
        $db      = \Config\Database::connect();
		$builder = $db->table('clientes');
        $builder->select("clientes.*");
        $builder->orderBy('clientes.id_cliente', 'asc');
		$builder->whereNotIn("clientes.idtipoUsuario", array(1,3));
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroClientes($page, $pageSize)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('clientes');
		$builder->select("*");
		$builder->whereNotIn("clientes.idtipoUsuario", array(1,3));

		
		return $builder->get()->getNumRows();
	}

    function insert($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('clientes');
		$builder->insert($data);
		return $db->insertID();
	}

    function actualizarClientes($id_cliente= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('clientes');
		$builder->where("id_cliente", $id_cliente);
		$builder->update($data);
	}
    function deleteClientes($id_cliente= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('clientes');
		$builder->where("id_cliente", $id_cliente);
		$builder->delete();
	}
    function selectClientesxid($id_cliente)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('clientes');
		$builder->select('clientes.*');
		$builder->join("pais_residencia", "pais_residencia.id_pais_residencia=clientes.pais_residencia","LEFT");
		$builder->where('clientes.id_cliente', $id_cliente);
		return $builder->get()->getRowArray();
	}

	function verificarexistenciaClientes($correo)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('clientes');
		$builder->select('clientes.*');
		$builder->where('clientes.correo', $correo);
		return $builder->get()->getRowArray();
	}


	
	function selectPais()
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('pais_residencia');
		$builder->select('pais_residencia.*');
	
		return $builder->get()->getResultArray();
	}
}
?>