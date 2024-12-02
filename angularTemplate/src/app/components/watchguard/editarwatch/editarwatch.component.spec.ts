import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditarwatchComponent } from './editarwatch.component';

describe('EditarwatchComponent', () => {
  let component: EditarwatchComponent;
  let fixture: ComponentFixture<EditarwatchComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EditarwatchComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(EditarwatchComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
