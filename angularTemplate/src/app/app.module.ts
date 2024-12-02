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
import { EditarusuarioComponent } from './components/Usuarios/editarusuario/editarusuario.component';
import { MarcasComponent } from './components/marcas/marcas.component';
import { SolucionComponent } from './components/solucion/solucion.component';
import { ProductosComponent } from './components/productos/productos.component';
import { NuevomarcaComponent } from './components/marcas/nuevomarca/nuevomarca.component';
import { EditarmarcaComponent } from './components/marcas/editarmarca/editarmarca.component';
import { EditarsolucionComponent } from './components/solucion/editarsolucion/editarsolucion.component';
import { NuevosolucionComponent } from './components/solucion/nuevosolucion/nuevosolucion.component';
import { EditarproductoComponent } from './components/productos/editarproducto/editarproducto.component';
import { NuevoproductoComponent } from './components/productos/nuevoproducto/nuevoproducto.component';
import { MarcassolComponent } from './components/solucion/marcassol/marcassol.component';
import { AgregarExcelComponent } from './components/productos/agregar-excel/agregar-excel.component';
import { ProdSonicComponent } from './components/prod-sonic/prod-sonic.component';
import { EditarsonicComponent } from './components/prod-sonic/editarsonic/editarsonic.component';
import { NuevosonicComponent } from './components/prod-sonic/nuevosonic/nuevosonic.component';
import { ExcelsonicComponent } from './components/prod-sonic/excelsonic/excelsonic.component';
import { ProdSophosComponent } from './components/prod-sophos/prod-sophos.component';
import { NuevosophosComponent } from './components/prod-sophos/nuevosophos/nuevosophos.component';
import { EditarsophosComponent } from './components/prod-sophos/editarsophos/editarsophos.component';
import { ExcelsophosComponent } from './components/prod-sophos/excelsophos/excelsophos.component';
import { WatchguardComponent } from './components/watchguard/watchguard.component';
import { NuevowatchComponent } from './components/watchguard/nuevowatch/nuevowatch.component';
import { ExcelwatchComponent } from './components/watchguard/excelwatch/excelwatch.component';
import { EditarwatchComponent } from './components/watchguard/editarwatch/editarwatch.component';
import { EmpresaComponent } from './components/empresa/empresa.component';
import { NuevoEmpresaComponent } from './components/empresa/nuevo-empresa/nuevo-empresa.component';
import { EditarEmpresaComponent } from './components/empresa/editar-empresa/editar-empresa.component';
import { ExcelEmpresaComponent } from './components/empresa/excel-empresa/excel-empresa.component';
import { ContactoComponent } from './components/contacto/contacto.component';
import { NuevocontactoComponent } from './components/contacto/nuevocontacto/nuevocontacto.component';
import { EditarcontactoComponent } from './components/contacto/editarcontacto/editarcontacto.component';
import { ExcelcontactoComponent } from './components/contacto/excelcontacto/excelcontacto.component';




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
            { path: 'Marcas', component:MarcasComponent , canActivate: [AuthService]},
            { path: 'Soluciones', component:SolucionComponent , canActivate: [AuthService]},
            { path: 'Productos', component:ProductosComponent , canActivate: [AuthService]},
            { path: 'Sonics', component:ProdSonicComponent , canActivate: [AuthService]},
            { path: 'Sophos', component:ProdSophosComponent , canActivate: [AuthService]},
            { path: 'Watchguards', component:WatchguardComponent , canActivate: [AuthService]},
            { path: 'Empresa', component:EmpresaComponent , canActivate: [AuthService]},
            { path: 'Contacto', component:ContactoComponent , canActivate: [AuthService]},
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
    EditarusuarioComponent,
    MarcasComponent,
    NuevomarcaComponent,
    SolucionComponent,
    ProductosComponent,
    EditarmarcaComponent,
    EditarsolucionComponent,
    NuevosolucionComponent,
    EditarproductoComponent,
    NuevoproductoComponent,
    MarcassolComponent,
    AgregarExcelComponent,

    ProdSonicComponent,
    EditarsonicComponent,
    NuevosonicComponent,
    ExcelsonicComponent,
    ProdSophosComponent,
    NuevosophosComponent,
    EditarsophosComponent,
    ExcelsophosComponent,
    WatchguardComponent,
    NuevowatchComponent,
    ExcelwatchComponent,
    EditarwatchComponent,
    EmpresaComponent,
    NuevoEmpresaComponent,
    EditarEmpresaComponent,
    ExcelEmpresaComponent,
    ContactoComponent,
    NuevocontactoComponent,
    EditarcontactoComponent,
    ExcelcontactoComponent,
    
    
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
