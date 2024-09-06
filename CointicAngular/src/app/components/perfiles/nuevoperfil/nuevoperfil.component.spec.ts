import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NuevoperfilComponent } from './nuevoperfil.component';

describe('NuevoperfilComponent', () => {
  let component: NuevoperfilComponent;
  let fixture: ComponentFixture<NuevoperfilComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NuevoperfilComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NuevoperfilComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
