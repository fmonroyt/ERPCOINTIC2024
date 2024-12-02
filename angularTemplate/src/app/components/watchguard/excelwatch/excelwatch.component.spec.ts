import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ExcelwatchComponent } from './excelwatch.component';

describe('ExcelwatchComponent', () => {
  let component: ExcelwatchComponent;
  let fixture: ComponentFixture<ExcelwatchComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ExcelwatchComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ExcelwatchComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
