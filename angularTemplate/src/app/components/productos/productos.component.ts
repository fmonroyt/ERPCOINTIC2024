import { Component, OnInit,ViewChild,EventEmitter,AfterViewInit } from '@angular/core';
import {Producto} from 'src/app/models/producto.model';
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
  selector: 'app-productos',
  templateUrl: './productos.component.html',
  styleUrls: ['./productos.component.scss']
})
export class ProductosComponent implements AfterViewInit {
  @ViewChild(MatTable) tablaProductos: MatTable<any>;
  @ViewChild('paginator1', { static: true }) paginator1: MatPaginator;
  @ViewChild(MatSort) sort1: MatSort;
  productos = new MatTableDataSource<Producto>([]);
  // productos:Producto[]=[];
  Relmarcas:Relmarca[]=[];
  productoDatabase: ProductoDatabase | null;
  resultsLength1 = 0;
  pageSize1 = 5;
  isLoadingResults1 = true;
  isRateLimitReached1 = false;
  productoUsuario:number;
  columnasTablaProducto: string[] = ["SKU","Description","Editar","Quitar"];
 

  constructor(private httpClient: HttpClient,
    private variablesService: VariablesService,
    private notificaciones: NotificacionService) {
      this.productoUsuario = jwtDecode(localStorage.getItem('jwt-admin'))['data']['idProducto_Fort'];
      this.productoDatabase = new ProductoDatabase(this.httpClient);
   }

  ngAfterViewInit(): void {
    
    this.buscarProductos("");
    this.obtenerRelmarca();
  }

  aplicarFiltro(valor: string) {
    this.productos.filter = valor.trim().toLowerCase();  // Aplica el filtro en la tabla
  }

  obtenerRelmarca() {
    this.httpClient.get(environment.api_url + 'Productos/obtenerRelProductosSolucion').subscribe((data: any[]) =>  {
      for (let i = 0; i < data.length; i++) {
        this.Relmarcas.push(new Relmarca(data[i]));
      }      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

  

  
  
  buscarProductos(filtro1) {
    this.productos = new MatTableDataSource([]);
    this.sort1.sortChange.subscribe(() => (this.paginator1.pageIndex = 0));
    merge(this.sort1.sortChange, this.paginator1.page)
      .pipe(
        startWith({}),
        switchMap(() => {
          this.productoDatabase!.getNumberOfProducto(
            this.sort1.active,
            this.sort1.direction,
            this.paginator1.pageIndex,
            this.pageSize1,
            filtro1
          ).subscribe((numero) => {
            this.resultsLength1 = numero;
          });
          return this.productoDatabase!.getProducto(
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
      .subscribe((data: Producto[]) => (this.productos.data = data));
    this.tablaProductos.renderRows();
  }
   
  establecerIdProducto_Fort(idProducto_Fort){
    this.variablesService.idProducto_Fort=idProducto_Fort;
  }
  
 
  eliminarProducto(idProducto_Fort) {
    const formData = new FormData();
    formData.append('idProducto_Fort', idProducto_Fort);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    this.httpClient.post(environment.api_url + 'Productos/EliminarProducto',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.buscarProductos("");
      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }


}

export class ProductoDatabase {
  constructor(private _httpClient: HttpClient) { }

  getProducto(
    sort1: string,
    order1: string,
    page1: number,
    pageSize1: number,
    busqueda1: string,
  ): Observable<Producto[]> {
    const formData = new FormData();
    formData.append("jwt", localStorage.getItem("jwt-admin"));
    formData.append("filtro", busqueda1);
    formData.append("sort", sort1);
    formData.append("order", order1);
    formData.append("page", page1.toString());
    formData.append("pageSize", pageSize1.toString());
    return this._httpClient.post<Producto[]>(
      environment.api_url + "Productos/obtenerProductos",
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
      environment.api_url + "Productos/obtenerNumeroProductos",
      formData
    );
  }
}

