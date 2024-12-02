import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ExcelcontactoComponent } from './excelcontacto.component';

describe('ExcelcontactoComponent', () => {
  let component: ExcelcontactoComponent;
  let fixture: ComponentFixture<ExcelcontactoComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ExcelcontactoComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ExcelcontactoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
