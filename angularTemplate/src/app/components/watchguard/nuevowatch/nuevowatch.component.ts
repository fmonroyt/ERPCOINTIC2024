import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import { Relmarca} from 'src/app/models/relmarca.model';
import { Watchguard } from 'src/app/models/watchguard.model';

@Component({
  selector: 'app-nuevowatch',
  templateUrl: './nuevowatch.component.html',
  styleUrls: ['./nuevowatch.component.scss']
})
export class NuevowatchComponent implements OnInit {
  Relmarcas:Relmarca[]=[];
  Watchguards:Watchguard[]=[];
  @Output() nuWatchguard= new EventEmitter<boolean>();
  Watchguard: FormGroup;


  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder) {
    this.Watchguard = formBuilder.group({
      SKU: ['', Validators.compose([Validators.required])],
      Description: ['', Validators.compose([Validators.required])],
      Precio: ['', Validators.compose([Validators.required])],
      Category: ['', Validators.compose([Validators.required])],
      idrel: ['', Validators.compose([Validators.required])],
    });
    this.obtenerRelmarca();
   }

  ngOnInit(): void {
  }

  obtenerRelmarca() {
    this.httpClient.get(environment.api_url + 'Watchguards/obtenerRelProductosSolucion').subscribe((data: any[]) =>  {
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
    formData.append('Category', values['Category']);
    formData.append('idrel', values['idrel']);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    // console.log(this.proyectoAceptado.idUsuarioLogeado); //imprimo el usuario logeado
    this.httpClient.post(environment.api_url + 'Watchguards/CrearProducto',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.nuWatchguard.emit(true);
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

}
