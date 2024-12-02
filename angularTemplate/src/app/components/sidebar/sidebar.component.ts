import { Component, OnInit } from '@angular/core';
import {PermisosService} from '../../services/permisos.service';
declare const $: any;
declare interface RouteInfo {
    path: string;
    title: string;
    icon: string;
    class: string;
    idModulo: number;
}

export const ROUTES: RouteInfo[] = [
  { path: '/dashboard', title: 'Inicio',  icon: 'home', class: '', idModulo: 0 },
  { path: '/Perfiles', title: 'Perfiles',  icon: 'people_alt', class: '', idModulo: 1 },
  { path: '/Departamentos', title: 'Departamentos',  icon: 'apartment', class: '', idModulo: 3 },
  { path: '/Usuarios', title: 'Usuarios',  icon: 'groups', class: '', idModulo: 4 },
  { path: '/Marcas', title: 'Marcas',  icon: 'scanner', class: '', idModulo: 5 },
  { path: '/Soluciones', title: 'Soluciones',  icon: 'cloud', class: '', idModulo: 6 },
  { path: '/Productos', title: 'Fortinet',  icon: 'router', class: '', idModulo: 7 },
  { path: '/Sonics', title: 'SonicWall',  icon: 'router', class: '', idModulo: 9 },
  { path: '/Sophos', title: 'Sophos',  icon: 'router', class: '', idModulo: 10 },
  { path: '/Watchguards', title: 'Watchguard',  icon: 'router', class: '', idModulo: 11 },
  { path: '/Empresa', title: 'Empresa',  icon: 'contacts', class: '', idModulo: 12 },
  { path: '/Contacto', title: 'Contacto',  icon: 'contacts', class: '', idModulo: 8 },
];

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.scss']
})
export class SidebarComponent implements OnInit {
  menuItems: any[];

  constructor(public permisos: PermisosService) {
   }

  ngOnInit() {
    this.menuItems = ROUTES.filter(menuItem => menuItem);
  }
  isMobileMenu() {
    if ($(window).width() > 991) {
        return false;
    }
    return true;
};





}
