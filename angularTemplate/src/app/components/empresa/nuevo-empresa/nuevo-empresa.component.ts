import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import { Relmarca} from 'src/app/models/relmarca.model';
import { Empresa } from 'src/app/models/empresa.model';
import { Usuario } from 'src/app/models/usuario';

@Component({
  selector: 'app-nuevo-empresa',
  templateUrl: './nuevo-empresa.component.html',
  styleUrls: ['./nuevo-empresa.component.scss']
})
export class NuevoEmpresaComponent implements OnInit {

  Usuarios:Usuario[]=[];
  Empresas:Empresa[]=[];
  @Output() nuEmpresa= new EventEmitter<boolean>();
  Empresa: FormGroup;

  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder) {
    this.Empresa = formBuilder.group({
     
      nombre: ['', Validators.compose([Validators.required])],
      telefono: ['', Validators.compose([Validators.required])],
      ciudad: ['', Validators.compose([Validators.required])],
      pais: ['', Validators.compose([Validators.required])],
      idusuario: ['', Validators.compose([Validators.required])],
    });
    this.obtenerUsuario();
   }

   ngOnInit(): void {
  }

  obtenerUsuario() {
    this.httpClient.get(environment.api_url + 'EmpresaExts/obtenerIdUsuario').subscribe((data: any[]) =>  {
      for (let i = 0; i < data.length; i++) {
        this.Usuarios.push(new Usuario(data[i]));
      }      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

  enviar(values:any) {
    const formData = new FormData();
    formData.append('nombre', values['nombre']);
    formData.append('telefono', values['telefono']);
    formData.append('ciudad', values['ciudad']);
    formData.append('pais', values['pais']);
    formData.append('idusuario', values['idusuario']);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    // console.log(this.proyectoAceptado.idUsuarioLogeado); //imprimo el usuario logeado
    this.httpClient.post(environment.api_url + 'EmpresaExts/CrearEmpresaExt',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.nuEmpresa.emit(true);
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

}
 