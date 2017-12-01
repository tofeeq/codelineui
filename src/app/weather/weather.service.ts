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

	
	getWeathers(): Promise<Weather[]> {
	  return this.http.get(this.apiUrl + '?command=location')
	  				.toPromise()
	  				.then(response => response.json().data as Weather[])
	  				.catch(this.handleError);
	}


}