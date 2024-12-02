import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ExcelsophosComponent } from './excelsophos.component';

describe('ExcelsophosComponent', () => {
  let component: ExcelsophosComponent;
  let fixture: ComponentFixture<ExcelsophosComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ExcelsophosComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ExcelsophosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
