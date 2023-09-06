import { ArticleConfection } from "./article-confection";
import { ArticleVente } from "./article-vente";
import { DataResponse } from "./data-response";

export interface ResponseArticleVente<V> extends DataResponse<ArticleVente> {
    articles : V[]


}
