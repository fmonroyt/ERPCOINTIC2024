import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import { Relmarca} from 'src/app/models/relmarca.model';
import { Sopho } from 'src/app/models/sopho.model';

@Component({
  selector: 'app-nuevosophos',
  templateUrl: './nuevosophos.component.html',
  styleUrls: ['./nuevosophos.component.scss']
})
export class NuevosophosComponent implements OnInit {
  Relmarcas:Relmarca[]=[];
  Sophos:Sopho[]=[];
  @Output() nuSopho= new EventEmitter<boolean>();
  Sopho: FormGroup;


  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder) {
    this.Sopho = formBuilder.group({
      SKU: ['', Validators.compose([Validators.required])],
      Description: ['', Validators.compose([Validators.required])],
      Precio: ['', Validators.compose([Validators.required])],
      idrel: ['', Validators.compose([Validators.required])],
    });
    this.obtenerRelmarca();
   }

  ngOnInit(): void {
  }

  obtenerRelmarca() {
    this.httpClient.get(environment.api_url + 'Sophos/obtenerRelProductosSolucion').subscribe((data: any[]) =>  {
      for (let i = 0; i < data.length; i++) {
        this.Relmarcas.push(new Relmarca(data[i]));
      }      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

  
  enviar(values:any) {
    const formData = new FormData();
    formData.append('SKU', values['SKU']);
    formData.append('Description', values['Description']);
    formData.append('Precio', values['Precio']);
    formData.append('idrel', values['idrel']);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    // console.log(this.proyectoAceptado.idUsuarioLogeado); //imprimo el usuario logeado
    this.httpClient.post(environment.api_url + 'Sophos/CrearProducto',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.nuSopho.emit(true);
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }


}
