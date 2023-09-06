import { Categories } from "./categories";

export interface ArticleConfection {
    id ?: number,
    libelle : string,
    prix : number,
    reference : string,
    stock : number,
    categorie : Categories
}
