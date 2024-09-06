import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditardepartamentoComponent } from './editardepartamento.component';

describe('EditardepartamentoComponent', () => {
  let component: EditardepartamentoComponent;
  let fixture: ComponentFixture<EditardepartamentoComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EditardepartamentoComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(EditardepartamentoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
