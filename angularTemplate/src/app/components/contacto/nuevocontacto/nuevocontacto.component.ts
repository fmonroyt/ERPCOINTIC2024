import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import { Relmarca} from 'src/app/models/relmarca.model';
import { Contacto } from 'src/app/models/contacto.model';
import { Empresa } from 'src/app/models/empresa.model';

@Component({
  selector: 'app-nuevocontacto',
  templateUrl: './nuevocontacto.component.html',
  styleUrls: ['./nuevocontacto.component.scss']
})
export class NuevocontactoComponent implements OnInit {


  Contactos:Contacto[]=[];
  Empresas:Empresa[]=[];
  @Output() nuContacto= new EventEmitter<boolean>();
  Contacto: FormGroup;

  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder) {
    this.Contacto = formBuilder.group({

      nombre: ['', Validators.compose([Validators.required])],
      apellido: ['', Validators.compose([Validators.required])],
      correo: ['', Validators.compose([Validators.required])],
      telefono: ['', Validators.compose([Validators.required])],
      idempresaext: ['', Validators.compose([Validators.required])],
    });
    this.obtenerempresaext();
  }

    ngOnInit(): void {
    }

    obtenerempresaext() {
      this.httpClient.get(environment.api_url + 'Contactos/obtenerIdempresaext').subscribe((data: any[]) =>  {
        for (let i = 0; i < data.length; i++) {
          this.Empresas.push(new Empresa(data[i]));
        }      
      }, error => {
        this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
      });  
    }
    
  enviar(values:any) {
    const formData = new FormData();
    formData.append('nombre', values['nombre']);
    formData.append('apellido', values['apellido']);
    formData.append('correo', values['correo']);
    formData.append('telefono', values['telefono']);
    formData.append('idempresaext', values['idempresaext']);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    // console.log(this.proyectoAceptado.idUsuarioLogeado); //imprimo el usuario logeado
    this.httpClient.post(environment.api_url + 'Contactos/CrearContacto',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.nuContacto.emit(true);
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }
  

}
