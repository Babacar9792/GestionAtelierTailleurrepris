
import { Component, ElementRef, EventEmitter, Input, Output, ViewChild } from '@angular/core';
import { FormBuilder, Validators, FormGroup, FormArray, ValidatorFn, ValidationErrors, AbstractControl, AsyncValidatorFn } from '@angular/forms';
import { Observable, catchError, map, min, of } from 'rxjs';
import { ArticleConfection } from 'src/app/iterfaces/article-confection';
import { ArticleVente } from 'src/app/iterfaces/article-vente';
import { Categories } from 'src/app/iterfaces/categories';
import { ArticleVenteService } from 'src/app/services/article-vente.service';
import { ValidationsService } from 'src/app/services/validations.service';
import { getReference, notSpecialCharacters, onlyNumber } from 'src/app/shared/fonctionsSchared/Functions';
import { extension, libelleUnique, minValueNumber } from 'src/app/shared/validators/validation';
import { environment } from 'src/environments/environment.development';


@Component({
  selector: 'app-form-vente',
  templateUrl: './form-vente.component.html',
  styleUrls: ['./form-vente.component.css']
})
export class FormVenteComponent {

  checkBoxPromo: boolean = false;
  // initialCategorie : Categories = <Categories>{}
  @Input() categoriesVentes: Categories[] = [];
  @Input() articlesConfections: ArticleConfection[] = [];
  @Input() articleVentesSearched : ArticleVente[] = [];
  @Input() categories : Categories[]= []; 
  @Output() libelleExist = new EventEmitter<string>();
  @Output() articleToSend = new EventEmitter();
  @ViewChild('margeInput') margeInput!: ElementRef;
  @ViewChild('prixConfectionInput') prixConfectionInput !: ElementRef;
  @ViewChild('categorieSelect') categorieSelect !: ElementRef;
  messageImageValide: string = '';
  tabArticleSearched: ArticleConfection[] = [];
  tabArticleConfectionSelected: ArticleConfection[] = [];
  idFormArraySelcted: number = -1;
  inValideAddConfection: string = '';
  // @ViewChild('checkBoxPromo') checkBoxPromo : HTMLInputElement = <HTMLInputElement>{} ;
  image = environment.defaultImage;
  // inputSelected :



  // valeurExistsValidator(): AsyncValidatorFn {
  //   return (control: AbstractControl):{ [key: string]: any } | null => {
  //     const valeurSaisie = control.value;
  //     // if(value.length >= 3)
  //     // {
        
  //     // }
  //     // return ParentService.checkValueInDatabase(value).pipe(
  //     //   map(result => (result ? null : { valueExists: true })),
  //     //   catchError(() => of(null)) // Gestion des erreurs
  //     // );
  //     if(valeurSaisie.value.length ===3)
  //     {
  //       console.log(valeurSaisie.value.substring(0,3));
  //       this.libelleExist.emit(valeurSaisie.value.substring(0,3))
  //     }
  //     if(this.articleVentesSearched.find((element : ArticleVente) => element.libelle.toLowerCase() === valeurSaisie.value.toLowerCase())){
  //     //  this.articleVentesSearched.find((element : ArticleVente) => element.libelle.toLowerCase() === valeurSaisie.value.toLowerCase());
  //     return {"libelleexist" : true};
  //       // this.libelle?.setValue('');
  //     }
  //     return null;
  //   };
  // }


  valeurExistsValidator(): AsyncValidatorFn {
    return (control: AbstractControl):
      Observable<ValidationErrors | null> => { // Modifiez le type de retour ici
        const valeurSaisie = control.value;

        if (valeurSaisie.length === 3) {
          console.log(valeurSaisie.substring(0, 3));
          // Vous pouvez émettre un événement ici, si nécessaire.
        }

        return this.validationService.search('articleVente/search/'+valeurSaisie).pipe(
          map(result => (result ? { libelleexist: true } : null)),
          catchError(() => of(null))
        );
      };
  }

  
  ValidationConfectionByVente(): ValidatorFn {
    return (control: AbstractControl): ValidationErrors | null => {
      const value = control.value;

      if (!value || !Array.isArray(value)) {
        return null;
      }
      let categoriesMin = this.categories.filter((element: Categories) => environment.minConfection.includes(element.libelle.toLowerCase())).map((element: Categories) => element.id);

      // Check if all categoriesMin IDs are present in selectedCategoryIds
      const allCategoriesMinIncluded = categoriesMin.every(id =>
        value.some((element: any) => element.categorie && element.categorie.id === id)
      );

      if (!allCategoriesMinIncluded) {
        return { 'minCategorieConfectionRequired': true };
      }

      return null;
    };
  }

