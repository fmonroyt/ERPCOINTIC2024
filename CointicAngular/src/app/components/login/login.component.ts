import { Component, OnInit } from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {Router} from '@angular/router';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../services/auth.service';
import Swal from 'sweetalert2';
import { NotificacionService } from '../../services/notificacion.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent  {
  loginForm: FormGroup;
  constructor(formBuilder: FormBuilder, private httpClient: HttpClient, private router: Router, private authService: AuthService, private notificaciones: NotificacionService) {
    this.loginForm = formBuilder.group({
      email: ['', Validators.compose([Validators.required, Validators.minLength(5)])],
      password: ['', Validators.compose([Validators.required, Validators.minLength(8)])]
    });
    if (authService.isLoggedIn()) {
      this.router.navigate(['/dashboard']);
    }
   }
   login(values: any) {

    const logged = this.authService.login(values['email'], values['password']);
    logged.subscribe((data) => {
      localStorage.setItem('jwt-admin', data['jwt']);
      this.router.navigate(['/dashboard']);
    }, (error) => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");

    });
  }

}
