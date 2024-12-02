import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NuevoEmpresaComponent } from './nuevo-empresa.component';

describe('NuevoEmpresaComponent', () => {
  let component: NuevoEmpresaComponent;
  let fixture: ComponentFixture<NuevoEmpresaComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NuevoEmpresaComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NuevoEmpresaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
