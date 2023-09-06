
/* 
* Fonction pour empecher à l'utilisateur de saisir des caracteres speciaux dans un input. Il prends en parametre la valeur de l'input et le champ ()
*  exemple d'utilisation 
  inputValide(event: Event) {
    let valeurSaisie = event.target as HTMLInputElement;
   notSpecialCharacters(valeurSaisie.value.trim(), valeurSaisie);
  }
 */

import { Categories } from "src/app/iterfaces/categories";
import { environment } from "src/environments/environment.development";

export function notSpecialCharacters(text: string, input: HTMLInputElement) {
  let regular = /[^a-zA-Z0-9 ]$/;
  let value = text.replace(regular, "");
  input.value = value;
}

export function onlyNumber(text: string, input: HTMLInputElement) {
  let regular = /[^0-9]*$/g;
  let value = text.replace(regular, "");
  input.value = value;
}




export function chargeImage(event: Event) {
  const input = event.target as HTMLInputElement;
  const file = input.files?.[0];

  if (file) {
    const reader = new FileReader();
    reader.onload = (e: any) => {
      // this.image = e.target.result;
      // console.log(this.image);
      // this.registerFormForSell.get("photo")?.setValue(e.target.result)

    };
    reader.readAsDataURL(file);
  }
}

export function  getReference(libelleArticle : string , categorie : Categories | string){
  let libelle = libelleArticle.substring(0,3);
  if(categorie === '')
  {
    return environment.ref+"-"+libelle.toUpperCase()+"-"+(0)

  }
  else{
    let cat : Categories = categorie as Categories
    console.log(cat);
    
    return environment.ref+"-"+libelle.toUpperCase()+"-"+cat.libelle.toUpperCase()+"-"+(cat.enregistrement_categorie + 1)
  }

}