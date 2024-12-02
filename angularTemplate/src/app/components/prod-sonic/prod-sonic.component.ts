import { Component, OnInit,ViewChild,EventEmitter,AfterViewInit } from '@angular/core';
import {Sonic} from 'src/app/models/sonic.model';
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
  selector: 'app-prod-sonic',
  templateUrl: './prod-sonic.component.html',
  styleUrls: ['./prod-sonic.component.scss']
})
export class ProdSonicComponent implements AfterViewInit {
  @ViewChild(MatTable) tablaProductosSonic: MatTable<any>;
  @ViewChild('paginator1', { static: true }) paginator1: MatPaginator;
  @ViewChild(MatSort) sort1: MatSort;
  sonics = new MatTableDataSource<Sonic>([]);
  // productos:Producto[]=[];
  Relmarcas:Relmarca[]=[];
  sonicDatabase: SonicDatabase | null;
  resultsLength1 = 0;
  pageSize1 = 5;
  isLoadingResults1 = true;
  isRateLimitReached1 = false;
  productoUsuario:number;
  columnasTablaProducto: string[] = ["SKU","Description","Editar","Quitar"];
 
  constructor(private httpClient: HttpClient,
    private variablesService: VariablesService,
    private notificaciones: NotificacionService) {
      this.productoUsuario = jwtDecode(localStorage.getItem('jwt-admin'))['data']['idproducto_Sonic'];
      this.sonicDatabase = new SonicDatabase(this.httpClient);
     }

  ngAfterViewInit(): void {
    this.buscarProductos("");
    this.obtenerRelmarca();
  }

  aplicarFiltro(valor: string) {
    this.sonics.filter = valor.trim().toLowerCase();  // Aplica el filtro en la tabla
  }

  obtenerRelmarca() {
    this.httpClient.get(environment.api_url + 'Sonics/obtenerRelProductosSolucion').subscribe((data: any[]) =>  {
      for (let i = 0; i < data.length; i++) {
        this.Relmarcas.push(new Relmarca(data[i]));
      }      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

  buscarProductos(filtro1) {
    this.sonics = new MatTableDataSource([]);
    this.sort1.sortChange.subscribe(() => (this.paginator1.pageIndex = 0));
    merge(this.sort1.sortChange, this.paginator1.page)
      .pipe(
        startWith({}),
        switchMap(() => {
          this.sonicDatabase!.getNumberOfProducto(
            this.sort1.active,
            this.sort1.direction,
            this.paginator1.pageIndex,
            this.pageSize1,
            filtro1
          ).subscribe((numero) => {
            this.resultsLength1 = numero;
          });
          return this.sonicDatabase!.getProducto(
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
      .subscribe((data: Sonic[]) => (this.sonics.data = data));
    this.tablaProductosSonic.renderRows();
  }
  establecerIdProducto_Sonic(idproducto_Sonic){
    this.variablesService.idproducto_Sonic=idproducto_Sonic;
  }

  eliminarProducto(idproducto_Sonic) {
    const formData = new FormData();
    formData.append('idproducto_Sonic', idproducto_Sonic);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    this.httpClient.post(environment.api_url + 'Sonics/EliminarProducto',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.buscarProductos("");
      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

}


export class SonicDatabase {
  constructor(private _httpClient: HttpClient) { }

  getProducto(
    sort1: string,
    order1: string,
    page1: number,
    pageSize1: number,
    busqueda1: string,
  ): Observable<Sonic[]> {
    const formData = new FormData();
    formData.append("jwt", localStorage.getItem("jwt-admin"));
    formData.append("filtro", busqueda1);
    formData.append("sort", sort1);
    formData.append("order", order1);
    formData.append("page", page1.toString());
    formData.append("pageSize", pageSize1.toString());
    return this._httpClient.post<Sonic[]>(
      environment.api_url + "Sonics/obtenerProductos",
      formData
    );
  }

  getNumberOfProducto(
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
      environment.api_url + "Sonics/obtenerNumeroProductos",
      formData
    );
  }
}

