import { Categories } from "./categories";
import { Links } from "./links";

export interface DataResponse <T> {
    links ?: Links[],
    data : T[],
    message : string,
    categories : Categories[],
    status : boolean

    



}
