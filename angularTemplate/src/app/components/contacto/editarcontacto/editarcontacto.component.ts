import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormBuilder, Validators } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import { VariablesService } from 'src/app/services/variables.service';
import { Empresa } from 'src/app/models/empresa.model';
import { Contacto} from 'src/app/models/contacto.model';

@Component({
  selector: 'app-editarcontacto',
  templateUrl: './editarcontacto.component.html',
  styleUrls: ['./editarcontacto.component.scss']
})
export class EditarcontactoComponent implements OnInit {

  idcontacto: string;
  Empresas: Empresa[] = [];
  Contactos:Contacto;
  Contacto: FormGroup;
  selected:number=0;

  @Output() nuContacto = new EventEmitter<boolean>();

  constructor(
    private httpClient: HttpClient,
    private notificaciones: NotificacionService,
    private formBuilder: FormBuilder,
    private variablesService: VariablesService
  ) {
    this.idcontacto = this.variablesService.idcontacto ? this.variablesService.idcontacto.toString() : ''; // Asegura que idEmpresaExt nunca sea nulo o undefined
    this.Contacto = this.formBuilder.group({
      nombre: ['', [Validators.required]],
      apellido: ['', [Validators.required]],
      correo: [''],
      telefono: ['', [Validators.required, Validators.pattern('^[0-9]+$')]],
      idempresaext: ['', [Validators.required]],
    });
    this.obtenerEmpresas();
  }
  ngOnInit(): void {
  }
  obtenerEmpresas() {
    this.httpClient.get(environment.api_url + 'Contactos/obtenerIdempresaext').subscribe((data: any[]) =>  {
      for (let i = 0; i < data.length; i++) {
        this.Empresas.push(new Empresa(data[i]));
      }      
      this.obtenerNombreContacto();
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

  obtenerNombreContacto() {
    const formData = new FormData();
    formData.append('idcontacto', this.idcontacto);
    this.httpClient.post(environment.api_url + 'Contactos/obtenerContactos',formData).subscribe((data: any[]) =>  {
      this.Contactos = new Contacto(data);
      this.Contacto.controls['nombre'].setValue(this.Contactos.nombre);
      this.Contacto.controls['apellido'].setValue(this.Contactos.apellido);
      this.Contacto.controls['correo'].setValue(this.Contactos.correo);
      this.Contacto.controls['telefono'].setValue(this.Contactos.telefono);
      this.selected=this.Contactos.idempresaext;
      // console.log(this.Empresas.idusuario);

    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

  enviar(values: any) {

    console.log(values);
    const formData = new FormData();
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    formData.append('idcontacto', this.idcontacto);
    formData.append('nombre', values['nombre']);
    formData.append('apellido', values['apellido']);
    formData.append('correo', values['correo']);
    formData.append('telefono', values['telefono']);
    formData.append('idempresaext', this.selected.toString());
    this.httpClient.post<any>(environment.api_url + 'Contactos/ActualizarContactos', formData).subscribe(
      (data) => {
        this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
        this.nuContacto.emit(true);  // Emitir evento de éxito
      },
      (error) => {
        this.notificaciones.crearNotificacion(error?.error?.message || 'Error desconocido', "fa fa-times", "error");
      }
    );
  }
}
