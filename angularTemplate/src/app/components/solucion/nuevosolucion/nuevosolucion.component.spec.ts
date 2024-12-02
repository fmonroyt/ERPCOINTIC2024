import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NuevosolucionComponent } from './nuevosolucion.component';

describe('NuevosolucionComponent', () => {
  let component: NuevosolucionComponent;
  let fixture: ComponentFixture<NuevosolucionComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NuevosolucionComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NuevosolucionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
