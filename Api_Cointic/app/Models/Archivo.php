<?php

namespace App\Models;
use CodeIgniter\Model;
class Archivo extends Model
{
	// sube el archivo al servidor y devuelve un arreglo con la ruta del archivo. Retorna false en caso de error.
	// La variable nombreArchivo es el nombre del parametro dentro del arreglo $_FILES.
	function subirArchivo($nombreArchivo, $ruta = "../assets_globaloro/archivosRecibos") { 
		// por seguridad, la ruta tiene .. al principio para que nadie pueda acceder a ella
		// esto es para que la carpeta de archivos este afuera del htdocs o www
		$ruta.="/"; // agrega una diagonal en caso de que no haya sido proporcionada al final
		if(!file_exists($nombreArchivo) && !is_dir($ruta)){
			mkdir($ruta,0777,true);
		}
		if(!empty($_FILES[$nombreArchivo])) {
			$archivo = $_FILES[$nombreArchivo];
			$nombreOriginal = $archivo['name'];
			$tipoArchivo = $archivo['type'];
			$extension = explode('.', basename($nombreOriginal));
			$extension = array_pop($extension);
			if($this->verificarArchivo($extension, $archivo['size'])) {
				$nuevoNombre = DIRECTORY_SEPARATOR . md5(uniqid()) . "." . $extension;
				$directorio = $ruta.$nuevoNombre;
				if(move_uploaded_file($archivo['tmp_name'], $directorio)) {
					return array(
						'directorio' => $directorio,
						'tipo' => $tipoArchivo,
						'extension' => $extension,
						'nombreOriginal'  => $nombreOriginal
					);
				}
				else  {
					http_response_code(500);
					echo json_encode(array(
						'message' => 'Error al subir el archivo. Intenta de nuevo.'
					));
					exit();
				}
			}
			else {
				http_response_code(500);
				echo json_encode(array(
					'message' => 'La extensión o peso del archivo no son válidos.'
				));
				exit();
			}


		}
		else {
			http_response_code(500);
			echo json_encode(array(
				'message' => 'No has proporcionado ningún archivo'
			));
			exit();
		}

	}

	function borrarArchivo($ruta) {
		if(file_exists($ruta)) {	
			unlink($ruta);
		}
	}


	// verifica si el archivo que se subirá es válido.
	function verificarArchivo($ext, $size)
	{
		$ext = strtolower($ext);
		$bytes = min($this->convertPHPSizeToBytes(ini_get('post_max_size')), $this->convertPHPSizeToBytes(ini_get('upload_max_filesize')));
		if($ext=='svg' || $ext=='xml' || $ext=='php' || $ext=='php1' || $ext=='php2' || $ext=='php3' || $ext=='php4' || $ext=='php5' || $ext=='php6' || $ext=='php7' || $ext=='phtml'
			|| $ext=='html' || $ext=='xhtml' || $ext=='exe' || $ext=='shtml' || $ext=='pht' || $ext=='asa' || $ext=='cer' || $ext=='asax' || $ext=='swf' || $ext=='xap' || $ext=='hta'
			|| $ext=='js' || $ext=='jar' || $ext=='vbs' || $ext=='vb' || $ext=='sfx' || $ext=='bat' || $ext=='py' || $ext=='com' || $size > $bytes)
		{
			return false;
		}
		return true;


	}

	function verificarArchivoExcel($ext, $size)
	{
		$ext = strtolower($ext);
		$bytes = min($this->convertPHPSizeToBytes(ini_get('post_max_size')), $this->convertPHPSizeToBytes(ini_get('upload_max_filesize')));
		if($ext=='xlsx' || $ext=='CVS'|| $size < $bytes)
		{
			return true;
		}
		return false;
	}
	function verificarArchivoImagen($ext, $size)
	{
		$ext = strtolower($ext);
		$bytes = min($this->convertPHPSizeToBytes(ini_get('post_max_size')), $this->convertPHPSizeToBytes(ini_get('upload_max_filesize')));
		if($ext=='JPEG' || $ext=='JPG' || $ext=='PNG' || $ext=='GIF' || $ext=='TIFF' || $ext=='RAW'|| $size < $bytes)
		{
			return true;
		}
		return false;
	}
	// obtiene el peso máximo para subir archivos
	function getMaximumFileUploadSize()
	{

		$bytes = min($this->convertPHPSizeToBytes(ini_get('post_max_size')), $this->convertPHPSizeToBytes(ini_get('upload_max_filesize')));
		$units = array('B', 'KB', 'MB', 'GB', 'TB');

		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
		$bytes /= pow(1024, $pow);

		return round($bytes, 2) . ' ' . $units[$pow];
	}
	// convierte una cadena de php de peso a bytes (1M) -> 1024
	function convertPHPSizeToBytes($sSize)
	{
		$sSuffix = strtoupper(substr($sSize, -1));
		if (!in_array($sSuffix,array('P','T','G','M','K'))){
			return (int)$sSize;
		}
		$iValue = substr($sSize, 0, -1);
		switch ($sSuffix) {
			case 'P':
				$iValue *= 1024;
			// Fallthrough intended
			case 'T':
				$iValue *= 1024;
			// Fallthrough intended
			case 'G':
				$iValue *= 1024;
			// Fallthrough intended
			case 'M':
				$iValue *= 1024;
			// Fallthrough intended
			case 'K':
				$iValue *= 1024;
				break;
		}
		return (int)$iValue;
	}
}
