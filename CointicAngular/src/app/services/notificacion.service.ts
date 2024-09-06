import { Injectable } from '@angular/core';
declare var $: any;
@Injectable({
  providedIn: 'root'
})
export class NotificacionService {

  constructor() { }
  crearNotificacion(message, icon, type) {
    $.notify({
      message: message,
      icon: icon
    }, {
      type: type,
      timer: 10000,
      placement: {
        from: 'top',
        align: 'right'
      }, template: `
      <div class="alert alert-{0} col-xl-4 col-lg-4 col-11 col-sm-4 col-md-4 alert-with-icon">
          <button mat-button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="material-icons" style="color: white;">close</i></button>
          <i data-notify="icon"></i> 
          <span data-notify="title">{1}</span>
          <span data-notify="message">{2}</span>
      </div>`
    });
  }
}
