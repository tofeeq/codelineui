import { NgModule }              from '@angular/core';
import { RouterModule, Routes }  from '@angular/router';


//LOADING COMPONENTS required by routes
import { UsersComponent } from './users/users.component';
import { DashboardComponent } from './dashboard/dashboard.component';



 const ROUTES : Routes = [
    {
      path: '',
      redirectTo: '/dashboard',
      pathMatch: 'full'
    },
    {
      path: 'dashboard',
      component: DashboardComponent
    }, 
    {
      path: 'users',
      component: UsersComponent
    }    
  ];

@NgModule({
  imports: [
    RouterModule.forRoot(ROUTES)
  ],
  exports: [
    RouterModule
  ]

})

export class AppRoutingModule {}