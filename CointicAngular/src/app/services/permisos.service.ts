import { Injectable } from '@angular/core';
import {CanActivate} from '@angular/router';
import {HttpClient} from '@angular/common/http';
import {environment} from '../../environments/environment';
import {Permiso} from '../models/permiso';

@Injectable({
  providedIn: 'root'
})
export class PermisosService implements CanActivate  {
  permisos:Permiso[] = [];
  canActivate(idModulo, columna) {
    return this.permisos[idModulo] && this.permisos[idModulo][columna] == 1;
  }
  constructor(private httpClient: HttpClient) { 
    this.cargarPermisos();
  }
  cargarPermisos() {
    this.permisos=[];
    const formData = new FormData();
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    this.httpClient.post(environment.api_url + 'Permisos/getPermisosUsuario', formData).subscribe((response: any[]) => {
      for(const permiso of response ) {
        this.permisos[permiso['idModulo']] = permiso;
      }
    });
  }
}
