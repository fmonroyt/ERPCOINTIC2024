import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormBuilder } from "@angular/forms";
import { environment } from 'src/environments/environment';
import { NotificacionService } from 'src/app/services/notificacion.service';
import { Relmarca} from 'src/app/models/relmarca.model';
import { Producto } from 'src/app/models/producto.model';


@Component({
  selector: 'app-agregar-excel',
  templateUrl: './agregar-excel.component.html',
  styleUrls: ['./agregar-excel.component.scss']
})
export class AgregarExcelComponent implements OnInit {
  Relmarcas:Relmarca[]=[];
  Productos:Producto[]=[];
  @Output() nuProducto = new EventEmitter<boolean>();
  // Producto: FormGroup;
  archivos: File[] = [];
  constructor(private httpClient: HttpClient, private notificaciones: NotificacionService,formBuilder: FormBuilder) {

   }

  ngOnInit(): void {
    // this.Producto = new FormGroup({
    //   archivo: new FormControl(null, Validators.required) 
    // });
  }

  seleccionarArchivo(event) {
    if (this.archivos.length) { // para que solo exista un archivo
        this.archivos = [];
    }
    this.archivos.push(...event.addedFiles);

}

removerArchivo(event) {
  this.archivos.splice(this.archivos.indexOf(event), 1);
}

enviar() {
  if(this.archivos.length==0){
    this.notificaciones.crearNotificacion('Agrega un archivo, pendejo(a).', "fa fa-times", "error");
  }else{
  const formData = new FormData();
  for ( let i = 0; i < this.archivos.length; i++) {
     formData.append('jwt', localStorage.getItem('jwt-admin'));
    formData.append('archivo', this.archivos[i]);
}
  this.httpClient.post(environment.api_url + 'Productos/LeerExcel',formData).subscribe((data: any[]) =>  {
    this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
    this.nuProducto.emit(true);
  }, error => {
    this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
  });  
}
}

}
