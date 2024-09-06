import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';

@Component({
  selector: 'app-nuevoperfil',
  templateUrl: './nuevoperfil.component.html',
  styleUrls: ['./nuevoperfil.component.scss']
})
export class NuevoperfilComponent implements OnInit {
  @Output() nuPerfil = new EventEmitter<boolean>();
  Perfil: FormGroup;
  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder) {
    this.Perfil = formBuilder.group({
      nombrePerfil: ['', Validators.compose([Validators.required])]
    });
   }

  ngOnInit() {
  }



  enviar(values:any) {
    const formData = new FormData();
    formData.append('nombrePerfil', values['nombrePerfil']);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    // console.log(this.proyectoAceptado.idUsuarioLogeado); //imprimo el usuario logeado
    this.httpClient.post(environment.api_url + 'Perfiles/CrearPerfil',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.nuPerfil.emit(true);
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }
}
