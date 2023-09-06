import { Categories } from "./categories";

export interface ConfectionVente {

    article_id : number,
    categorie : Categories,
    libelle_article : string,
    quantite_necessaire : number
}
