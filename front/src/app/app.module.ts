import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppComponent } from './app.component';

import { HttpClientModule } from  '@angular/common/http';
import { ArticleVenteComponent } from './article-vente/article-vente.component';
import { ListVenteComponent } from './article-vente/list-vente/list-vente.component';
import { NavbarComponent } from './navbar/navbar.component';
import { FormVenteComponent } from './article-vente/form-vente/form-vente.component';
import { PaginationComponent } from './pagination/pagination.component';
import { ReactiveFormsModule } from '@angular/forms';

@NgModule({
  declarations: [
    AppComponent,
    ArticleVenteComponent,
    ListVenteComponent,
    NavbarComponent,
    PaginationComponent,
    FormVenteComponent

  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    ReactiveFormsModule
    
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
