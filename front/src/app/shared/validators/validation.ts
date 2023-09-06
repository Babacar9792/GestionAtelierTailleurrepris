import { AbstractControl, AsyncValidatorFn, ValidationErrors, ValidatorFn } from "@angular/forms";
import { ArticleVente } from "src/app/iterfaces/article-vente";
 
import { Observable, of } from 'rxjs';
import { ParentService } from "src/app/services/parent.service";

export function minValueNumber(valueMin: number): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    const value = control.value as number
    if (value < valueMin) {
      return { "minvalue": true }
    }
    return null;
  }

}

export function ValidationConfectionByVente(categoriesMin: any[]): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    const value = control.value;

    if (!value || !Array.isArray(value)) {
      return null;
    }
    //   let categoriesMin = this.allCategories.filter((element: Categorie) => environment.minConfection.includes(element.libelle)).map((element: Categorie) => element.id);

    const allCategoriesMinIncluded = categoriesMin.every(id =>
      value.some((element: any) => element.categorie && element.categorie.id === id)
    );

    if (!allCategoriesMinIncluded) {
      return { 'minCategorieConfectionRequired': true };
    }

    return null;
  };
}

export function goodExtension(): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    const value = control.value as string
    const extension: string = value.split('.')[value.length - 1];
    let tabExtension: string[] = ["jpeg", "jpg", "png"];

    if (tabExtension.filter((element: string) => element === value).length === 0) {

      return { 'extenxionInvalid': true }
    }

    return null;
  }


}


export function extension(event: Event): boolean {
  let valueInput = event.target as HTMLInputElement;
  const extensionInput: string = valueInput.value.split('.')[valueInput.value.split('.').length - 1];
  console.log(extension);
  let tabExtension: string[] = ["jpeg", "jpg", "png"];
  if (tabExtension.filter((element: string) => element === extensionInput).length === 0) {

    return false;
  }
  return true;
}

export function libelleUnique(tabArticle: ArticleVente[]): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    const value = control.value as string
    if(tabArticle.find((element : ArticleVente) => element.libelle.toLowerCase() === control.value.toLowerCase())){
      return {"libelleExist" : true};
    }
    return null;
  }
}


 

 
// export function gte(control: AbstractControl): 
//          Observable<ValidationErrors> | null {
 
//     const v:string=control.value;
 
//     console.log(v)
    
//     // if (isNaN(v)) {
//     //   return of({ 'gte': true, 'requiredValue': 10 })
//     // }      
 
//     // if (v <= 10) {
//     //   return of({ 'gte': true, 'requiredValue': 10 })
//     // } 
 
//     return of(null)
 
// }



/* 


export function createPasswordStrengthValidator(): ValidatorFn {
    return (control:AbstractControl) : ValidationErrors | null => {

        const value = control.value;

        if (!value) {
            return null;
        }

        const hasUpperCase = /[A-Z]+/.test(value);

        const hasLowerCase = /[a-z]+/.test(value);

        const hasNumeric = /[0-9]+/.test(value);

        const passwordValid = hasUpperCase && hasLowerCase && hasNumeric;

        return !passwordValid ? {passwordStrength:true}: null;
    }
}*/