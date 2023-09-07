import { Component, EventEmitter, Input, Output } from '@angular/core';
import { Links } from '../iterfaces/links';

@Component({
  selector: 'app-pagination',
  templateUrl: './pagination.component.html',
  styleUrls: ['./pagination.component.css']
})
export class PaginationComponent {

  @Input() links : Links[] = [];
  @Output() page  = new EventEmitter<number>();
  changePage(url :string)
  {
    this.page.emit(+url[url.length - 1])
  }
}
