import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';

import { DashboardComponent } from './dashboard/dashboard.component';
import { WeatherComponent } from './weather/weather.component';
import { UsersComponent } from './users/users.component';

//load Router
import { AppRoutingModule } from './app-routing.module';


 
//load components
import { AppComponent } from './app.component';

//loading services
import { WeatherService } from './weather/weather.service';

@NgModule({

  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    AppRoutingModule
  ],
  
  declarations : [
    AppComponent,
    UsersComponent,
    DashboardComponent,
    WeatherComponent,
  ],

  providers: [WeatherService], //singleton to share across all

  bootstrap: [AppComponent]

})
export class AppModule { }
