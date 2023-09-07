import { Component, EventEmitter, Input, Output } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ArticleVente } from 'src/app/iterfaces/article-vente';
import { Links } from 'src/app/iterfaces/links';
import { onlyNumber } from 'src/app/shared/fonctionsSchared/Functions';

@Component({
  selector: 'app-list-vente',
  templateUrl: './list-vente.component.html',
  styleUrls: ['./list-vente.component.css']
})
export class ListVenteComponent {

  @Input() articleVentes :ArticleVente[] = []; 
  @Input() links : Links[] = []; 
  @Output() page = new EventEmitter<number>();
  @Output() articleToEdit = new EventEmitter<ArticleVente>();
  @Output() idToDelete = new EventEmitter<number>();
  @Output() trie = new EventEmitter<boolean>();
  @Output() perpage = new EventEmitter<number>();
    @Output() searchedArticle = new EventEmitter<string>();
  trieValue : boolean = true;
  idsToDeleted : number[] = [];
  nombreArticleParLigne !: FormGroup;


  constructor(private fb: FormBuilder){
    this.nombreArticleParLigne = fb.group({
      paginationNumber : [3, [Validators.required, Validators.min(1)]]
    })
  }

  parPageNombreDeligne()
  {
    console.log(this.nombreArticleParLigne.value);
    this.perpage.emit(this.paginationNumber?.value);
    
  }

  get paginationNumber()
  {
    return this.nombreArticleParLigne.get('paginationNumber');
  }
  changePage(page : number)
  {
    this.page.emit(page);
  }

  triage()
  {
    this.trieValue = !this.trieValue;
    this.trie.emit(this.trieValue);
  }

  sendArticleToEdit(article : ArticleVente)
  {
    this.articleToEdit.emit(article);
    console.log(article);
    
  }
  sendIdTodelete(event : Event,id : number)
  {
    if(this.idsToDeleted.filter((element : number) => element === id).length !== 0)
    {
      this.idToDelete.emit(id);
    }
    else{
      let count = 3;
      let button = event.target as HTMLButtonElement;
      this.idsToDeleted.push(id);
      button.classList.add('bg-green-700');
      button.classList.remove('bg-red-600');
      button.textContent = `OK(${count})`
      let timeOut = setInterval(()=>{
        count--;
        button.textContent = `OK(${count})`
        if(count == 0)
        {
          button.classList.remove('bg-green-700');
          button.classList.add('bg-red-600');
          this.idsToDeleted = this.idsToDeleted.filter((element) => element !== id);
          button.textContent = "supp"
          clearInterval(timeOut);
        }
      },1000)
    }
  }

  pagination(event : Event)
  {
    let inputValue = event.target as HTMLInputElement;
    onlyNumber(inputValue.value, inputValue);
  }

  searchArticleVente(event : Event){
    let input = event.target as HTMLInputElement;
    this.searchedArticle.emit(input.value);



  }

}
