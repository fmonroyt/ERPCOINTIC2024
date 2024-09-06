<?php
namespace App\Models;
use CodeIgniter\Model;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class WebToken extends Model
{
	private $key;
	public function __construct()
	{
		parent::__construct();
		$this->key = "/Sd/w1/s9x/8@@";
	}
	// genera un JWT para la autenticación de usuarios
	function generarJWT($data) {
		$time = time();
		$token = array(
			'iat' => $time, // Tiempo que inició el token
			'exp' => $time + (60*60)*24*30, // Tiempo que expirará el token (24 horas por 30 días  = 1 mes)
			'data' => $data);

		$jwt = JWT::encode($token, $this->key, 'HS256');
		return $jwt;

	}
	function decodificarJWT($jwt) {
		try {
			$jwt = JWT::decode($jwt, new Key($this->key, 'HS256'));
			if(!empty($jwt->data))
			{
				return $jwt->data;
			}
			else {
				return [];
			}
		}
		catch (Exception $x) {
			http_response_code(500);
			echo json_encode(array(
				'message' => 'Error de autenticación. Por favor, vuelve a iniciar sesión.',
				'error' => $x->getMessage()
			));
			exit();
		}
	}
}
