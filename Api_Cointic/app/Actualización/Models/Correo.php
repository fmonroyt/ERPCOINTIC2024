<?php
namespace App\Models;
use CodeIgniter\Model;
class Correo extends Model
{

    $email = \Config\Services::email();
    function enviar($titulo, $destinatario, $mensaje, $archivos = null, $bcc = "")
    {
        try {
            // $this->email->initialize();
            $this->email->from("noreply@deisa.com.mx", "Deisa");
            $this->email->reply_to("sistema@dnamexico.com.mx", "Deisa");
            $this->email->to($destinatario);
            if($bcc)
				$this->email->bcc($bcc);
            $this->email->subject($titulo);
            $this->email->message($mensaje);
            $this->email->set_mailtype('html');
            if(!empty($archivos)) {
                // cada archivo debe estructurarse así: 
                /*
                    {
                        directorio: string, // la ruta del archivo
                        nombre: string // el nombre del archivo
                    }
                */
                foreach($archivos as $archivo) {
                    $this->email->attach($archivo['directorio'], 'attachment', $archivo['nombre']);
                }
            }
            $resultado = FALSE;
            $resultado = $this->email->send();
        } catch (Exception $x) {
            $resultado = FALSE;
            $x->getMessage();
        }
        return $resultado;
    }
}
?>