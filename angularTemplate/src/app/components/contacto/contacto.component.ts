import { Component, OnInit,ViewChild,EventEmitter,AfterViewInit } from '@angular/core';
import {Contacto} from 'src/app/models/contacto.model';
import {  HttpClient  } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import { MatTable } from '@angular/material/table';
import { MatPaginator } from "@angular/material/paginator";
import { MatSort } from "@angular/material/sort";
import { merge, Observable, of as observableOf } from "rxjs";
import { startWith, switchMap, map, catchError } from "rxjs/operators";
import{ VariablesService} from 'src/app/services/variables.service';
import { NotificacionService } from 'src/app/services/notificacion.service';
import { Relmarca} from 'src/app/models/relmarca.model';
import { MatTableDataSource } from '@angular/material/table';
import * as jwtDecode from 'jwt-decode';

@Component({
  selector: 'app-contacto',
  templateUrl: './contacto.component.html',
  styleUrls: ['./contacto.component.scss']
})
export class ContactoComponent implements AfterViewInit {
  @ViewChild(MatTable) tablaContactos: MatTable<any>;
  @ViewChild('paginator1', { static: true }) paginator1: MatPaginator;
  @ViewChild(MatSort) sort1: MatSort;
  contactos = new MatTableDataSource<Contacto>([]);
  // productos:Producto[]=[];
  Relmarcas:Relmarca[]=[];
  contactoDatabase: ContactoDatabase | null;
  resultsLength1 = 0;
  pageSize1 = 5;
  isLoadingResults1 = true;
  isRateLimitReached1 = false;
  contactoUsuario:number;
  columnasTablaProductoContacto: string[] = ["nombre","telefono","Editar","Quitar"];
 


  constructor(private httpClient: HttpClient,
    private variablesService: VariablesService,
    private notificaciones: NotificacionService) {
      this.contactoUsuario = jwtDecode(localStorage.getItem('jwt-admin'))['data']['idcontacto'];
      this.contactoDatabase = new ContactoDatabase(this.httpClient);
   
     }

  ngAfterViewInit(): void {
    this.buscarContactos("");
    this.obtenerIdempresaext();
  }

  
  aplicarFiltro(valor: string) {
    this.contactos.filter = valor.trim().toLowerCase();  // Aplica el filtro en la tabla
  }

  obtenerIdempresaext() {
    this.httpClient.get(environment.api_url + 'Contactos/obtenerIdempresaext').subscribe((data: any[]) =>  {
      for (let i = 0; i < data.length; i++) {
        this.Relmarcas.push(new Relmarca(data[i]));
      }      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }
  
  
  buscarContactos(filtro1) {
    this.contactos = new MatTableDataSource([]);
    this.sort1.sortChange.subscribe(() => (this.paginator1.pageIndex = 0));
    merge(this.sort1.sortChange, this.paginator1.page)
      .pipe(
        startWith({}),
        switchMap(() => {
          this.contactoDatabase!.getNumberOfContacto(
            this.sort1.active,
            this.sort1.direction,
            this.paginator1.pageIndex,
            this.pageSize1,
            filtro1
          ).subscribe((numero) => {
            this.resultsLength1 = numero;
          });
          return this.contactoDatabase!.getContacto(
            this.sort1.active,
            this.sort1.direction,
            this.paginator1.pageIndex,
            this.pageSize1,
            filtro1
          );
        }),
        map((data) => {
          this.isLoadingResults1 = false;
          this.isRateLimitReached1 = false;
          return data;
        }),
        catchError(() => {
          this.isLoadingResults1 = false;
          this.isRateLimitReached1 = true;
          return observableOf([]);
        })
      )
      .subscribe((data: Contacto[]) => (this.contactos.data = data));
    this.tablaContactos.renderRows();
  }

  
  establecerIdProducto_Contacto(idcontacto){
    this.variablesService.idcontacto=idcontacto;
  }


  eliminarContacto(idcontacto) {
    const formData = new FormData();
    formData.append('idcontacto', idcontacto);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    this.httpClient.post(environment.api_url + 'Contactos/EliminarContacto',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.buscarContactos("");
      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

}


export class ContactoDatabase {
  constructor(private _httpClient: HttpClient) { }

  getContacto(
    sort1: string,
    order1: string,
    page1: number,
    pageSize1: number,
    busqueda1: string,
  ): Observable<Contacto[]> {
    const formData = new FormData();
    formData.append("jwt", localStorage.getItem("jwt-admin"));
    formData.append("filtro", busqueda1);
    formData.append("sort", sort1);
    formData.append("order", order1);
    formData.append("page", page1.toString());
    formData.append("pageSize", pageSize1.toString());
    return this._httpClient.post<Contacto[]>(
      environment.api_url + "Contactos/obtenerContacto",
      formData
    );
  }

  getNumberOfContacto(
    sort1: string,
    order1: string,
    page1: number,
    pageSize1: number,
    busqueda1: string,
  ): Observable<number> {
    const formData = new FormData();
    formData.append("jwt", localStorage.getItem("jwt-admin"));
    formData.append("filtro", busqueda1);
    formData.append("sort", sort1);
    formData.append("order", order1);
    formData.append("page", page1.toString());
    formData.append("pageSize", pageSize1.toString());
    return this._httpClient.post<number>(
      environment.api_url + "Contactos/obtenerNumeroContacto",
      formData
    );
  }
}


