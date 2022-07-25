var firebaseConfig = {
  apiKey: "AIzaSyDZCgvUdwc71BjVRIHO7dqoPPe_FO5yZ5w",
  authDomain: "p2b-app.firebaseapp.com",
  databaseURL: "https://p2b-app.firebaseio.com",
  projectId: "p2b-app",
  storageBucket: "p2b-app.appspot.com",
  messagingSenderId: "319358867912",
  appId: "1:319358867912:web:ae9befc8473cfaf52c08fd"
};

firebase.initializeApp(firebaseConfig);

var firebasedb = firebase.firestore();
// const messaging = firebase.messaging();

window.real_time_chat_get = function real_time_chat_get(rela_time_room_ids) {
  var room_ids = rela_time_room_ids;
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  //  var  room_ids=['room_60','room_59',"room_100"];
  for (var i = 0; i < room_ids.length; i++) {
    // console.log(room_ids[i]);
    firebasedb.collection("chat").doc("room_" + room_ids[i])
      .collection('messages')
      .onSnapshot((messages) => {
        var gloable_messages = [];

        messages.forEach((doc, index, arr) => {
          // console.log(doc.data().action_id);
          $('.loadMediaChat_' + doc.data().action_id).empty();
          // console.log(doc);
          // console.log(index);
          // console.log(arr);
          // console.log(doc.data());
          gloable_messages.push(doc.data());
        });
        // console.log(gloable_messages);
        $.ajax({
          url: APP_URL+'/admin/getactionchat',
          type: 'POST',
          data: {
            _token: CSRF_TOKEN,
            "messages": gloable_messages,
          },
          dataType: 'JSON',
          async: true,
          success: function (data) {
            // console.log(data.document);
            $('.dynamicAppendChat_' + data.actionId).html(data.document);
            $('.chat_pre_loader_' + data.actionId).hide();
            $('.SendChat_' + data.actionId).removeAttr("style");

            var d = $("#mainChatSection");
            if (d.length != 0 && d.length != undefined) {

              d.scrollTop(d[0].scrollHeight);
            }
          }
        });




      }, (error) => {
        console.log("snapshot");
      });

    var d = $("#mainChatSection");
    if (d.length != 0 && d.length != undefined) {
      d.scrollTop(d[0].scrollHeight);
    }

  }
  // console.log("outside");
  // console.log(gloable_messages);
}


if (rela_time_room_ids.length) {

  real_time_chat_get(rela_time_room_ids);

}




// Get registration token. Initially this makes a network call, once retrieved
// messaging
// .requestPermission()
// .then(function () {
//   // MsgElem.innerHTML = "Notification permission granted." 
//   console.log("Notification permission granted.");

//      messaging.getToken({ vapidKey: 'BE9-uW2lkXMUu2aU47AiwW7R6rvvlKJtTjlD2Wgn0KLtrhKYzqpYWoI9KihBe2Wz_eTHf2NfzwnN58WuiHFWoT8' }).then((currentToken) => {
//       if (currentToken) {

//           // $.ajaxSetup({
//           //   headers: {
//           //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//           //   }
//           // });
//           // $.ajax({
//           //     url: '{{ route("store.token") }}',
//           //     type: 'POST',
//           //     data: {
//           //         token: currentToken
//           //     },
//           //     dataType: 'JSON',
//           //     success: function (response) {
//           //         alert('Token stored.');
//           //     },
//           //     error: function (error) {
//           //         alert(error);
//           //     },
//           // });


//         console.log(currentToken);
//       } else {
//         // Show permission request UI
//         console.log('No registration token available. Request permission to generate one.');
//         // ...
//       }
//     }).catch((err) => {
//       console.log('An error occurred while retrieving token. ', err);
//       // ...
//     });

// })
// .then(function(token) {
//   // print the token on the HTML page
//   console.log("Device token is : <br>" + token);

// })
// .catch(function (err) {
// console.log("Unable to get permission to notify.", err);
// });







// if ('serviceWorker' in navigator) {
//   navigator.serviceWorker.register('./firebase-messaging-sw.js')
//     .then(function(registration) {
//       console.log('Registration successful, scope is:', registration.scope);
//     }).catch(function(err) {
//       console.log('Service worker registration failed, error:', err);
//     });
// }





