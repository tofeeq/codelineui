import { Component, OnInit } from '@angular/core';
import { HttpModule } from '@angular/http';
import { Weather } from './weather';
import { WeatherService } from './weather.service';
import { Router } from '@angular/router'; 

@Component({
  selector: 'weather',
  templateUrl: './weather.component.html',
  styleUrls: ['./weather.component.css']
})


export class WeatherComponent implements OnInit {
  	title = "Weather"; //model 
  	
  	weathers ;

  	constructor (private weatherService: WeatherService, private router: Router) {
  	}

	ngOnInit() : void {
    if (this.router.url.match('/search')) {
        var parts = this.router.url.split("/");
        this.weatherService.findWeathers(parts[parts.length - 1]).then(
          response => {
            this.weathers = response 
          }
        );
    } else {

  		this.weatherService.getWeathers().then(
          response => {
            this.weathers = response 
          }
        );
    }		
	}

  
}
