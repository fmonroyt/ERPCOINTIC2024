import { Component, OnInit , EventEmitter, Output} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import{ VariablesService} from 'src/app/services/variables.service';
import { Producto} from 'src/app/models/producto.model';
import { Relmarca} from 'src/app/models/relmarca.model';

@Component({
  selector: 'app-editarproducto',
  templateUrl: './editarproducto.component.html',
  styleUrls: ['./editarproducto.component.scss']
})
export class EditarproductoComponent implements OnInit {
  idProducto_Fort:string;
  Relmarcas:Relmarca[]=[];
  Productos:Producto;
  Producto: FormGroup;
  @Output() nuProducto = new EventEmitter<boolean>();
  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder,private variablesService: VariablesService) { 
    this.idProducto_Fort=this.variablesService.idProducto_Fort.toString();
    this.Producto = formBuilder.group({
      Unit: ['', Validators.compose([Validators.required])],
      SKU: ['', Validators.compose([Validators.required])],
      Description: ['', Validators.compose([Validators.required])],
      Price: [''],
      Contract_1Yr: [''],
      Contract_2Yr: [''],
      Contract_3Yr: [''],
      Contract_4Yr: [''],
      Contract_5Yr: [''],
      Comments: [''],
      Category: ['', Validators.compose([Validators.required])],
      idrel: ['', Validators.compose([Validators.required])],
    });
    this.obtenerNombreProducto();
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

  obtenerNombreProducto() {
    const formData = new FormData();
    formData.append('idProducto_Fort', this.idProducto_Fort);
    this.httpClient.post(environment.api_url + 'Productos/obtenerProductoxId',formData).subscribe((data: any[]) =>  {
      this.Productos = new Producto(data);
      this.Producto.controls['Unit'].setValue(this.Productos.Unit);
      this.Producto.controls['SKU'].setValue(this.Productos.SKU);
      this.Producto.controls['Description'].setValue(this.Productos.Description);
      this.Producto.controls['Price'].setValue(this.Productos.Price);
      this.Producto.controls['Contract_1Yr'].setValue(this.Productos.Contract_1Yr);
      this.Producto.controls['Contract_2Yr'].setValue(this.Productos.Contract_2Yr);
      this.Producto.controls['Contract_3Yr'].setValue(this.Productos.Contract_3Yr);
      this.Producto.controls['Contract_4Yr'].setValue(this.Productos.Contract_4Yr);
      this.Producto.controls['Contract_5Yr'].setValue(this.Productos.Contract_5Yr);
      this.Producto.controls['Comments'].setValue(this.Productos.Comments);
      this.Producto.controls['Category'].setValue(this.Productos.Category);
      this.Producto.controls['idrel'].setValue(this.Productos.idrel);
      
      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }
  enviar(values:any) {
    const formData = new FormData();
    formData.append('idProducto_Fort',this.idProducto_Fort);
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
    this.httpClient.post(environment.api_url + 'Productos/ActualizarProducto',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.nuProducto.emit(true);
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

}
