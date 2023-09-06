import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment.development';
import { DataResponse } from '../iterfaces/data-response';

@Injectable({
  providedIn: 'root'
})
export abstract class ParentService<T> {

  constructor(private http: HttpClient) { }



  getAll(uri: string, page: number = 1, perpage: number = 3): Observable<T> {
    return this.http.get<T>(environment.api.url + uri + "?page=" + page + "&item=" + perpage);

  }

  search(uri: string): Observable<T> {
    return this.http.get<T>(environment.api.url + uri);
  }

  add<R, T>(uri: string, objet: T): Observable<R> {
    return this.http.post<R>(environment.api.url + uri, objet);
  }
  delete<T>(uri : string) : Observable<DataResponse<T>>
  {
    return this.http.delete<DataResponse<T>>(environment.api.url+uri);
  }

  update<R,T>(uri : string, objet : T) : Observable<R>
  {
    return this.http.post<R>(environment.api.url+uri, objet);
  }
}
