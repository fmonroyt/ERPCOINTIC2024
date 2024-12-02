import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NuevowatchComponent } from './nuevowatch.component';

describe('NuevowatchComponent', () => {
  let component: NuevowatchComponent;
  let fixture: ComponentFixture<NuevowatchComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NuevowatchComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NuevowatchComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
