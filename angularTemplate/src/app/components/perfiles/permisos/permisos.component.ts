import { Component, OnInit } from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import { PermisosService } from 'src/app/services/permisos.service';
import { Perfil } from 'src/app/models/perfil';
import { Permiso } from 'src/app/models/permiso';
import { Modulos } from 'src/app/models/modulos'

@Component({
  selector: 'app-permisos',
  templateUrl: './permisos.component.html',
  styleUrls: ['./permisos.component.scss']
})
export class PermisosComponent implements OnInit {
  idPerfil:string;
  Perfiles:Perfil;
  Modulos:Modulos[]=[];
  permisos: Permiso[];
  
  constructor(private activatedRoute: ActivatedRoute,private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder,public permisosService: PermisosService) { 
    this.idPerfil= this.activatedRoute.snapshot.paramMap.get('idPerfil');
    this.obtenerNombrePerfil();
    this.obtenerModulos();
    this.cargarPermisos();
  }

  ngOnInit() {
  }

  obtenerNombrePerfil() {
    const formData = new FormData();
    formData.append('idPerfil', this.idPerfil);
    // console.log(this.proyectoAceptado.idUsuarioLogeado); //imprimo el usuario logeado
    this.httpClient.post(environment.api_url + 'Perfiles/obtenerPerfilxId',formData).subscribe((data: any[]) =>  {
      this.Perfiles = new Perfil(data);
      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

  cargarPermisos() {
    const formData = new FormData();
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    formData.append('idPerfil', this.idPerfil);
    this.httpClient.post(environment.api_url + 'Permisos/getPermisosUsuario', formData).subscribe((data: any[]) => {
      const temp = [];
      for (const response of data) {
        temp[response['idModulo']] = new Permiso(response);
      }
      this.permisos = temp;
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'], 'fa fa-times', 'error');
    });
  }

  asignarPermiso(columna, modulo, isChecked: boolean) {
    console.log("El modulo "+modulo+ ", columna "+columna+ " tiene permisos: "+isChecked);
    const formData = new FormData();
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    formData.append('columna', columna);
    formData.append('idModulo', modulo);
    formData.append('valor', ((isChecked)? '1': '0'));
    formData.append('idPerfil', this.idPerfil.toString());
    this.httpClient.post(environment.api_url + 'Permisos/asignarPermiso', formData).subscribe();
  }
  
    obtenerModulos() {
      // console.log(this.proyectoAceptado.idUsuarioLogeado); //imprimo el usuario logeado
      this.httpClient.get(environment.api_url + 'Perfiles/obtenerModulos').subscribe((data: any[]) =>  {
        for (let i = 0; i < data.length; i++) {
        this.Modulos.push(new Modulos(data[i]));
      }
      }, error => {
        this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
      });  
    }

    getPermisosModulo(columna, modulo) {
      if(this.permisos[modulo])
        return this.permisos[modulo][columna];
      return false;
    }
}



