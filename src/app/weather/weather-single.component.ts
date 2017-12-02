import { Component, Input, OnInit } from '@angular/core';
import { ActivatedRoute, Params }   from '@angular/router';
import { Location }                 from '@angular/common';
import { WeatherService } from './weather.service';
import { Weather } from './weather';
import 'rxjs/add/operator/switchMap';

@Component({
  selector: 'weather-single',
  templateUrl: './weather-single.component.html',
  styleUrls: ['./weather-single.component.css'],
})

export class WeatherSingleComponent implements OnInit {
	@Input() weather : Weather;
	
	constructor(private weatherService : WeatherService, private route: ActivatedRoute, private location: Location) {
	}
	//+ operator converts string to number
	ngOnInit() {
		 this.route.params.switchMap(
		 	(params: Params) => this.weatherService.getWeather(
		 		+params['id']
		 	)
		 )
	    .subscribe(weather => this.weather = weather)
	    ;
	}


	goBack(): void {
	  this.location.back();
	}
}