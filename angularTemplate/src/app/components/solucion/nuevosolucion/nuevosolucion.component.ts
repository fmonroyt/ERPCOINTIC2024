import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import {Marca} from 'src/app/models/marca.model';
@Component({
  selector: 'app-nuevosolucion',
  templateUrl: './nuevosolucion.component.html',
  styleUrls: ['./nuevosolucion.component.scss']
})
export class NuevosolucionComponent implements OnInit {
  @Output() nuSolucion = new EventEmitter<boolean>();
  Marcas:Marca[]=[];
  Solucion: FormGroup;
  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder) {
    this.Solucion = formBuilder.group({
      nombre: ['', Validators.compose([Validators.required])]
    });
   }

  ngOnInit(): void {
  }
  obtenerMarcasSol() {
    this.httpClient.get(environment.api_url + 'Soluciones/getMarcas').subscribe((data: any[]) =>  {
      for (let i = 0; i < data.length; i++) {
        this.Marcas.push(new Marca(data[i]));
      }      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }
  
  enviar(values:any) {
    const formData = new FormData();
    formData.append('nombre', values['nombre']);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    // console.log(this.proyectoAceptado.idUsuarioLogeado); //imprimo el usuario logeado
    this.httpClient.post(environment.api_url + 'Soluciones/CrearSolucion',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.nuSolucion.emit(true);
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

}