  constructor(private fb: FormBuilder, private validationService : ValidationsService ) { }

  registerFormForSell = this.fb.group({
    id: ['', []],
    libelle: ['', [Validators.required, Validators.minLength(3), this.valeurExistsValidator]],
    promo: [0, [Validators.required,Validators.min(0), Validators.max(100)]],
    reference: ['', []],
    prix_vente: [5000, []],
    prix_confection: [0, [Validators.min(1)]],
    photo: ['', []],
    categorie: ['', [Validators.required]],
    confection_by_vente: this.fb.array([], [this.ValidationConfectionByVente]),
    marge: [5000, [Validators.required, Validators.min(5000)]]
  });
  // this.ValidationConfectionByVente()

  



  get marge() {
    return this.registerFormForSell.get('marge');
  }
  get categorie() {
    return this.registerFormForSell.get('categorie');
  }
  get photo() {
    return this.registerFormForSell.get('photo');
  }

  get prix_confection() {
    return this.registerFormForSell.get('prix_confection');
  }

  get promo() {
    return this.registerFormForSell.get('promo');
  }

  get confection_by_vente(): FormArray {
    return this.registerFormForSell.get('confection_by_vente') as FormArray;
  }

  get libelle() {
    return this.registerFormForSell.get('libelle');
  }
  get reference() {
    return this.registerFormForSell.get('reference');
  }
  get prix_vente() {
    return this.registerFormForSell.get('prix_vente');
  }


  onSubmite() {
    console.log(this.registerFormForSell.valid);
    console.log(this.articlesConfections);
    this.articleToSend.emit(this.registerFormForSell.value)


  }


  compareWith = (cat1: Categories, cat2: Categories): boolean => {
    return cat1 && cat2 ? cat1.id == cat2.id : cat1 == cat2;

  }




  promoOrNo(event: Event) {
    let promoCheckBox = event.target as HTMLInputElement;
    this.checkBoxPromo = promoCheckBox.checked;
    if (!promoCheckBox.checked) {
      this.promo?.setValue(0);
    }
  }




  newArticleConfection(): FormGroup {
    return this.fb.group({
      article_id: [0, [Validators.required, Validators.min(1)]],
      libelle_article: ['', [Validators.required,]],
      quantite_necessaire: [1, [Validators.required, Validators.min(1)]],
      categorie : ['', [Validators.required]]
    })
  }


  addNewArticleConfection() {
    let nombreArticlesConfectionsSelested = this.confection_by_vente.value.length
    if (nombreArticlesConfectionsSelested === 0) {
      this.inValideAddConfection = "";
      this.confection_by_vente.push(this.newArticleConfection());
    }
    else {
      console.log(this.confection_by_vente.value.length);
      if (this.confection_by_vente.controls[this.confection_by_vente.value.length - 1].value.article_id !== 0 && this.confection_by_vente.controls[this.confection_by_vente.value.length - 1].value.quantite_necessaire !== 0) {
        this.inValideAddConfection = "";
        this.confection_by_vente.push(this.newArticleConfection());
      }
      else {
        this.inValideAddConfection = "Veuiller d'abord remplir le champ precedent pour pouvoir ajouter un autre champ, la quantite ne doit pas etre vide ou nulle";

      }
    }

  }



  removeSkill(i: number) {
    this.confection_by_vente.removeAt(i);
    this.prix_confection?.setValue(this.calculPrixConfection());
  }

  fonctionValueConfection(event: Event) {
    let confectionInput = event.target as HTMLInputElement;
    let inputmarge = this.margeInput.nativeElement as HTMLInputElement;
    inputmarge.setAttribute('readOnly', 'true');
    if (+confectionInput.value >= 5000) {
      inputmarge.removeAttribute('readOnly');
    }
  }

