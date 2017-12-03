import { Component, OnInit } from '@angular/core';
import { HttpModule } from '@angular/http';
import { Weather } from './weather';
import { WeatherService } from './weather.service';
import { Router, ActivatedRoute,  Params } from '@angular/router'; 
import { Location }                 from '@angular/common';
import 'rxjs/add/operator/switchMap';
import { Observable } from 'rxjs/Observable';
import 'rxjs/add/observable/of';

@Component({
  selector: 'weather',
  templateUrl: './weather.component.html',
  styleUrls: ['./weather.component.css']
})


export class WeatherComponent implements OnInit {
  	title = "Weather"; //model 
  	
  	weathers: any;
    errormsg : string ;

  	constructor (private weatherService: WeatherService, private route: ActivatedRoute, private location: Location, private router: Router) {
      
    }

    ngOnChanges () : void {
    }
    

    ngDoCheck () : void {
    }

    

    ngOnInit() : void {

        this.route.params.switchMap(
          (params: Params) => {
            if (params['location']) {
              this.errormsg = "";
              console.log(params['location'])
              return this.weatherService.findWeathers(params['location'])
                .catch(err => {
                  this.errormsg = "No results were found. Try changing the keyword!"
                  return Observable.of(err)
              });
            } else {
              return this.weatherService.getWeathers() 
            }
          }
         )
          .subscribe(
            response => {
              console.log("weathers found")
              console.log(response)
              if (!this.errormsg) {
                this.weathers = response;
              } else {
                this.weathers = ""
              }
            },
            err => {
              console.log("error in weathers")
              this.weathers = ""
              this.errormsg =  "No results were found. Try changing the keyword!"
            },
            () => console.log("completed")
          )
          ;	
	  }


  

    weathersearch(keyword) {
        console.log("event clicked", "navigation to: ", '/weather/search/' + keyword);
        this.router.navigate(['/weather/search/' + keyword]);
    }
  
}
