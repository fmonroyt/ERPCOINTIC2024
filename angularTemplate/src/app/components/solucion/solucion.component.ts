import { Component, OnInit } from '@angular/core';
import { ViewChild,EventEmitter,AfterViewInit } from '@angular/core';
import{Solucion } from 'src/app/models/solucion.model';
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
  selector: 'app-solucion',
  templateUrl: './solucion.component.html',
  styleUrls: ['./solucion.component.scss']
})
export class SolucionComponent implements AfterViewInit {
  @ViewChild(MatTable) tablaSoluciones: MatTable<any>;
  @ViewChild('paginator1', { static: true }) paginator1: MatPaginator;
  @ViewChild(MatSort) sort1: MatSort;
  soluciones:Solucion[]=[];
  solucionDatabase: SolucionDatabase | null;
  resultsLength1 = 0;
  pageSize1 = 5;
  isLoadingResults1 = true;
  isRateLimitReached1 = false;
  solucionUsuario:number;
  columnasTablaSolucion: string[] = ["nombre","Editar","Marcas","Quitar"];
 
  constructor(private httpClient: HttpClient,
    private variablesService: VariablesService,
    private notificaciones: NotificacionService) {
      this.solucionUsuario = jwtDecode(localStorage.getItem('jwt-admin'))['data']['idSolucion'];
      this.solucionDatabase = new SolucionDatabase(this.httpClient);
   
     }

     ngAfterViewInit(): void {
      this.buscarSoluciones();
  }

  buscarSoluciones() {
    this.soluciones = [];
    this.sort1.sortChange.subscribe(() => (this.paginator1.pageIndex = 0));
    merge(this.sort1.sortChange, this.paginator1.page)
      .pipe(
        startWith({}),
        switchMap(() => {
          this.solucionDatabase!.getNumberOfSolucion(
            this.sort1.active,
            this.sort1.direction,
            this.paginator1.pageIndex,
            this.pageSize1
            // filtro1
          ).subscribe((numero) => {
            this.resultsLength1 = numero;
          });
          return this.solucionDatabase!.getSolucion(
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
      .subscribe((data: Solucion[]) => (this.soluciones = data));
    this.tablaSoluciones.renderRows();
  }
  
  establecerIdSolucion(idsoluciones){
    this.variablesService.idsoluciones=idsoluciones;
  }

  eliminarSolucion(idsoluciones) {
    const formData = new FormData();
    formData.append('idsoluciones', idsoluciones);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    this.httpClient.post(environment.api_url + 'Soluciones/EliminarSolucion',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.buscarSoluciones();
      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

}


export class SolucionDatabase {
  constructor(private _httpClient: HttpClient) { }

  getSolucion(
    sort1: string,
    order1: string,
    page1: number,
    pageSize1: number,
    // busqueda1: string,
  ): Observable<Solucion[]> {
    const formData = new FormData();
    formData.append("jwt", localStorage.getItem("jwt-admin"));
    // formData.append("filtro", busqueda1);
    formData.append("sort", sort1);
    formData.append("order", order1);
    formData.append("page", page1.toString());
    formData.append("pageSize", pageSize1.toString());
    return this._httpClient.post<Solucion[]>(
      environment.api_url + "Soluciones/obtenerSoluciones",
      formData
    );
  }

  getNumberOfSolucion(
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
      environment.api_url + "Soluciones/obtenerNumeroSoluciones",
      formData
    );
  }
}
