import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class VariablesService {
  static idPerfil: number;
  static idUsuario: number;
  static idDepartamento: number;
  static idMarcas: number;
  static idsoluciones: number;
  static idProducto_Fort: number;
  static idcontacto: number;
  static idempresaExt: number;
  static idproducto_Sonic: number;
  static idproduct_Sophos: number;
  static idproduct_Watchguard: number;
  static idEmpresaExt: number;
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
  get idMarcas(): number {
    return VariablesService.idMarcas;
  }
  get idsoluciones(): number {
    return VariablesService.idsoluciones;
  }
  get idProducto_Fort(): number {
    return VariablesService.idProducto_Fort;
  }
  set idDepartamento(value: number) {
    VariablesService.idDepartamento = value;
  }
  set idProducto_Fort(value: number) {
    VariablesService.idProducto_Fort = value;
  }
  set idMarcas(value: number) {
    VariablesService.idMarcas = value;
  }
  set idsoluciones(value: number) {
    VariablesService.idsoluciones = value;
  }
  get idUsuario(): number {
    return VariablesService.idUsuario;
  }
  set idUsuario(value: number) {
    VariablesService.idUsuario = value;
  }
  get idcontacto(): number {
    return VariablesService.idcontacto;
  }
  set idcontacto(value: number) {
    VariablesService.idcontacto = value;
  }
  get idempresaExt(): number {
    return VariablesService.idempresaExt;
  }
  set idempresaExt(value: number) {
    VariablesService.idempresaExt = value;
  }
  get idproducto_Sonic(): number {
    return VariablesService.idproducto_Sonic;
  }
  set idproducto_Sonic(value: number) {
    VariablesService.idproducto_Sonic = value;
  }
  get idproduct_Sophos(): number {
    return VariablesService.idproduct_Sophos;
  }
  set idproduct_Sophos(value: number) {
    VariablesService.idproduct_Sophos = value;
  }
  get idproduct_Watchguard(): number {
    return VariablesService.idproduct_Watchguard;
  }
  set idproduct_Watchguard(value: number) {
    VariablesService.idproduct_Watchguard = value;
  }
  get idEmpresaExt(): number {
    return VariablesService.idEmpresaExt;
  }
  set idEmpresaExt(value: number) {
    VariablesService.idEmpresaExt = value;
  }
}
