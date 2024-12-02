import { Component, OnInit,ViewChild,EventEmitter,AfterViewInit } from '@angular/core';
import {Empresa} from 'src/app/models/empresa.model';
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
  selector: 'app-empresa',
  templateUrl: './empresa.component.html',
  styleUrls: ['./empresa.component.scss']
})
export class EmpresaComponent implements AfterViewInit {
  @ViewChild(MatTable) tablaEmpresas: MatTable<any>;
  @ViewChild('paginator1', { static: true }) paginator1: MatPaginator;
  @ViewChild(MatSort) sort1: MatSort;
  empresas = new MatTableDataSource<Empresa>([]);
  // productos:Producto[]=[];
  Relmarcas:Relmarca[]=[];
  empresaDatabase: EmpresaDatabase | null;
  resultsLength1 = 0;
  pageSize1 = 5;
  isLoadingResults1 = true;
  isRateLimitReached1 = false;
  empresaUsuario:number;
  columnasTablaProductoEmpresa: string[] = ["nombre","telefono","Editar","Quitar"];
 

  constructor(private httpClient: HttpClient,
    private variablesService: VariablesService,
    private notificaciones: NotificacionService) {
      this.empresaUsuario = jwtDecode(localStorage.getItem('jwt-admin'))['data']['idEmpresaExt'];
      this.empresaDatabase = new EmpresaDatabase(this.httpClient);
   
     }

     ngAfterViewInit(): void {
      this.buscarEmpresas("");
      this.obtenerIdUsuario();
  }
  
  aplicarFiltro(valor: string) {
    this.empresas.filter = valor.trim().toLowerCase();  // Aplica el filtro en la tabla
  }

   obtenerIdUsuario() {
    this.httpClient.get(environment.api_url + 'EmpresaExts/obtenerIdUsuario').subscribe((data: any[]) =>  {
      for (let i = 0; i < data.length; i++) {
        this.Relmarcas.push(new Relmarca(data[i]));
      }      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }


  
  buscarEmpresas(filtro1) {
    this.empresas = new MatTableDataSource([]);
    this.sort1.sortChange.subscribe(() => (this.paginator1.pageIndex = 0));
    merge(this.sort1.sortChange, this.paginator1.page)
      .pipe(
        startWith({}),
        switchMap(() => {
          this.empresaDatabase!.getNumberOfEmpresa(
            this.sort1.active,
            this.sort1.direction,
            this.paginator1.pageIndex,
            this.pageSize1,
            filtro1
          ).subscribe((numero) => {
            this.resultsLength1 = numero;
          });
          return this.empresaDatabase!.getEmpresa(
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
      .subscribe((data: Empresa[]) => (this.empresas.data = data));
    this.tablaEmpresas.renderRows();
  }

  
  establecerIdProducto_Empresas(idEmpresaExt){
    this.variablesService.idEmpresaExt=idEmpresaExt;
  }

  eliminarEmpresa(idEmpresaExt) {
    const formData = new FormData();
    formData.append('idEmpresaExt', idEmpresaExt);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    this.httpClient.post(environment.api_url + 'EmpresaExts/EliminarEmpresaExt',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.buscarEmpresas("");
      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

}


export class EmpresaDatabase {
  constructor(private _httpClient: HttpClient) { }

  getEmpresa(
    sort1: string,
    order1: string,
    page1: number,
    pageSize1: number,
    busqueda1: string,
  ): Observable<Empresa[]> {
    const formData = new FormData();
    formData.append("jwt", localStorage.getItem("jwt-admin"));
    formData.append("filtro", busqueda1);
    formData.append("sort", sort1);
    formData.append("order", order1);
    formData.append("page", page1.toString());
    formData.append("pageSize", pageSize1.toString());
    return this._httpClient.post<Empresa[]>(
      environment.api_url + "EmpresaExts/obtenerEmpresaExt",
      formData
    );
  }

  getNumberOfEmpresa(
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
      environment.api_url + "EmpresaExts/obtenerNumeroEmpresaExt",
      formData
    );
  }
}




