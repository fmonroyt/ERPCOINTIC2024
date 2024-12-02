import { Component, OnInit , EventEmitter, Output} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import{ VariablesService} from 'src/app/services/variables.service';
import { Watchguard} from 'src/app/models/watchguard.model';
import { Relmarca} from 'src/app/models/relmarca.model';


@Component({
  selector: 'app-editarwatch',
  templateUrl: './editarwatch.component.html',
  styleUrls: ['./editarwatch.component.scss']
})

export class EditarwatchComponent implements OnInit {
  idproduct_Watchguard:string;
  Relmarcas:Relmarca[]=[];
  Watchguards:Watchguard;
  Watchguard: FormGroup;
  @Output() nuWatchguard = new EventEmitter<boolean>();
 

  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder,private variablesService: VariablesService) {
    this.idproduct_Watchguard=this.variablesService.idproduct_Watchguard.toString();
    this.Watchguard = formBuilder.group({
     
      SKU: ['', Validators.compose([Validators.required])],
      Description: ['', Validators.compose([Validators.required])],
      Precio: [''],
      Category: ['', Validators.compose([Validators.required])],
      idrel: ['', Validators.compose([Validators.required])],
    });
    this.obtenerNombreProducto();
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

  obtenerNombreProducto() {
    const formData = new FormData();
    formData.append('idproduct_Watchguard', this.idproduct_Watchguard);
    this.httpClient.post(environment.api_url + 'Watchguards/obtenerProductoxId',formData).subscribe((data: any[]) =>  {
      this.Watchguards = new Watchguard(data);
      this.Watchguard.controls['SKU'].setValue(this.Watchguards.SKU);
      this.Watchguard.controls['Description'].setValue(this.Watchguards.Description);
      this.Watchguard.controls['Precio'].setValue(this.Watchguards.Precio);
      this.Watchguard.controls['Category'].setValue(this.Watchguards.Category);
      this.Watchguard.controls['idrel'].setValue(this.Watchguards.idrel);
     
      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

  enviar(values:any) {
    const formData = new FormData();
    formData.append('idproduct_Watchguard',this.idproduct_Watchguard);
   
    formData.append('SKU', values['SKU']);
    formData.append('Description', values['Description']);
    formData.append('Precio', values['Precio']);
    formData.append('Category', values['Category']);  
    formData.append('idrel', values['idrel']);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    this.httpClient.post(environment.api_url + 'Watchguards/ActualizarProducto',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.nuWatchguard.emit(true);
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

}
