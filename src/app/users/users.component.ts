import { Component } from '@angular/core';

@Component({
  //selector: 'app-users',
  selector: '.app-users',
  templateUrl: './users.component.html',
  styleUrls: ['./users.component.css']
})


export class UsersComponent {
  status = false
  userId : number = 12
  username : string  = "tofeeq";
  
  constructor() {
  	setTimeout(
  		() => {
  			this.status = true;
  		}, 2000
  	);
  }

  getUsername() {
  	if (this.status)
  		return this.username + '(' + this.userId + ')';
  	return 'no active user found'
  }

  onChangeStatus() {
  	this.status = !this.status;
  }

  onAddUser(event: Event) {
  	this.username = (<HTMLInputElement>event.target).value;
  }
}
