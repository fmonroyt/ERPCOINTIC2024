import { Component, OnInit,ViewChild,EventEmitter,AfterViewInit } from '@angular/core';
import{ Departamento} from 'src/app/models/departamento';
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
  selector: 'app-departamentos',
  templateUrl: './departamentos.component.html',
  styleUrls: ['./departamentos.component.scss']
})
export class DepartamentosComponent implements AfterViewInit {
  @ViewChild(MatTable) tablaDepartamentos: MatTable<any>;
  @ViewChild('paginator1', { static: true }) paginator1: MatPaginator;
  @ViewChild(MatSort) sort1: MatSort;
  departamentos:Departamento[]=[];
  departamentoDatabase: DepartamentoDatabase | null;
  resultsLength1 = 0;
  pageSize1 = 5;
  isLoadingResults1 = true;
  isRateLimitReached1 = false;
  departamentoUsuario:number;
  columnasTablaDepartamento: string[] = ["nombreDepartamento","Editar","Quitar"];
  constructor(private httpClient: HttpClient,
    private variablesService: VariablesService,
    private notificaciones: NotificacionService) { 
      this.departamentoUsuario = jwtDecode(localStorage.getItem('jwt-admin'))['data']['idDepartamento'];
      this.departamentoDatabase = new DepartamentoDatabase(this.httpClient);
    }

    ngAfterViewInit() :void {
      this.buscarDepartamentos();
    }

    buscarDepartamentos() {
      this.departamentos = [];
      this.sort1.sortChange.subscribe(() => (this.paginator1.pageIndex = 0));
      merge(this.sort1.sortChange, this.paginator1.page)
        .pipe(
          startWith({}),
          switchMap(() => {
            this.departamentoDatabase!.getNumberOfDepartamento(
              this.sort1.active,
              this.sort1.direction,
              this.paginator1.pageIndex,
              this.pageSize1
              // filtro1
            ).subscribe((numero) => {
              this.resultsLength1 = numero;
            });
            return this.departamentoDatabase!.getDepartamento(
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
        .subscribe((data: Departamento[]) => (this.departamentos = data));
      this.tablaDepartamentos.renderRows();
    }
  
  
  
    establecerIdDepartamento(idDepartamento){
      this.variablesService.idDepartamento=idDepartamento;
    }
  
    eliminarDepartamento(idDepartamento) {
      const formData = new FormData();
      formData.append('idDepartamento', idDepartamento);
      formData.append('jwt', localStorage.getItem('jwt-admin'));
      this.httpClient.post(environment.api_url + 'Departamentos/EliminarDepartamento',formData).subscribe((data: any[]) =>  {
        this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
        this.buscarDepartamentos();
        
      }, error => {
        this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
      });  
    }

}

export class DepartamentoDatabase {
  constructor(private _httpClient: HttpClient) { }

  getDepartamento(
    sort1: string,
    order1: string,
    page1: number,
    pageSize1: number,
    // busqueda1: string,
  ): Observable<Departamento[]> {
    const formData = new FormData();
    formData.append("jwt", localStorage.getItem("jwt-admin"));
    // formData.append("filtro", busqueda1);
    formData.append("sort", sort1);
    formData.append("order", order1);
    formData.append("page", page1.toString());
    formData.append("pageSize", pageSize1.toString());
    return this._httpClient.post<Departamento[]>(
      environment.api_url + "Departamentos/obtenerDepartamentos",
      formData
    );
  }

  getNumberOfDepartamento(
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
      environment.api_url + "Departamentos/obtenerNumeroDepartamentos",
      formData
    );
  }
}
