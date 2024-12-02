import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import { Relmarca} from 'src/app/models/relmarca.model';
import { Producto } from 'src/app/models/producto.model';

@Component({
  selector: 'app-nuevoproducto',
  templateUrl: './nuevoproducto.component.html',
  styleUrls: ['./nuevoproducto.component.scss']
})
export class NuevoproductoComponent implements OnInit {
  Relmarcas:Relmarca[]=[];
  Productos:Producto[]=[];
  @Output() nuProducto = new EventEmitter<boolean>();
  Producto: FormGroup;
  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder) { 
    this.Producto = formBuilder.group({
      Unit: ['', Validators.compose([Validators.required])],
      SKU: ['', Validators.compose([Validators.required])],
      Description: ['', Validators.compose([Validators.required])],
      Price: ['', Validators.compose([Validators.required])],
      Contract_1Yr: ['' ],
      Contract_2Yr: [''],
      Contract_3Yr: [''],
      Contract_4Yr: [''],
      Contract_5Yr: [''],
      Comments: ['', Validators.compose([Validators.required])],
      Category: ['', Validators.compose([Validators.required])],
      idrel: ['', Validators.compose([Validators.required])],
    });
    this.obtenerRelmarca();
    
  }

  ngOnInit(): void {
  }

  obtenerRelmarca() {
    this.httpClient.get(environment.api_url + 'Productos/obtenerRelProductosSolucion').subscribe((data: any[]) =>  {
      for (let i = 0; i < data.length; i++) {
        this.Relmarcas.push(new Relmarca(data[i]));
      }      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

  enviar(values:any) {
    const formData = new FormData();
    formData.append('Unit', values['Unit']);
    formData.append('SKU', values['SKU']);
    formData.append('Description', values['Description']);
    formData.append('Price', values['Price']);
    formData.append('Contract_1Yr', values['Contract_1Yr']);
    formData.append('Contract_2Yr', values['Contract_2Yr']);
    formData.append('Contract_3Yr', values['Contract_3Yr']);
    formData.append('Contract_4Yr', values['Contract_4Yr']);
    formData.append('Contract_5Yr', values['Contract_5Yr']);
    formData.append('Comments', values['Comments']);
    formData.append('Category', values['Category']);
    formData.append('idrel', values['idrel']);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    // console.log(this.proyectoAceptado.idUsuarioLogeado); //imprimo el usuario logeado
    this.httpClient.post(environment.api_url + 'Productos/CrearProducto',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.nuProducto.emit(true);
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

}
