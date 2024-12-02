 import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormBuilder, Validators } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import { VariablesService } from 'src/app/services/variables.service';
import { Usuario } from 'src/app/models/usuario';
import { Empresa} from 'src/app/models/empresa.model';

@Component({
  selector: 'app-editar-empresa',
  templateUrl: './editar-empresa.component.html',
  styleUrls: ['./editar-empresa.component.scss']
})

export class EditarEmpresaComponent implements OnInit {
  idEmpresaExt: string;
  Usuarios: Usuario[] = [];
  Empresas:Empresa;
  Empresa: FormGroup;

  @Output() nuEmpresa = new EventEmitter<boolean>();

  constructor(
    private httpClient: HttpClient,
    private notificaciones: NotificacionService,
    private formBuilder: FormBuilder,
    private variablesService: VariablesService
  ) {
    this.idEmpresaExt = this.variablesService.idEmpresaExt ? this.variablesService.idEmpresaExt.toString() : ''; // Asegura que idEmpresaExt nunca sea nulo o undefined
    this.Empresa = this.formBuilder.group({
      nombre: ['', [Validators.required]],
      telefono: ['', [Validators.required, Validators.pattern('^[0-9]+$')]], // Validación de teléfono numérico
      ciudad: [''],
      pais: ['', [Validators.required]],
      idusuario: ['', [Validators.required]]
    });
    // Cargar los datos iniciales
    this.obtenerNombreEmpresa();
    this.obtenerUsuarios();
  }

  ngOnInit(): void {
  }

  // Obtener los usuarios
  obtenerUsuarios() {
    this.httpClient.get(environment.api_url + 'EmpresaExts/obtenerIdUsuario').subscribe((data: any[]) =>  {
      for (let i = 0; i < data.length; i++) {
        this.Usuarios.push(new Usuario(data[i]));
      }      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

  // Obtener los detalles de la empresa
  obtenerNombreEmpresa() {
    const formData = new FormData();
    formData.append('idEmpresaExt', this.idEmpresaExt);
    this.httpClient.post(environment.api_url + 'EmpresaExts/obtenerEmpresasExt',formData).subscribe((data: any[]) =>  {
      this.Empresas = new Empresa(data);
      this.Empresa.controls['nombre'].setValue(this.Empresas.nombre);
      this.Empresa.controls['telefono'].setValue(this.Empresas.telefono);
      this.Empresa.controls['ciudad'].setValue(this.Empresas.ciudad);
      this.Empresa.controls['pais'].setValue(this.Empresas.pais);
      this.Empresa.controls['idusuario'].setValue(this.Empresas.idusuario);
      // console.log(this.Empresas.idusuario);
     
      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }


  // Enviar datos del formulario
  enviar(values: any) {
    const formData = new FormData();
    formData.append('idEmpresaExt', this.idEmpresaExt);
    formData.append('nombre', values['nombre']);
    formData.append('telefono', values['telefono']);
    formData.append('ciudad', values['ciudad']);
    formData.append('pais', values['pais']);
    formData.append('idusuario', values['idusuario']);
    formData.append('jwt', localStorage.getItem('jwt-admin') || '');

    this.httpClient.post<any>(environment.api_url + 'EmpresaExts/ActualizarEmpresaExt', formData).subscribe(
      (data) => {
        this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
        this.nuEmpresa.emit(true);  // Emitir evento de éxito
      },
      (error) => {
        this.notificaciones.crearNotificacion(error?.error?.message || 'Error desconocido', "fa fa-times", "error");
      }
    );
  }
}
