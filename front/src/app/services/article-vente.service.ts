import { Injectable } from '@angular/core';
import { ParentService } from './parent.service';

@Injectable({
  providedIn: 'root'
})
export class ArticleVenteService<T>  extends ParentService<T>{

  
}
