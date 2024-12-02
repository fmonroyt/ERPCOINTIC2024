import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditarsonicComponent } from './editarsonic.component';

describe('EditarsonicComponent', () => {
  let component: EditarsonicComponent;
  let fixture: ComponentFixture<EditarsonicComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EditarsonicComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(EditarsonicComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
