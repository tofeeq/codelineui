import { Component } from '@angular/core';
import { Router } from '@angular/router'; 

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})


export class AppComponent {
  	title = "Main App"; //model title

  	constructor (private router: Router) {
  	}

  	weathersearch(keyword) {
      this.router.navigate(['/weather/search/' + keyword]);
  }
}
