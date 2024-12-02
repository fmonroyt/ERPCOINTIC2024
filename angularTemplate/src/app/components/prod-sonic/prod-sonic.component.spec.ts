import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ProdSonicComponent } from './prod-sonic.component';

describe('ProdSonicComponent', () => {
  let component: ProdSonicComponent;
  let fixture: ComponentFixture<ProdSonicComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ProdSonicComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ProdSonicComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
