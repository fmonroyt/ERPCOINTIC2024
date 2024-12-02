import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ProdSophosComponent } from './prod-sophos.component';

describe('ProdSophosComponent', () => {
  let component: ProdSophosComponent;
  let fixture: ComponentFixture<ProdSophosComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ProdSophosComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ProdSophosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
