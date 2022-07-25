require('./bootstrap');


let userId=document.head.querySelector("meta[name='pusher-id']").content;
let pushertype=document.head.querySelector("meta[name='pusher-id']").title;
console.log(userId);
console.log(pushertype);
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

if(pushertype!="" && pushertype!="undefined"){
    if(pushertype=="emp"){

        Echo.private('App.Models.Role.'+userId).notification((notification) => {
            console.log(notification);    
            $.ajax({
                url: '/admin/get-notifications-by-id',
                type: 'post',
                async: true,
                data: {'notification_id':notification.chat.Notification_id, _token:CSRF_TOKEN},
                success: function (response) {
                    $(".dropdown-notification-scroll").prepend(response);
                    count=Number($(".notification-count").text());
                    $(".notification-count").text(count+1);
                },
                complete: function(){
                    $('.pre_loader').hide();
                },
                error: function (error) {
                    errorHandler(error);
                }
            });
        
        });
    }else{
        Echo.private('App.Models.Users.'+userId).notification((notification) => {
            console.log(notification);    
            $.ajax({
                url: '/admin/get-notifications-by-id',
                type: 'post',
                async: true,
                data: {'notification_id':notification.chat.Notification_id, _token:CSRF_TOKEN},
                success: function (response) {
                    $(".dropdown-notification-scroll").prepend(response);
                    count=Number($(".notification-count").text());
                    $(".notification-count").text(count+1);
                },
                complete: function(){
                    $('.pre_loader').hide();
                },
                error: function (error) {
                    errorHandler(error);
                }
            }); 
        });
    }

}



/**
 * jQuery
 */
window.$ = window.jQuery = require('jquery');
/**
 * jQuery migrate
 */
require('../../public/assets/global/plugins/jquery-migrate.min.js');
/**
 * jQuery ui
 * IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip
 */
require('jquery-ui');
/**
 * bootstrap js
 */
require('../../public/assets/global/plugins/bootstrap/js/bootstrap.min.js');
/**
 * bootstrap js
 */
// require('../../public/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js');
/**
 * jquery-slimscroll
 */
require('jquery-slimscroll');
/**
 * bootstrap switch js
 */
require('../../public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js');
/**
 * jquery validation min js
 */
require('../../public/assets/js/jquery.validation.min.js');
/**
 * datatables
 */
//  require( 'datatables.net-bs' );
//  require( 'datatables.net-buttons');