import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditarmarcaComponent } from './editarmarca.component';

describe('EditarmarcaComponent', () => {
  let component: EditarmarcaComponent;
  let fixture: ComponentFixture<EditarmarcaComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EditarmarcaComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(EditarmarcaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
