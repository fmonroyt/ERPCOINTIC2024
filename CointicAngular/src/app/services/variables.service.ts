import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class VariablesService {
  static idPerfil: number;
  static idDepartamento: number;
  static idUsuario: number;
  constructor() { }
  get idPerfil(): number {
    return VariablesService.idPerfil;
  }
  set idPerfil(value: number) {
    VariablesService.idPerfil = value;
  }
  get idDepartamento(): number {
    return VariablesService.idDepartamento;
  }
  set idDepartamento(value: number) {
    VariablesService.idDepartamento = value;
  }
  get idUsuario(): number {
    return VariablesService.idUsuario;
  }
  set idUsuario(value: number) {
    VariablesService.idUsuario = value;
  }
}
