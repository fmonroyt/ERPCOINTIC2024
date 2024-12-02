import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditarsolucionComponent } from './editarsolucion.component';

describe('EditarsolucionComponent', () => {
  let component: EditarsolucionComponent;
  let fixture: ComponentFixture<EditarsolucionComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EditarsolucionComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(EditarsolucionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
