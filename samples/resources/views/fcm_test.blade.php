<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<meta http-equiv='cache-control' content='no-cache'>
	<meta http-equiv='expires' content='0'>
	<meta http-equiv='pragma' content='no-cache'>

	<title>FCM Test</title>
</head>
<body>
	<!-- <div id="token_div"></div>
	<div id="permission_div"></div>
	<div id="messages"></div> -->

	<button onclick="deleteToken();">Delete Token</button>

	<script
	src="https://code.jquery.com/jquery-2.2.4.min.js"
	integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
	crossorigin="anonymous"></script>

	<!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
	<script src="https://www.gstatic.com/firebasejs/7.6.0/firebase-app.js"></script>

	<!-- If you enabled Analytics in your project, add the Firebase SDK for Analytics -->
	<!-- <script src="https://www.gstatic.com/firebasejs/7.6.0/firebase-analytics.js"></script> -->

	<!-- Add Firebase products that you want to use -->
	<!-- <script src="https://www.gstatic.com/firebasejs/7.6.0/firebase-auth.js"></script> -->
	<!-- <script src="https://www.gstatic.com/firebasejs/7.6.0/firebase-firestore.js"></script> -->
	<script src="https://www.gstatic.com/firebasejs/7.6.0/firebase-messaging.js"></script>

	<script>
		// Your web app's Firebase configuration
		var firebaseConfig = {
			apiKey: "your-apiKey",
			authDomain: "your-authDomain",
			databaseURL: "your-databseUrl",
			projectId: "your-projectId",
			storageBucket: "your-storageBucket",
			messagingSenderId: "your-messagingSenderId",
			appId: "your-appId",
			// measurementId: "your-measurementId"
		};

		// Initialize Firebase
		firebase.initializeApp(firebaseConfig);
		// firebase.analytics();

		// Retrieve Firebase Messaging object.
		const messaging = firebase.messaging();

		// IDs of divs that display Instance ID token UI or request permission UI.
		// const tokenDivId = 'token_div';
		// const permissionDivId = 'permission_div';

		// [START refresh_token]
		// Callback fired if Instance ID token is updated.
		messaging.onTokenRefresh(() => {
			messaging.getToken().then((refreshedToken) => {
				console.log('Token refreshed.');
				// Indicate that the new Instance ID token has not yet been sent to the
				// app server.
				setTokenSentToServer(false);
				// Send Instance ID token to app server.
				sendTokenToServer(refreshedToken);
				// [START_EXCLUDE]
				// Display new Instance ID token and clear UI of all previous messages.
				resetUI();
				// [END_EXCLUDE]
			}).catch((err) => {
				console.log('Unable to retrieve refreshed token ', err);
				// showToken('Unable to retrieve refreshed token ', err);
			});
		});
		// [END refresh_token]

		// [START receive_message]
		// Handle incoming messages. Called when:
		// - a message is received while the app has focus
		// - the user clicks on an app notification created by a service worker
		//   `messaging.setBackgroundMessageHandler` handler.
		messaging.onMessage((payload) => {
			console.log('Message received. ', payload);

			// todo : ajax to save notif to notification table to get notif id then update .notif-menu element
			// $('.notif-menu').find('.dropdown-menu').prepend(`
			//     <li class="notif-item" data-notif="" style="border-bottom:1px solid #f7f7f7">
			//         <a href="#">
			//             `+payload.notification.title+` <br>
			//             <small>`+payload.notification.body+`</small>
			//         </a>
			//     </li>
			// `);

			// [START_EXCLUDE]
			// Update the UI to include the received message.
			// appendMessage(payload);
			// [END_EXCLUDE]
		});

		// [END receive_message]
		function resetUI() {
			// clearMessages();
			//     showToken('loading...');
			//     // [START get_token]
			//     // Get Instance ID token. Initially this makes a network call, once retrieved
			//     // subsequent calls to getToken will return from cache.
			messaging.getToken().then((currentToken) => {
				if (currentToken) {
					console.log(currentToken);

					$.ajax({
						url: "{{url('api/notif-token')}}",
						type: "post",
						data: {
							user_id: 1,
							notif_token: currentToken
						}
					}).success(function(res) {
						// console.log(res);
					}).error(function(res) {
						// console.log(res);
					});

					// // TODO : ini untuk testing notifikasi
					// $.ajax({
					// 	url: "{{url('api/send-notif')}}",
					// 	type: "post",
					// 	data: {
					// 		notif_token: [currentToken],
					// 		notif: {
					// 			"title" : "FCM Message Test ok",
					// 			"body" : "This is a message from FCM"
					// 		}
					// 	}
					// }).success(function(res) {
					// 	console.log('success => ', res);
					// }).error(function(res) {
					// 	console.log('error => ', res);
					// });

					sendTokenToServer(currentToken);
					// updateUIForPushEnabled(currentToken);
				} else {
					// Show permission request.
					console.log('No Instance ID token available. Request permission to generate one.');
					// Show permission UI.
					// updateUIForPushPermissionRequired();
					setTokenSentToServer(false);
				}
			}).catch((err) => {
				console.log('An error occurred while retrieving token. ', err);
				// showToken('Error retrieving Instance ID token. ', err);
				setTokenSentToServer(false);
			});
			// [END get_token]
		}

		// function showToken(currentToken) {
		// 	console.log(currentToken);
		// 	// Show token in console and UI.
		// 	// var tokenElement = document.querySelector('#token');
		// 	// tokenElement.textContent = currentToken;
		// }

		// Send the Instance ID token your application server, so that it can:
		// - send messages back to this app
		// - subscribe/unsubscribe the token from topics
		function sendTokenToServer(currentToken) {
			if (!isTokenSentToServer()) {
				console.log('Sending token to server...');
				// TODO(developer): Send the current token to your server.
				setTokenSentToServer(true);
			} else {
				console.log('Token already sent to server so won\'t send it again ' +
					'unless it changes');
			}
		}

		function isTokenSentToServer() {
			return window.localStorage.getItem('sentToServer') === '1';
		}

		function setTokenSentToServer(sent) {
			window.localStorage.setItem('sentToServer', sent ? '1' : '0');
		}

		// function showHideDiv(divId, show) {
		// 	// const div = document.querySelector('#' + divId);
		// 	// if (show) {
		// 	//     div.style = 'display: visible';
		// 	// } else {
		// 	//     div.style = 'display: none';
		// 	// }
		// }

		function requestPermission() {
			console.log('Requesting permission...');
			// [START request_permission]
			Notification.requestPermission().then((permission) => {
				if (permission === 'granted') {
					console.log('Notification permission granted.');
					// TODO(developer): Retrieve an Instance ID token for use with FCM.
					// [START_EXCLUDE]
					// In many cases once an app has been granted notification permission,
					// it should update its UI reflecting this.
					resetUI();
					// [END_EXCLUDE]
				} else {
					console.log('Unable to get permission to notify.');
				}
			});
			// [END request_permission]
		}

		function deleteToken() {
			// Delete Instance ID token.
			// [START delete_token]
			messaging.getToken().then((currentToken) => {
				console.log('Get tokenn to delete. ', currentToken);
				messaging.deleteToken(currentToken).then((res) => {
					console.log('Token deleted. ', res);
					setTokenSentToServer(false);
					// [START_EXCLUDE]
					// Once token is deleted update UI.
					resetUI();
					// [END_EXCLUDE]
				}).catch((err) => {
					console.log('Unable to delete token. ', err);
				});
				// [END delete_token]
			}).catch((err) => {
				console.log('Error retrieving Instance ID token. ', err);
				// showToken('Error retrieving Instance ID token. ', err);
			});
		}

		// // Add a message to the messages element.
		// function appendMessage(payload) {
		// 	console.log(payload);
		// 	const messagesElement = document.querySelector('#messages');
		// 	const dataHeaderELement = document.createElement('h5');
		// 	const dataElement = document.createElement('pre');
		// 	dataElement.style = 'overflow-x:hidden;';
		// 	dataHeaderELement.textContent = 'Received message:';
		// 	dataElement.textContent = JSON.stringify(payload, null, 2);
		// 	messagesElement.appendChild(dataHeaderELement);
		// 	messagesElement.appendChild(dataElement);

		// 	// alert('<pre>'+JSON.stringify(payload, null, 2)+'</pre>');
		// }

		// // Clear the messages element of all children.
		// function clearMessages() {
		// 	const messagesElement = document.querySelector('#messages');
		// 	while (messagesElement.hasChildNodes()) {
		// 		messagesElement.removeChild(messagesElement.lastChild);
		// 	}
		// }

		// function updateUIForPushEnabled(currentToken) {
		// 	showHideDiv(tokenDivId, true);
		// 	showHideDiv(permissionDivId, false);
		// 	showToken(currentToken);
		// }

		// function updateUIForPushPermissionRequired() {
		// 	showHideDiv(tokenDivId, false);
		// 	showHideDiv(permissionDivId, true);
		// }

		// resetUI();
		requestPermission();
	</script>
</body>
