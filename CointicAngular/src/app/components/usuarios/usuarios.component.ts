import { Component, OnInit, ViewChild,EventEmitter,AfterViewInit } from '@angular/core';
import{ Usuario} from 'src/app/models/usuario'
import {  HttpClient  } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import { MatTable } from '@angular/material/table';
import { MatPaginator } from "@angular/material/paginator";
import { MatSort } from "@angular/material/sort";
import { merge, Observable, of as observableOf } from "rxjs";
import { startWith, switchMap, map, catchError } from "rxjs/operators";
import{ VariablesService} from 'src/app/services/variables.service';
import { NotificacionService } from 'src/app/services/notificacion.service';
import * as jwtDecode from 'jwt-decode';
@Component({
  selector: 'app-usuarios',
  templateUrl: './usuarios.component.html',
  styleUrls: ['./usuarios.component.scss']
})
export class UsuariosComponent implements AfterViewInit {
  @ViewChild(MatTable) tablaUsuarios: MatTable<any>;
  @ViewChild('paginator1', { static: true }) paginator1: MatPaginator;
  @ViewChild(MatSort) sort1: MatSort;
  usuarios:Usuario[]=[];
  usuariosDatabase: UsuarioDatabase | null;
  resultsLength1 = 0;
  pageSize1 = 5;
  isLoadingResults1 = true;
  isRateLimitReached1 = false;
  UsuarioActivo:number;
  columnasTablaUsuario: string[] = ["nombreUsuario","Nickname","Departamento","Puesto","Editar","Desactivar"];
  constructor(
    private httpClient: HttpClient,
    private variablesService: VariablesService,
    private notificaciones: NotificacionService) { 
      this.UsuarioActivo = jwtDecode(localStorage.getItem('jwt-admin'))['data']['idUsuario'];
      this.usuariosDatabase = new UsuarioDatabase(this.httpClient);
  }

  ngAfterViewInit() :void {
    this.buscarUsuarios();
    
  }
  buscarUsuarios() {
    this.usuarios = [];
    this.sort1.sortChange.subscribe(() => (this.paginator1.pageIndex = 0));
    merge(this.sort1.sortChange, this.paginator1.page)
      .pipe(
        startWith({}),
        switchMap(() => {
          this.usuariosDatabase!.getNumberOfUsuario(
            this.sort1.active,
            this.sort1.direction,
            this.paginator1.pageIndex,
            this.pageSize1
            // filtro1
          ).subscribe((numero) => {
            this.resultsLength1 = numero;
          });
          return this.usuariosDatabase!.getUsuarios(
            this.sort1.active,
            this.sort1.direction,
            this.paginator1.pageIndex,
            this.pageSize1
            // filtro1
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
      .subscribe((data: Usuario[]) => (this.usuarios = data));
    this.tablaUsuarios.renderRows();
   
  }



  establecerIdUsuario(idUsuario){
    this.variablesService.idUsuario=idUsuario;
  }

  cambiarStatusUsuario(idUsuario,Status) {
    let newstatus;
    if(Status==0){
      newstatus=1;
    }
    else{
      newstatus=0;
    }
    const formData = new FormData();
    formData.append('idUsuario', idUsuario);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    formData.append('newstatus', newstatus);
    this.httpClient.post(environment.api_url + 'Usuarios/cambiarStatusUsuarios',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.buscarUsuarios();
      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }
}
export class UsuarioDatabase {
  constructor(private _httpClient: HttpClient) { }

  getUsuarios(
    sort1: string,
    order1: string,
    page1: number,
    pageSize1: number,
    // busqueda1: string,
  ): Observable<Usuario[]> {
    const formData = new FormData();
    formData.append("jwt", localStorage.getItem("jwt-admin"));
    // formData.append("filtro", busqueda1);
    formData.append("sort", sort1);
    formData.append("order", order1);
    formData.append("page", page1.toString());
    formData.append("pageSize", pageSize1.toString());
    return this._httpClient.post<Usuario[]>(
      environment.api_url + "Usuarios/getUsuarios",
      formData
    );
  }

  getNumberOfUsuario(
    sort1: string,
    order1: string,
    page1: number,
    pageSize1: number,
    // busqueda1: string,
  ): Observable<number> {
    const formData = new FormData();
    formData.append("jwt", localStorage.getItem("jwt-admin"));
    // formData.append("filtro", busqueda1);
    formData.append("sort", sort1);
    formData.append("order", order1);
    formData.append("page", page1.toString());
    formData.append("pageSize", pageSize1.toString());
    return this._httpClient.post<number>(
      environment.api_url + "Usuarios/getUsuariosNumeros",
      formData
    );
  }
}
