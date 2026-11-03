importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js');

firebase.initializeApp({
  apiKey: "AIzaSyCCj-N6MdMKaFAzxb02G2MmGm9BeKNeKCU",
  authDomain: "laravelordernotify.firebaseapp.com",
  projectId: "laravelordernotify",
  storageBucket: "laravelordernotify.firebasestorage.app",
  messagingSenderId: "868388646146",
  appId: "1:868388646146:web:856d59ae681ea3b821661b",
  measurementId: "G-2N94G3NPC0"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/firebase-logo.png'
  };
  self.registration.showNotification(notificationTitle, notificationOptions);
});
