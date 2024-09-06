import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NuevodepartamentoComponent } from './nuevodepartamento.component';

describe('NuevodepartamentoComponent', () => {
  let component: NuevodepartamentoComponent;
  let fixture: ComponentFixture<NuevodepartamentoComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NuevodepartamentoComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NuevodepartamentoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
