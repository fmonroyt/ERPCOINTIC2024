import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NuevosonicComponent } from './nuevosonic.component';

describe('NuevosonicComponent', () => {
  let component: NuevosonicComponent;
  let fixture: ComponentFixture<NuevosonicComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NuevosonicComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NuevosonicComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
