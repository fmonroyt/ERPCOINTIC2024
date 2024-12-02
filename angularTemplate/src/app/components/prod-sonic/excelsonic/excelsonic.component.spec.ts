import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ExcelsonicComponent } from './excelsonic.component';

describe('ExcelsonicComponent', () => {
  let component: ExcelsonicComponent;
  let fixture: ComponentFixture<ExcelsonicComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ExcelsonicComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ExcelsonicComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
