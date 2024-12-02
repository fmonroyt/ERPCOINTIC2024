import { Component, OnInit , EventEmitter, Output} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import{ VariablesService} from 'src/app/services/variables.service';
import { Sonic} from 'src/app/models/sonic.model';
import { Relmarca} from 'src/app/models/relmarca.model';

@Component({
  selector: 'app-editarsonic',
  templateUrl: './editarsonic.component.html',
  styleUrls: ['./editarsonic.component.scss']
})
export class EditarsonicComponent implements OnInit {
  idproducto_Sonic:string;
  Relmarcas:Relmarca[]=[];
  Sonics:Sonic;
  Sonic: FormGroup;
  @Output() nuSonic = new EventEmitter<boolean>();
 
  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder,private variablesService: VariablesService) {
    this.idproducto_Sonic=this.variablesService.idproducto_Sonic.toString();
    this.Sonic = formBuilder.group({
      // Unit: ['', Validators.compose([Validators.required])],
      SKU: ['', Validators.compose([Validators.required])],
      Description: ['', Validators.compose([Validators.required])],
      Precio: [''],
      // Contract_1Yr: [''],
      // Contract_2Yr: [''],
      // Contract_3Yr: [''],
      // Contract_4Yr: [''],
      // Contract_5Yr: [''],
      // Comments: [''],
      HardwareorSoftware: ['', Validators.compose([Validators.required])],
      idrel: ['', Validators.compose([Validators.required])],
    });
    this.obtenerNombreProducto();
    this.obtenerRelmarca();
   }

  ngOnInit(): void {
  }

  obtenerRelmarca() {
    this.httpClient.get(environment.api_url + 'Sonics/obtenerRelProductosSolucion').subscribe((data: any[]) =>  {
      for (let i = 0; i < data.length; i++) {
        this.Relmarcas.push(new Relmarca(data[i]));
      }      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

  
  obtenerNombreProducto() {
    const formData = new FormData();
    formData.append('idproducto_Sonic', this.idproducto_Sonic);
    this.httpClient.post(environment.api_url + 'Sonics/obtenerProductoxId',formData).subscribe((data: any[]) =>  {
      this.Sonics = new Sonic(data);
      // this.Producto.controls['Unit'].setValue(this.Productos.Unit);
      this.Sonic.controls['SKU'].setValue(this.Sonics.SKU);
      this.Sonic.controls['Description'].setValue(this.Sonics.Description);
      this.Sonic.controls['Precio'].setValue(this.Sonics.Precio);
      // this.Producto.controls['Contract_1Yr'].setValue(this.Productos.Contract_1Yr);
      // this.Producto.controls['Contract_2Yr'].setValue(this.Productos.Contract_2Yr);
      // this.Producto.controls['Contract_3Yr'].setValue(this.Productos.Contract_3Yr);
      // this.Producto.controls['Contract_4Yr'].setValue(this.Productos.Contract_4Yr);
      // this.Producto.controls['Contract_5Yr'].setValue(this.Productos.Contract_5Yr);
      // this.Producto.controls['Comments'].setValue(this.Productos.Comments);
      this.Sonic.controls['HardwareorSoftware'].setValue(this.Sonics.HardwareorSoftware);
      this.Sonic.controls['idrel'].setValue(this.Sonics.idrel);
      
      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

  
  enviar(values:any) {
    const formData = new FormData();
    formData.append('idproducto_Sonic',this.idproducto_Sonic);
    // formData.append('Unit', values['Unit']);
    formData.append('SKU', values['SKU']);
    formData.append('Description', values['Description']);
    formData.append('Precio', values['Precio']);
    formData.append('HardwareorSoftware', values['HardwareorSoftware']);
    formData.append('idrel', values['idrel']);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    this.httpClient.post(environment.api_url + 'Sonics/ActualizarProducto',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.nuSonic.emit(true);
      this.obtenerNombreProducto();
    this.obtenerRelmarca();
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

}
