import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NuevosophosComponent } from './nuevosophos.component';

describe('NuevosophosComponent', () => {
  let component: NuevosophosComponent;
  let fixture: ComponentFixture<NuevosophosComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NuevosophosComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NuevosophosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
