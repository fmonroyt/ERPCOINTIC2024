import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';

@Component({
  selector: 'app-nuevodepartamento',
  templateUrl: './nuevodepartamento.component.html',
  styleUrls: ['./nuevodepartamento.component.scss']
})
export class NuevodepartamentoComponent implements OnInit {
  @Output() nuDepartamento = new EventEmitter<boolean>();
  Departamento: FormGroup;
  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder) { 
    this.Departamento = formBuilder.group({
      nombreDepartamento: ['', Validators.compose([Validators.required])]
    });
  }

  ngOnInit(): void {
  }
  enviar(values:any) {
    const formData = new FormData();
    formData.append('nombreDepartamento', values['nombreDepartamento']);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    // console.log(this.proyectoAceptado.idUsuarioLogeado); //imprimo el usuario logeado
    this.httpClient.post(environment.api_url + 'Departamentos/CrearDepartamento',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.nuDepartamento.emit(true);
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }
}
