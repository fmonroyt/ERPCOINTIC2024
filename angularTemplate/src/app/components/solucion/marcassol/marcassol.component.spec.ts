import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MarcassolComponent } from './marcassol.component';

describe('MarcassolComponent', () => {
  let component: MarcassolComponent;
  let fixture: ComponentFixture<MarcassolComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ MarcassolComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(MarcassolComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
