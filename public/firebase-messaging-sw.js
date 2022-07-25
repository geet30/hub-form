importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');


var firebaseConfig = {
    apiKey: "AIzaSyDZCgvUdwc71BjVRIHO7dqoPPe_FO5yZ5w",
    // authDomain: "p2b-app.firebaseapp.com",
    // databaseURL: "https://p2b-app.firebaseio.com",
    projectId: "p2b-app",
    // storageBucket: "p2b-app.appspot.com",
    messagingSenderId: "319358867912",
    appId: "1:319358867912:web:ae9befc8473cfaf52c08fd"
  };
  
  firebase.initializeApp(firebaseConfig);
  
  const messaging = firebase.messaging();
  

messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    // Customize notification here
    const notificationTitle = 'Background Message Title';
    const notificationOptions = {
      body: 'Background Message body.',
      icon: '/firebase-logo.png'
    };
  
    self.registration.showNotification(notificationTitle,
      notificationOptions);
      
});
