import { Component, OnInit, EventEmitter, Output, ViewEncapsulation } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import{ VariablesService} from 'src/app/services/variables.service';
import { Departamento} from 'src/app/models/departamento';
import{ Perfil} from 'src/app/models/perfil';
import { Usuario } from 'src/app/models/usuario';

@Component({
  selector: 'app-editarusuario',
  templateUrl: './editarusuario.component.html',
  styleUrls: ['./editarusuario.component.scss'],
  encapsulation:ViewEncapsulation.None
})
export class EditarusuarioComponent implements OnInit {
  Departamentos:Departamento[]=[];
  Perfiles:Perfil[]=[];
  idUsuario:string;
  usuariog:Usuario;
  Usuario: FormGroup;
  @Output()nuUsuario = new EventEmitter<boolean>();
  archivos: File[] = [];
  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder,private variablesService: VariablesService) { 
    this.Usuario = formBuilder.group({
      nombreUsuario: ['', Validators.compose([Validators.required])],
      apellidoPaterno: ['', Validators.compose([Validators.required])],
      apellidoMaterno: ['', Validators.compose([Validators.required])],
      nickName: ['', Validators.compose([Validators.required])],
      password: ['', Validators.compose([])],
      correoDestino: ['', Validators.compose([Validators.required])],
      idDepartamento: ['', Validators.compose([Validators.required])],
      idPerfil: ['', Validators.compose([Validators.required])],
    });
    this.idUsuario=this.variablesService.idUsuario.toString();
    this.obtenerDepartamento();
    this.obtenerPerfiles();
    this.obtenerUsuario()
  }

  ngOnInit(): void {
  }
  obtenerUsuario() {
    const formData = new FormData();
    formData.append('idUsuario',this.idUsuario);
    this.httpClient.post(environment.api_url + 'Usuarios/getUsuarioxid',formData).subscribe((data: any[]) =>  {

        this.usuariog=new Usuario(data);
        this.Usuario.controls['nombreUsuario'].setValue(this.usuariog.nombreUsuario);
        this.Usuario.controls['apellidoPaterno'].setValue(this.usuariog.apellidoPaterno);
        this.Usuario.controls['apellidoMaterno'].setValue(this.usuariog.apellidoMaterno);
        this.Usuario.controls['nickName'].setValue(this.usuariog.nickName);
        this.Usuario.controls['correoDestino'].setValue(this.usuariog.correoDestino);
        this.Usuario.controls['idDepartamento'].setValue(this.usuariog.idDepartamento);
        this.Usuario.controls['idPerfil'].setValue(this.usuariog.idPerfil);
          
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }
  obtenerDepartamento() {
    this.httpClient.get(environment.api_url + 'Usuarios/getDepartamentos').subscribe((data: any[]) =>  {
      for (let i = 0; i < data.length; i++) {
        this.Departamentos.push(new Departamento(data[i]));
      }      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }
  obtenerPerfiles() {
    this.httpClient.get(environment.api_url + 'Usuarios/getPerfiles').subscribe((data: any[]) =>  {
      for (let i = 0; i < data.length; i++) {
        this.Perfiles.push(new Perfil(data[i]));
      }      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

  seleccionarArchivo(event) {
    if (this.archivos.length) { // para que solo exista un archivo
        this.archivos = [];
    }
    this.archivos.push(...event.addedFiles);

}
removerArchivo(event) {
    this.archivos.splice(this.archivos.indexOf(event), 1);
}

  enviar(values:any) {
    const formData = new FormData();
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    formData.append('idUsuarioEditar', this.idUsuario);
    formData.append('nombreUsuario', values['nombreUsuario']);
    formData.append('apellidoPaterno', values['apellidoPaterno']);
    formData.append('apellidoMaterno', values['apellidoMaterno']);
    formData.append('nickName', values['nickName']);
    formData.append('password', values['password']);
    formData.append('correoDestino', values['correoDestino']);
    formData.append('idDepartamento', values['idDepartamento']);
    formData.append('idPerfil', values['idPerfil']);
    for ( let i = 0; i < this.archivos.length; i++) {
      formData.append('archivo', this.archivos[i]);
 }
    this.httpClient.post(environment.api_url + 'Usuarios/editarusuario',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.nuUsuario.emit(true);
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

}
