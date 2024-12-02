import { Component, OnInit , EventEmitter, Output} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import{ VariablesService} from 'src/app/services/variables.service';
import { Marca} from 'src/app/models/marca.model';

@Component({
  selector: 'app-marcassol',
  templateUrl: './marcassol.component.html',
  styleUrls: ['./marcassol.component.scss']
})
export class MarcassolComponent implements OnInit {
  idsoluciones:string;
  MarcasAsignadas:Marca[]=[];
  Marcas:Marca[]=[];
  Marca: FormGroup;
  @Output() nuSolucion = new EventEmitter<boolean>();
  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder,private variablesService: VariablesService) {
    this.idsoluciones=this.variablesService.idsoluciones.toString();
    this.Marca = formBuilder.group({
      marcas: ['', Validators.compose([Validators.required])]
    });
    this.obtenerMarcas();
   
   }

  ngOnInit(): void {
  }

  obtenerMarcas() {
    const formData = new FormData();
    formData.append('idsoluciones', this.idsoluciones);
    this.httpClient.post(environment.api_url + 'Soluciones/ObtenerMarcas',formData).subscribe((data: any[]) =>  {
      for (let i = 0; i < data.length; i++) {
        this.Marcas.push(new Marca(data[i])); 
      }
      this.obtenerMarcasAsignadas();
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

  obtenerMarcasAsignadas() {
    const formData = new FormData();
    formData.append('idsoluciones', this.idsoluciones);
    this.httpClient.post(environment.api_url + 'Soluciones/ObtenerMarcasAsignadas',formData).subscribe((data: any[]) =>  {
      const Marcs = [];
      for (let i = 0; i < data.length; i++) {
        this.MarcasAsignadas.push(new Marca(data[i]));
        Marcs.push(data[i]['idMarcas']);
      }
      console.log(Marcs);
      this.Marca.controls['marcas'].setValue(Marcs);
      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

  AsignarMarcas() {
    const formData = new FormData();
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    formData.append('idsoluciones', this.idsoluciones);
    formData.append('Marcas', this.Marca.controls['marcas'].value);
    this.httpClient.post(environment.api_url + 'Soluciones/AsignarMarcas',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.nuSolucion.emit(true);
    }, error => {      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

}
