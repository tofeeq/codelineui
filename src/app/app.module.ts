import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';

import { DashboardComponent } from './dashboard/dashboard.component';
import { ServerComponent } from './server/server.component';
import { UsersComponent } from './users/users.component';

//load Router
import { AppRoutingModule } from './app-routing.module';

//for web api simulations
import { InMemoryWebApiModule } from 'angular-in-memory-web-api';
import { InMemoryDataService }  from './in-memory-data.service';



 
//load components
import { AppComponent } from './app.component';

 //loading services

@NgModule({

  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    InMemoryWebApiModule.forRoot(InMemoryDataService),
    AppRoutingModule
  ],
  
  declarations : [
    AppComponent,
    ServerComponent,
    UsersComponent,
    DashboardComponent,
  ],

  providers: [], //singleton to share across all

  bootstrap: [AppComponent]

})
export class AppModule { }
