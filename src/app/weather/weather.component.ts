import { Component, OnInit } from '@angular/core';
import { HttpModule } from '@angular/http';
import { Weather } from './weather';
import { WeatherService } from './weather.service';


@Component({
  selector: 'weather',
  templateUrl: './weather.component.html',
  styleUrls: ['./weather.component.css']
})


export class WeatherComponent implements OnInit {
  	title = "Weather"; //model 
  	
  	weathers : Weather[];

  	constructor (private weatherService: WeatherService) {
  	}

	ngOnInit() : void {
		this.weatherService.getWeathers().then(response => this.weathers = response );		
	}

}
