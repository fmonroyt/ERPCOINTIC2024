import { Component, OnInit,ViewChild,EventEmitter,AfterViewInit } from '@angular/core';
import { Marca } from 'src/app/models/marca.model';
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
  selector: 'app-marcas',
  templateUrl: './marcas.component.html',
  styleUrls: ['./marcas.component.scss']
})
export class MarcasComponent implements AfterViewInit {
  @ViewChild(MatTable) tablaMarcas: MatTable<any>;
  @ViewChild('paginator1', { static: true }) paginator1: MatPaginator;
  @ViewChild(MatSort) sort1: MatSort;
  marcas:Marca[]=[];
  marcaDatabase: MarcaDatabase | null;
  resultsLength1 = 0;
  pageSize1 = 5;
  isLoadingResults1 = true;
  isRateLimitReached1 = false;
  marcaUsuario:number;
  columnasTablaMarca: string[] = ["Nombre","Editar","Quitar"];

  constructor(private httpClient: HttpClient,
    private variablesService: VariablesService,
    private notificaciones: NotificacionService) { 
      this.marcaUsuario = jwtDecode(localStorage.getItem('jwt-admin'))['data']['idMarca'];
      this.marcaDatabase = new MarcaDatabase(this.httpClient);
    }

    ngAfterViewInit(): void {
      this.buscarMarcas();
  }

  
  buscarMarcas() {
    this.marcas = [];
    this.sort1.sortChange.subscribe(() => (this.paginator1.pageIndex = 0));
    merge(this.sort1.sortChange, this.paginator1.page)
      .pipe(
        startWith({}),
        switchMap(() => {
          this.marcaDatabase!.getNumberOfMarca(
            this.sort1.active,
            this.sort1.direction,
            this.paginator1.pageIndex,
            this.pageSize1
            // filtro1
          ).subscribe((numero) => {
            this.resultsLength1 = numero;
          });
          return this.marcaDatabase!.getMarca(
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
      .subscribe((data: Marca[]) => (this.marcas = data));
    this.tablaMarcas.renderRows();
  }
  
  establecerIdMarca(idMarcas){
    this.variablesService.idMarcas=idMarcas;
  }

  eliminarMarca(idMarcas) {
    const formData = new FormData();
    formData.append('idMarcas', idMarcas);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    this.httpClient.post(environment.api_url + 'Marcas/EliminarMarca',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.buscarMarcas();
      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }
}


export class MarcaDatabase {
  constructor(private _httpClient: HttpClient) { }

  getMarca(
    sort1: string,
    order1: string,
    page1: number,
    pageSize1: number,
    // busqueda1: string,
  ): Observable<Marca[]> {
    const formData = new FormData();
    formData.append("jwt", localStorage.getItem("jwt-admin"));
    // formData.append("filtro", busqueda1);
    formData.append("sort", sort1);
    formData.append("order", order1);
    formData.append("page", page1.toString());
    formData.append("pageSize", pageSize1.toString());
    return this._httpClient.post<Marca[]>(
      environment.api_url + "Marcas/obtenerMarcas",
      formData
    );
  }

  getNumberOfMarca(
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
      environment.api_url + "Marcas/obtenerNumeroMarcas",
      formData
    );
  }
}
