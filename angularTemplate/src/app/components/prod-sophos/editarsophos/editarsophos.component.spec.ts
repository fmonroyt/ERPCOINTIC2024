import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditarsophosComponent } from './editarsophos.component';

describe('EditarsophosComponent', () => {
  let component: EditarsophosComponent;
  let fixture: ComponentFixture<EditarsophosComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EditarsophosComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(EditarsophosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
