import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AgregarExcelComponent } from './agregar-excel.component';

describe('AgregarExcelComponent', () => {
  let component: AgregarExcelComponent;
  let fixture: ComponentFixture<AgregarExcelComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AgregarExcelComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(AgregarExcelComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
