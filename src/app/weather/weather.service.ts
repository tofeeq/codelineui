import { Injectable } from '@angular/core';
import { Headers, Http } from '@angular/http';

//to convert observable to promise we need to use toPromise operator
import 'rxjs/add/operator/toPromise';

import { Weather } from './weather';

@Injectable()
export class WeatherService {

	private apiUrl = 'http://localhost/codeline/ui/weather.php';
	private headers = new Headers({'Content-Type': 'application/json'});

	constructor(private http: Http) {

	}


	private handleError(error: any): Promise<any> {
		console.error('An error occurred', error); // for demo purposes only
	    return Promise.reject(error.message || error);
	}

	private locations = ["Istanbul", "Berlin", "London", "Helsinki", "Dublin", "Vancouver"];

	getWeathers(): Promise<Weather[]> {
	  return this.http.get(this.apiUrl + '?command=location', {
	  			params : {"locations[]" : this.locations}
	  		})
	  		.toPromise()
	  		//.then(response => response.json())
	  		.then(response => {
	  			var data = [];
	  			var weatherData = response.json();

	  			for (var i in weatherData) {
	  				var weatherjson = JSON.parse(weatherData[i]);
	  				var w = weatherjson.consolidated_weather[0];


	  				var weath = new Weather();
	  				weath.city = i;
	  				weath.temprature = Math.round(w.the_temp);
	  				weath.mintemprature = Math.round(w.min_temp);
	  				weath.maxtemprature = Math.round(w.max_temp);
	  				weath.icon = 'https://www.metaweather.com/static/img/weather/' 
	  					+ w.weather_state_abbr + '.svg';
	  				data.push(weath);
	  			}
	  			//return response.json()
	  			return data;
	  		})
	  		.catch(this.handleError);
	}


}