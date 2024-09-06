import { Component, OnInit, EventEmitter, Output} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import{ VariablesService} from 'src/app/services/variables.service';
import {Perfil } from 'src/app/models/perfil';

@Component({
  selector: 'app-editarperfil',
  templateUrl: './editarperfil.component.html',
  styleUrls: ['./editarperfil.component.scss']
})
export class EditarperfilComponent implements OnInit {
 idPerfil:string;
 Perfiles:Perfil;
 Perfil: FormGroup;
 @Output() nuPerfil = new EventEmitter<boolean>();
  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder,private variablesService: VariablesService) { 
    this.idPerfil=this.variablesService.idPerfil.toString();
    this.Perfil = formBuilder.group({
      nombrePerfil: ['', Validators.compose([Validators.required])]
    });
    this.obtenerNombrePerfil();
  }

  ngOnInit() {
  }

  obtenerNombrePerfil() {
    const formData = new FormData();
    formData.append('idPerfil', this.idPerfil);
    // console.log(this.proyectoAceptado.idUsuarioLogeado); //imprimo el usuario logeado
    this.httpClient.post(environment.api_url + 'Perfiles/obtenerPerfilxId',formData).subscribe((data: any[]) =>  {
      this.Perfiles = new Perfil(data);
      this.Perfil.controls['nombrePerfil'].setValue(this.Perfiles.nombrePerfil);
      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

  enviar(values:any) {
    const formData = new FormData();
    formData.append('idPerfil',this.idPerfil);
    formData.append('nombrePerfil', values['nombrePerfil']);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    // console.log(this.proyectoAceptado.idUsuarioLogeado); //imprimo el usuario logeado
    this.httpClient.post(environment.api_url + 'Perfiles/ActualizarPerfil',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.nuPerfil.emit(true);
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }
}
