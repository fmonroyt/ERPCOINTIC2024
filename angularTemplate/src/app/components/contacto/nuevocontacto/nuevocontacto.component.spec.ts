import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NuevocontactoComponent } from './nuevocontacto.component';

describe('NuevocontactoComponent', () => {
  let component: NuevocontactoComponent;
  let fixture: ComponentFixture<NuevocontactoComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NuevocontactoComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NuevocontactoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
