import { Component, OnInit , EventEmitter, Output} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import{ VariablesService} from 'src/app/services/variables.service';
import { Departamento} from 'src/app/models/departamento';


@Component({
  selector: 'app-editardepartamento',
  templateUrl: './editardepartamento.component.html',
  styleUrls: ['./editardepartamento.component.scss']
})
export class EditardepartamentoComponent implements OnInit {
  idDepartamento:string;
  Departamentos:Departamento;
  Departamento: FormGroup;
  @Output() nuDepartamento = new EventEmitter<boolean>();
  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder,private variablesService: VariablesService) {
    this.idDepartamento=this.variablesService.idDepartamento.toString();
    this.Departamento = formBuilder.group({
      nombreDepartamento: ['', Validators.compose([Validators.required])]
    });
    this.obtenerNombreDepartamento();
   }

  ngOnInit(): void {
  }
  obtenerNombreDepartamento() {
    const formData = new FormData();
    formData.append('idDepartamento', this.idDepartamento);
    this.httpClient.post(environment.api_url + 'Departamentos/obtenerDepartamentoxId',formData).subscribe((data: any[]) =>  {
      this.Departamentos = new Departamento(data);
      this.Departamento.controls['nombreDepartamento'].setValue(this.Departamentos.nombreDepartamento);
      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }
  enviar(values:any) {
    const formData = new FormData();
    formData.append('idDepartamento',this.idDepartamento);
    formData.append('nombreDepartamento', values['nombreDepartamento']);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    this.httpClient.post(environment.api_url + 'Departamentos/ActualizarDepartamento',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.nuDepartamento.emit(true);
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }
}
