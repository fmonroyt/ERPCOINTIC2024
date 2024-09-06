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
  { path: '/dashboard', title: 'Inicio',  icon: 'monetization_on', class: '', idModulo: 0 },
  { path: '/Perfiles', title: 'Perfiles',  icon: 'people_alt', class: '', idModulo: 1 },
  { path: '/Departamentos', title: 'Departamentos',  icon: 'group', class: '', idModulo: 3 },
  { path: '/Usuarios', title: 'Usuarios',  icon: 'group', class: '', idModulo: 4 },
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
