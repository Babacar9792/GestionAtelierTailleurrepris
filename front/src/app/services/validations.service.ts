import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment.development';

@Injectable({
  providedIn: 'root'
})
export class ValidationsService {

  constructor(private http : HttpClient) { }

  search<T>(uri : string) : Observable<T>
  {
    return this.http.get<T>(environment.api.url+uri);
    }
}
