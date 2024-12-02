import { Component, OnInit } from '@angular/core';
import {PermisosService} from '../services/permisos.service';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit {

  constructor(private permisos: PermisosService) { }

  ngOnInit(): void {
    this.permisos.cargarPermisos();
  }

}
