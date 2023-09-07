import { Component, LOCALE_ID, OnInit, ViewChild } from '@angular/core';
import { ArticleVenteService } from '../services/article-vente.service';
import { ResponseArticleVente } from '../iterfaces/response-article-vente';
import { ArticleConfection } from '../iterfaces/article-confection';
import { ArticleVente } from '../iterfaces/article-vente';
import { Categories } from '../iterfaces/categories';
import { Links } from '../iterfaces/links';
import { DataResponse } from '../iterfaces/data-response';
import { FormVenteComponent } from './form-vente/form-vente.component';
import { FormControl, FormGroup } from '@angular/forms';
import { debounce, debounceTime, map } from 'rxjs';
import { environment } from 'src/environments/environment.development';

@Component({
  selector: 'app-article-vente',
  templateUrl: './article-vente.component.html',
  styleUrls: ['./article-vente.component.css']
})
export class ArticleVenteComponent implements OnInit {

  articleVentes: ArticleVente[] = [];
  articleVentesSearched: ArticleVente[] = [];
  articlesConfections: ArticleConfection[] = [];
  categories: Categories[] = [];
  categoriesVentes: Categories[] = [];
  links: Links[] = [];
  currentPage: number = 1;
  croissantOrDecroissant: boolean = true;
  nombreDeLigne: number = 3;
  EditOrAdd: boolean = true;
  @ViewChild(FormVenteComponent, { static: false }) formvente: FormVenteComponent = <FormVenteComponent>{}

  constructor(private articleVenteService: ArticleVenteService<ResponseArticleVente<ArticleConfection>>) { }

  ngOnInit(): void {

    this.getData(1);
    // this.searchArticleVente();
  }

  getData(page: number, perpage: number = 3) {

    const requete = this.articleVenteService.getAll("articleVente", page, perpage).subscribe({
      next: (value: ResponseArticleVente<ArticleConfection>) => {
        console.log(value);

        // console.log(value);
        this.articleVentes = value.data;
        this.articlesConfections = value.articles;
        this.categories = value.categories;
        this.links = value.links!;
        console.log(this.categories);

        this.categoriesVentes = this.categories.filter((element: Categories) => element.type_categorie === 'AV');

      },
      error: (error) => {
        console.log(error);

      }



    })

    setTimeout(() => {
      requete.unsubscribe();

    }, 5000);
  }


  changePage(page: number) {
    this.getData(page, this.nombreDeLigne);
    this.currentPage = page;
    this.croissantOrDecroissant = true;
  }

  searchArticleVente(event: string) {
    const sub = this.articleVenteService.search('articleVente/search/' + event).pipe(
      debounceTime(300)
    )
    .subscribe({
      next: (value: ResponseArticleVente<ArticleConfection>) => {
        this.articleVentesSearched = value.data
        console.log(value);

      },
      error: (error) => {
        console.log(error);

      }
    });

  }

  addOrUpdateArticleVente(event: ArticleVente) {
    if (this.EditOrAdd) {

      const subs = this.articleVenteService.add<ResponseArticleVente<ArticleConfection>, ArticleVente>("articleVente", event).subscribe({
        next: (value: ResponseArticleVente<ArticleConfection>) => {
          if (value.status) {
            this.articleVentes.unshift(value.data[0])
            this.clearForm();
            alert(value.message);
          }
          else {
            alert(value.message);
          }
        },


        error: (error) => {
          alert(error.error.message)
          console.log(error);

        },
        complete: () => console.log("completed")

      })
      setTimeout(() => {
        subs.unsubscribe();
      }, 3000);
    }
    else {
      const subs = this.articleVenteService.update<ResponseArticleVente<ArticleVente>, ArticleVente>("articleVente/update", event).subscribe({
        next: (value: ResponseArticleVente<ArticleVente>) => {
          console.log(value)
          if(value.status)
          {
            let index = this.articleVentes.findIndex((element : ArticleVente) => element.id === event.id);
            console.log(this.articleVentes[index]);
            console.log(value.data);
            this.clearForm();
            this.articleVentes[index] = value.data[0];
          }
          alert(value.message);
        },
        error: (error) => console.log(error)


      })
      setTimeout(() => {
        subs.unsubscribe();
      }, 3000);
    }

    // console.log(event);


  }

  edit(event: any) {
    console.log(event);
    this.formvente.confection_by_vente.clear();
    let tab = [];
    tab = event.confection_by_vente;
    tab.forEach((element: any) => {
      this.formvente.confection_by_vente.push(
        new FormGroup({
          article_id: new FormControl(element.article_id),
          libelle_article: new FormControl(element.libelle_article),
          quantite_necessaire: new FormControl(element.quantite_necessaire),
          categorie : new FormControl(element.categorie)
        })
      )

    });
    this.formvente.image = event.photo;
    this.formvente.registerFormForSell.patchValue(event)
    this.formvente.promo?.setValue(event.promo);
    this.EditOrAdd = false;
    // this.formvente.categorie = event.categorie


  }

  delete(event: number) {
    this.articleVenteService.delete<number[]>("articleVente/" + event).subscribe({
      next: (value: DataResponse<number[]>) => {
        this.articleVentes = this.articleVentes.filter((element: ArticleVente) => element.id !== event);
        this.clearForm()
        console.log(value)
        alert(value.message)
      },
      error: (error) => console.log(error),
      complete: () => console.log("completed")



    })

  }

  perpage(event: number) {
    console.log(event);
    this.nombreDeLigne = event;
    this.getData(this.currentPage, event);
  }


  triage(event: boolean) {
    // console.log(event);
    console.log(this.croissantOrDecroissant);

    this.articleVenteService.getAll("articleVente", this.currentPage, this.nombreDeLigne).pipe(
      map((data) => {
        if (this.croissantOrDecroissant) {

          return data.data.sort((a: ArticleVente, b: ArticleVente) => a.libelle.localeCompare(b.libelle))
        }
        return data.data.sort((a: ArticleVente, b: ArticleVente) => b.libelle.localeCompare(a.libelle))

      })
    ).subscribe({
      next: (valeur) => {
        console.log(valeur)
        this.articleVentes = valeur
        this.croissantOrDecroissant = !this.croissantOrDecroissant;
      },
      error: (error) => console.log(error)


    })
  }

  clearForm()
  {
    this.formvente.confection_by_vente.clear();
    this.formvente.registerFormForSell.reset();
    this.formvente.image = environment.defaultImage;
  }

  searchArticle(event : string)
  {

  }



}
