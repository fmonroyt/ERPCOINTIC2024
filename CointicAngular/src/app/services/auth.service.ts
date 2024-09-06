import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router, CanActivate } from '@angular/router';
import { environment } from '../../environments/environment';
import * as jwt_decode from 'jwt-decode';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  constructor(private httpClient: HttpClient, private router: Router) { }
  canActivate() {
    
    // si el usuario no esta logueado, entonces se le envia al login
    if (!this.isLoggedIn()) {
      console.log('No estás logueado');
      this.router.navigate(['/login']);
      return false;
     }
     else {
       let fechaexpiracion=jwt_decode(localStorage.getItem('jwt-admin'))['exp'];
       fechaexpiracion= new Date(fechaexpiracion*1000);
       let fechahoy= new Date();
       if(fechahoy>fechaexpiracion){
      this.router.navigate(['/login']);
      localStorage.removeItem('jwt-admin');
      console.log('Token Expirado');
      // return false;
    }
    
    }
    // TODO: Colocar la validación del tiempo del jwt

    return true;
  }

  // Método para loguearse.
  login(user: string, password: string) {
    const body = new FormData();
    body.append('usuario', user);
    body.append('password', password);

    return this.httpClient.post(
      environment.api_url + 'Usuarios/Autenticar',
      body
    );
  }
  // Destruye la sesión
  logout() {
    localStorage.removeItem('jwt-admin');
    this.router.navigate(['/login']);
  }
  // obtiene el nombre del usuario logueado
  getJWT(): string {
    return localStorage.getItem('jwt-admin');
  }
  // verifica si el usuario esta loggeado
  isLoggedIn(): boolean {
    return this.getJWT() !== null;
  }
  getDecodedAccessToken(jwt: string): any {
    try{
        return jwt_decode(jwt);
    }
    catch(Error){
        return null;
    }
  }
}
