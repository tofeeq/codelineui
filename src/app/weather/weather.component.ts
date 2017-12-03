import { Component, OnInit } from '@angular/core';
import { HttpModule } from '@angular/http';
import { Weather } from './weather';
import { WeatherService } from './weather.service';
import { Router, ActivatedRoute,  Params } from '@angular/router'; 
import { Location }                 from '@angular/common';
import 'rxjs/add/operator/switchMap';
import { Observable } from 'rxjs/Observable';

@Component({
  selector: 'weather',
  templateUrl: './weather.component.html',
  styleUrls: ['./weather.component.css']
})


export class WeatherComponent implements OnInit {
  	title = "Weather"; //model 
  	
  	weathers: Weather[];

  	constructor (private weatherService: WeatherService, private route: ActivatedRoute, private location: Location, private router: Router) {
      
    }

    ngOnChanges () : void {
    }
    

    ngDoCheck () : void {
    }

    
    ngOnInit() : void {
        this.route.params.switchMap(
          (params: Params) => {
            if (params['location'])
              return this.weatherService.findWeathers(
                params['location']
              )
            else
              return this.weatherService.getWeathers() 
          }
         )
          .subscribe(weathers => this.weathers = weathers)
          ;	
	  }


  

    weathersearch(keyword) {
        console.log("event clicked", "navigation to: ", '/weather/search/' + keyword);
        this.router.navigate(['/weather/search/' + keyword]);
    }
  
}
