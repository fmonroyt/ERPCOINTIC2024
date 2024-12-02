import { ComponentFixture, TestBed } from '@angular/core/testing';

import { WatchguardComponent } from './watchguard.component';

describe('WatchguardComponent', () => {
  let component: WatchguardComponent;
  let fixture: ComponentFixture<WatchguardComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ WatchguardComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(WatchguardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
