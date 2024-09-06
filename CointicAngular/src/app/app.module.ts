import {LOCALE_ID,NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { AppComponent } from './app.component';
import { CommonModule, registerLocaleData } from '@angular/common';
import localeEs from '@angular/common/locales/es'
import { RouterModule, Routes } from '@angular/router';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { AuthService } from './services/auth.service'
import { SweetAlert2Module } from '@sweetalert2/ngx-sweetalert2';
import { NgxDropzoneModule } from 'ngx-dropzone';
import { MatIconModule } from '@angular/material/icon';
import { MatButtonToggleModule } from '@angular/material/button-toggle';
import { MatAutocompleteModule } from '@angular/material/autocomplete';
import { MatDividerModule } from '@angular/material/divider';
import { MatButtonModule } from '@angular/material/button';
import { MatNativeDateModule } from '@angular/material/core';
import { MatSortModule } from '@angular/material/sort';
import { MatSelectModule } from '@angular/material/select';
import { MatTableModule } from '@angular/material/table';
import { MatPaginatorModule } from '@angular/material/paginator';
import {MatStepperModule} from '@angular/material/stepper';
import {MatListModule} from '@angular/material/list';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { MatTooltipModule } from '@angular/material/tooltip';
import { MatExpansionModule } from '@angular/material/expansion';
import { MatRippleModule } from '@angular/material/core';
import { NgxChartsModule } from '@swimlane/ngx-charts';
import {MatDatepickerModule} from '@angular/material/datepicker';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatCheckboxModule} from '@angular/material/checkbox';
import { SweetAlertService } from 'angular-sweetalert-service';
import {MatProgressBarModule} from '@angular/material/progress-bar';
import { NgxUiLoaderConfig, NgxUiLoaderHttpModule, NgxUiLoaderModule, PB_DIRECTION, POSITION } from 'ngx-ui-loader';
import { LayoutComponent } from './layout/layout.component';
import{DashboardComponent} from './dashboard/dashboard.component';
import { LoginComponent } from './components/login/login.component';
import { NavbarComponent } from './components/navbar/navbar.component';
import { SidebarComponent } from './components/sidebar/sidebar.component';
import { FooterComponent } from './components/footer/footer.component';
import { PerfilesComponent } from './components/perfiles/perfiles.component';
import { NuevoperfilComponent } from './components/perfiles/nuevoperfil/nuevoperfil.component';
import { EditarperfilComponent } from './components/perfiles/editarperfil/editarperfil.component';
import { PermisosComponent } from './components/perfiles/permisos/permisos.component';
import { DepartamentosComponent } from './components/departamentos/departamentos.component';
import { NuevodepartamentoComponent } from './components/Departamentos/nuevodepartamento/nuevodepartamento.component';
import { EditardepartamentoComponent } from './components/Departamentos/editardepartamento/editardepartamento.component';
import { UsuariosComponent } from './components/usuarios/usuarios.component';
import { NuevousuarioComponent } from './components/usuarios/nuevousuario/nuevousuario.component';
import { EditarusuarioComponent } from './components/Usuarios/editarusuario/editarusuario.component'

registerLocaleData(localeEs, 'es');
const ngxUiLoaderConfig: NgxUiLoaderConfig = {
  bgsColor: '#01224E', // color
  bgsPosition: POSITION.bottomLeft, // position of the loader
  pbDirection: PB_DIRECTION.leftToRight, // progress bar direction
  pbThickness: 5, // progress bar thickness
  bgsType: 'pulse',

  fgsColor: '#01224E',
  fgsPosition: POSITION.bottomLeft,
  fgsSize: 60,
  fgsType: 'pulse',

  logoPosition: 'center-center',
  masterLoaderId: 'master',
  overlayBorderRadius: '0',
  overlayColor: 'rgba(40, 40, 40, 0.8)',
  pbColor: '#01224E',
  hasProgressBar: true,
  textColor: '#FFFFFF',
  textPosition: 'center-center'
};

const routes: Routes = [{
  path: '',
        component: LayoutComponent,
        children: [
            { path: 'dashboard', component: DashboardComponent, canActivate: [AuthService]},
            { path: 'Perfiles', component: PerfilesComponent, canActivate: [AuthService]},
            { path: 'Perfiles/Permisos/:idPerfil', component: PermisosComponent, canActivate: [AuthService]},
            { path: 'Departamentos', component:DepartamentosComponent , canActivate: [AuthService]},
            { path: 'Usuarios', component:UsuariosComponent , canActivate: [AuthService]},
          ], canActivate: [AuthService]},
    { path: 'login', component: LoginComponent },
];

@NgModule({
  declarations: [
    AppComponent,
    LayoutComponent,
    LoginComponent,
    NavbarComponent,
    SidebarComponent,
    FooterComponent,
    PerfilesComponent,
    NuevoperfilComponent,
    EditarperfilComponent,
    PermisosComponent,
    DepartamentosComponent,
    NuevodepartamentoComponent,
    EditardepartamentoComponent,
    UsuariosComponent,
    NuevousuarioComponent,
    EditarusuarioComponent
  ],
  imports: [
    MatNativeDateModule,
    BrowserModule,
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    RouterModule.forRoot(routes),
    BrowserAnimationsModule,
    SweetAlert2Module.forRoot(),
    NgxDropzoneModule,
    NgxUiLoaderModule.forRoot(ngxUiLoaderConfig),
    NgxUiLoaderHttpModule,
    NgxChartsModule,
    MatButtonModule,
    MatRippleModule,
    MatInputModule,
    MatFormFieldModule,
    MatSelectModule,
    MatTooltipModule,
    MatDividerModule,
    MatAutocompleteModule,
    MatIconModule,
    MatTableModule,
    MatButtonToggleModule,
    MatExpansionModule,
    MatPaginatorModule,
    MatSortModule,
    MatDatepickerModule,
    MatCheckboxModule,
    MatStepperModule,
    MatListModule,
    MatProgressBarModule
  ],
  providers: [
    {provide: LOCALE_ID, useValue: 'es'},
    SweetAlertService

  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
