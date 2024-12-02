import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ExcelEmpresaComponent } from './excel-empresa.component';

describe('ExcelEmpresaComponent', () => {
  let component: ExcelEmpresaComponent;
  let fixture: ComponentFixture<ExcelEmpresaComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ExcelEmpresaComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ExcelEmpresaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
