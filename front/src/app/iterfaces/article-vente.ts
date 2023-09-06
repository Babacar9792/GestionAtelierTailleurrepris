import { Categories } from "./categories";
import { ConfectionVente } from "./confection-vente";

export interface ArticleVente {
    id ?: number,
    libelle : string,
    marge : number,
    photo : string,
    prix_confection : number,
    prix_vente:number,
    promo : number,
    quantite_stock : number,
    reference : string,
    categorie : Categories,
    confection_by_vente : ConfectionVente[]
}