  getQuantite(event: Event, i: number) {
    console.log(this.articlesConfections);

    let inputQuantite = event.target as HTMLInputElement;
    onlyNumber(inputQuantite.value, inputQuantite);
    // this.confection_by_vente.controls[i].get("libelle")?.setValue({
    //   article_id: 1,
    //   libelle: ""
    // });
    // this.confection_by_vente.controls[i].value.quantite_necessaire.setValue(+inputQuantite.value);
    if(inputQuantite.value === '' || inputQuantite.value === '0')
    {
      inputQuantite.value = '1';
      this.confection_by_vente.controls[i].setValue({
        article_id : this.confection_by_vente.controls[i].value.article_id,
        libelle_article : this.confection_by_vente.controls[i].value.libelle_article,
       quantite_necessaire : 1,
       categorie :  this.confection_by_vente.controls[i].value.categorie
      });
    }
    console.log(this.confection_by_vente.controls[i].value);
    // console.log(this.calculPrixConfection());
    
     this.prix_confection?.patchValue(this.calculPrixConfection())
    
    if (isNaN(+inputQuantite.value[inputQuantite.value.length - 1])) {
      inputQuantite.value = inputQuantite.value.substring(0, inputQuantite.value.length - 1);
    }
  }

  serchArticleConfection(event: Event, i: number) {
    let articleSeached = event.target as HTMLInputElement;
    console.log(articleSeached.value);
    this.tabArticleSearched = [];
    if (articleSeached.value !== '') {
      let tabIdArticle: number[] = []
      for (let i = 0; i < this.confection_by_vente.length; i++) {
        console.log(this.confection_by_vente.controls[i].value);
        if (this.confection_by_vente.controls[i].value.article_id !== 0) {
          tabIdArticle.push(this.confection_by_vente.controls[i].value.article_id)
        }
      }
      console.log(tabIdArticle);

      this.tabArticleSearched = this.articlesConfections.filter((element: ArticleConfection) => (element.libelle.toLowerCase().includes(articleSeached.value.toLowerCase()) && !tabIdArticle.includes(element.id!)));
      this.idFormArraySelcted = i;
    }
    console.log(i);
    this.prix_confection?.setValue(this.calculPrixConfection());
  }

  liArticleConfectionSelected(article: ArticleConfection) {
    console.log(article);

    this.confection_by_vente.controls[this.idFormArraySelcted].setValue({
      article_id: article.id,
      quantite_necessaire: 1,
      libelle_article: article.libelle,
      categorie : article.categorie
    })
    console.log(this.confection_by_vente.value);
    
    this.inValideAddConfection = "";
    this.tabArticleSearched = [];
    // console.log(this.confection_by_vente.value);


  }

  isLibelleSelectedForQuantite(index: number) {
    return this.confection_by_vente.controls[index].get('article_id')?.invalid;
  }


  chargeImage(event: Event) {
    const input = event.target as HTMLInputElement;
    if (!extension(event)) {
      this.messageImageValide = "fichier incorrect, veuiller choisir une image";
      this.registerFormForSell.get("photo")?.setValue('');
      this.image = environment.defaultImage;
    }
    else {
      this.messageImageValide = '';
      const file = input.files?.[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e: any) => {
          this.image = e.target.result;
          console.log(this.image);
          this.registerFormForSell.get("photo")?.setValue(e.target.result)
        };
        reader.readAsDataURL(file);
      }
    }
  }




  inputValide(event: Event) {
    // let patern =  '/^[a-zA-Z0-9_]$'
    let valeurSaisie = event.target as HTMLInputElement;
    // console.log(this.categorie?.value!);


    notSpecialCharacters(valeurSaisie.value, valeurSaisie);


    this.reference?.setValue(getReference(valeurSaisie.value.trim(), this.categorie?.value!));

    if(valeurSaisie.value.length ===3)
    {
      console.log(valeurSaisie.value.substring(0,3));
      this.libelleExist.emit(valeurSaisie.value.substring(0,3))
    }
    if(this.articleVentesSearched.find((element : ArticleVente) => element.libelle.toLowerCase() === valeurSaisie.value.toLowerCase())){
      console.log(this.articleVentesSearched.find((element : ArticleVente) => element.libelle.toLowerCase() === valeurSaisie.value.toLowerCase()));
      // this.libelle?.setValue('');
    }
    


  }

  changeCategorie() {
    this.reference?.setValue(getReference(this.libelle?.value!, this.categorie?.value!));

    console.log(getReference(this.libelle?.value!, this.categorie?.value!));
  }

  onlyNumber(event: Event) {
    let valeurSaisie = event.target as HTMLInputElement;
    onlyNumber(valeurSaisie.value, valeurSaisie);
  }

  calculPrixConfection()
  {
    let prix : number = 0;
    for (let i = 0; i < this.confection_by_vente.length; i++) {
      prix = prix + this.confection_by_vente.controls[i].value.quantite_necessaire
      
    }
    return prix;
  }
}
