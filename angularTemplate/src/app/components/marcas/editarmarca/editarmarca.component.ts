import { Component, OnInit , EventEmitter, Output} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import{ VariablesService} from 'src/app/services/variables.service';
import { Marca} from 'src/app/models/marca.model';

@Component({
  selector: 'app-editarmarca',
  templateUrl: './editarmarca.component.html',
  styleUrls: ['./editarmarca.component.scss']
})
export class EditarmarcaComponent implements OnInit {
  idMarcas:string;
  Marcas:Marca;
  Marca: FormGroup;
  @Output() nuMarca = new EventEmitter<boolean>();
  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder,private variablesService: VariablesService) {
    this.idMarcas=this.variablesService.idMarcas.toString();
    this.Marca = formBuilder.group({
      Nombre: ['', Validators.compose([Validators.required])]
    });
    this.obtenerNombreMarca();
   }

  ngOnInit(): void {
  }
  
  obtenerNombreMarca() {
    const formData = new FormData();
    formData.append('idMarcas', this.idMarcas);
    this.httpClient.post(environment.api_url + 'Marcas/obtenerMarcaxId',formData).subscribe((data: any[]) =>  {
      this.Marcas = new Marca(data);
      this.Marca.controls['Nombre'].setValue(this.Marcas.Nombre);
      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }
  enviar(values:any) {
    const formData = new FormData();
    formData.append('idMarcas',this.idMarcas);
    formData.append('Nombre', values['Nombre']);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    this.httpClient.post(environment.api_url + 'Marcas/ActualizarMarca',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.nuMarca.emit(true);
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }
}
