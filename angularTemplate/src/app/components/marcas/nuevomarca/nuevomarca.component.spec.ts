import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NuevomarcaComponent } from './nuevomarca.component';

describe('NuevomarcaComponent', () => {
  let component: NuevomarcaComponent;
  let fixture: ComponentFixture<NuevomarcaComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NuevomarcaComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NuevomarcaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
