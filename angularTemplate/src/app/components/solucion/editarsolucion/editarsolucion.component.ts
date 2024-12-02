import { Component, OnInit , EventEmitter, Output} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import{ VariablesService} from 'src/app/services/variables.service';
import { Solucion} from 'src/app/models/solucion.model';

@Component({
  selector: 'app-editarsolucion',
  templateUrl: './editarsolucion.component.html',
  styleUrls: ['./editarsolucion.component.scss']
})
export class EditarsolucionComponent implements OnInit {
  idsoluciones:string;
  Soluciones:Solucion;
  Solucion: FormGroup;
  @Output() nuSolucion = new EventEmitter<boolean>();
  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder,private variablesService: VariablesService) { 
    this.idsoluciones=this.variablesService.idsoluciones.toString();
    this.Solucion = formBuilder.group({
      nombre: ['', Validators.compose([Validators.required])]
    });
    this.obtenerNombreSolucion();
  }

  ngOnInit(): void {
  }
  obtenerNombreSolucion() {
    const formData = new FormData();
    formData.append('idsoluciones', this.idsoluciones);
    this.httpClient.post(environment.api_url + 'Soluciones/obtenerSolucionxId',formData).subscribe((data: any[]) =>  {
      this.Soluciones = new Solucion(data);
      this.Solucion.controls['nombre'].setValue(this.Soluciones.nombre);
      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }
  enviar(values:any) {
    const formData = new FormData();
    formData.append('idsoluciones',this.idsoluciones);
    formData.append('nombre', values['nombre']);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    this.httpClient.post(environment.api_url + 'Soluciones/ActualizarSolucion',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.nuSolucion.emit(true);
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

}
