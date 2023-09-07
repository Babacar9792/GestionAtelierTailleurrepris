import { Routes, RouterModule } from "@angular/router";
import { ArticleVenteComponent } from "./article-vente/article-vente.component";
import { CategorieComponent } from "./categorie/categorie.component";


export const routes: Routes = [
    { path: 'articleVente', component: ArticleVenteComponent },
    { path: 'categorie', component: CategorieComponent }
  ];