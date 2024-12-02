import { Component, OnInit,ViewChild,EventEmitter,AfterViewInit } from '@angular/core';
import{ Perfil} from 'src/app/models/perfil';
import {  HttpClient  } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import { MatTable } from '@angular/material/table';
import { MatPaginator } from "@angular/material/paginator";
import { MatSort } from "@angular/material/sort";
import { merge, Observable, of as observableOf } from "rxjs";
import { startWith, switchMap, map, catchError } from "rxjs/operators";
import{ VariablesService} from 'src/app/services/variables.service';
import { NotificacionService } from 'src/app/services/notificacion.service';

@Component({
  selector: 'app-perfiles',
  templateUrl: './perfiles.component.html',
  styleUrls: ['./perfiles.component.scss']
})
export class PerfilesComponent implements AfterViewInit {
  @ViewChild(MatTable) tablaPerfil: MatTable<any>;
  @ViewChild('paginator1', { static: true }) paginator1: MatPaginator;
  @ViewChild(MatSort) sort1: MatSort;
  perfiles:Perfil[]=[];
  perfilDatabase: PerfilDatabase | null;
  resultsLength1 = 0;
  pageSize1 = 5;
  isLoadingResults1 = true;
  isRateLimitReached1 = false;
  columnasTablaPerfiles: string[] = ["nombrePerfil","Permisos","Editar","Quitar"];

  constructor(private httpClient: HttpClient,
    private variablesService: VariablesService,
    private notificaciones: NotificacionService) {
    this.perfilDatabase = new PerfilDatabase(this.httpClient);
   }

  // async ngOnInit(){
  // }

   ngAfterViewInit(): void {
    this.buscarPerfiles();
  }

buscarPerfiles() {
    this.perfiles = [];
    this.sort1.sortChange.subscribe(() => (this.paginator1.pageIndex = 0));
    merge(this.sort1.sortChange, this.paginator1.page)
      .pipe(
        startWith({}),
        switchMap(() => {
          this.perfilDatabase!.getNumberOfPerfil(
            this.sort1.active,
            this.sort1.direction,
            this.paginator1.pageIndex,
            this.pageSize1
            // filtro1
          ).subscribe((numero) => {
            this.resultsLength1 = numero;
          });
          return this.perfilDatabase!.getPerfil(
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
      .subscribe((data: Perfil[]) => (this.perfiles = data));
    this.tablaPerfil.renderRows();
  }



  establecerIdPerfil(idPerfil){
    this.variablesService.idPerfil=idPerfil;
  }

  eliminarPerfil(idPerfil) {
    const formData = new FormData();
    formData.append('idPerfil', idPerfil);
    formData.append('jwt', localStorage.getItem('jwt-admin'));
    this.httpClient.post(environment.api_url + 'Perfiles/EliminarPerfil',formData).subscribe((data: any[]) =>  {
      this.notificaciones.crearNotificacion(data['message'] || 'Bien!', "fa fa-check-square-o", "info");
      this.buscarPerfiles();
      
    }, error => {
      this.notificaciones.crearNotificacion(error['error']['message'] || 'Error desconocido.', "fa fa-times", "error");
    });  
  }

}
export class PerfilDatabase {
  constructor(private _httpClient: HttpClient) { }

  getPerfil(
    sort1: string,
    order1: string,
    page1: number,
    pageSize1: number,
    // busqueda1: string,
  ): Observable<Perfil[]> {
    const formData = new FormData();
    formData.append("jwt", localStorage.getItem("jwt-admin"));
    // formData.append("filtro", busqueda1);
    formData.append("sort", sort1);
    formData.append("order", order1);
    formData.append("page", page1.toString());
    formData.append("pageSize", pageSize1.toString());
    // formData.append("cuenta", idCuenta.toString());
    return this._httpClient.post<Perfil[]>(
      environment.api_url + "Perfiles/obtenerPerfiles",
      formData
    );
  }

  getNumberOfPerfil(
    sort1: string,
    order1: string,
    page1: number,
    pageSize1: number,
    // busqueda1: string,r
  ): Observable<number> {
    const formData = new FormData();
    formData.append("jwt", localStorage.getItem("jwt-admin"));
    formData.append("sort", sort1);
    formData.append("order", order1);
    formData.append("page", page1.toString());
    formData.append("pageSize", pageSize1.toString());
    return this._httpClient.post<number>(
      environment.api_url + "Perfiles/obtenerNumeroPerfiles",
      formData
    );
  }
}