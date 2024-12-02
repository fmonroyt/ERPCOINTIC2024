import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NuevousuarioComponent } from './nuevousuario.component';

describe('NuevousarioComponent', () => {
  let component: NuevousuarioComponent;
  let fixture: ComponentFixture<NuevousuarioComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NuevousuarioComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NuevousuarioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
