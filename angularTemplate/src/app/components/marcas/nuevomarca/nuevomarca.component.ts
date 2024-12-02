import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';

@Component({
  selector: 'app-nuevomarca',
  templateUrl: './nuevomarca.component.html',
  styleUrls: ['./nuevomarca.component.scss']
})
export class NuevomarcaComponent implements OnInit {
  @Output() nuMarca = new EventEmitter<boolean>();
  Marca: FormGroup;
  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder) { 
    this.Marca = formBuilder.group({
      Nombre: ['', Validators.compose([Validators.required])]
    });
  }

  ngOnInit(): void {
  }
  enviar(values:any) {
    const formData = new FormData();
    formData.append('Nombre', values['Nombre']);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    // console.log(this.proyectoAceptado.idUsuarioLogeado); //imprimo el usuario logeado
    this.httpClient.post(environment.api_url + 'Marcas/CrearMarca',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.nuMarca.emit(true);
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }
}
