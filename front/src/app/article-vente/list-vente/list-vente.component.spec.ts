import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ListVenteComponent } from './list-vente.component';

describe('ListVenteComponent', () => {
  let component: ListVenteComponent;
  let fixture: ComponentFixture<ListVenteComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [ListVenteComponent]
    });
    fixture = TestBed.createComponent(ListVenteComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
