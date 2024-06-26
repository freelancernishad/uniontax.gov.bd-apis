<!DOCTYPE html>

<html lang="en">

<head>


    <head>

        <meta charset="UTF-8">

        <title>:: ইউনিয়ন পরিষদ ডিজিটাল সেবা সিস্টেম ::</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>

    <body>


    <form onsubmit="return false">

    <div class="container">
        <div class="row">




            <div class="col-md-6">
                <div class="form-group">
                    <label for="roles">Roles</label>
                    <select class="form-control" id="roles" name="roles" required>
                        <option value="">Select</option>
                        <option>Secretary</option>
                        <option>Chairman</option>
                    </select>
                </div>
            </div>



        </div>
    <button type="submit" class="btn btn-info" onclick="IntitalizeFireBaseMessaging()">Allow Notifications</button>
    <p id="token"></p>
    </div>


    </form>



<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-app.js"></script>

<script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-messaging.js"></script>

<script>

    var firebaseConfig = {

        apiKey: "AIzaSyCCJfC5ZGXbZKq4qMYYvNfEos1JOpHfx_k",
    authDomain: "localhost-16413.firebaseapp.com",
    projectId: "localhost-16413",
    storageBucket: "localhost-16413.appspot.com",
    messagingSenderId: "1066229040723",
    appId: "1:1066229040723:web:f80bda5df2a5ff581428a6",
    measurementId: "G-JN28H6BLC4"

    };

    firebase.initializeApp(firebaseConfig);

    const messaging=firebase.messaging();

// console.log(messaging)

    function IntitalizeFireBaseMessaging() {
        var roles =  document.getElementById('roles').value;
        if(roles==''){
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Roles is Required',
                    showConfirmButton: false,
                    timer: 1500
                    })
        }else{


        messaging
            .requestPermission()
            .then(function () {
                console.log("Notification Permission");
                return messaging.getToken();
            })
            .then(function (token) {
                console.log("Token : "+token);
                document.getElementById("token").innerHTML=token;



                var myHeaders = new Headers();
			var requestOptions = {
			  method: 'GET',
			  headers: myHeaders,
			  redirect: 'follow'
			};
            // console.log(window.location.origin)

            var orgin = window.location.origin;
			fetch(orgin+"/api/set/notification?key="+token+"&roles="+roles, requestOptions)
			  .then(response => response.text())
			  .then(result => {
                if(result==1){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Notification permission granted',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }else{
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Notification permission already granted',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }
                window.location.href = '/dashboard'

              })
			  .catch(error => console.log('error', error));









            })

            .catch(function (reason) {

                console.log(reason);

            });

    }
    }



    messaging.onMessage(function (payload) {

        console.log(payload);

        const notificationOption={

            body:payload.notification.body,

            icon:payload.notification.icon

        };



        if(Notification.permission==="granted"){

            var notification=new Notification(payload.notification.title,notificationOption);



            notification.onclick=function (ev) {

                ev.preventDefault();

                window.open(payload.notification.click_action,'_blank');

                notification.close();

            }

        }



    });

    messaging.onTokenRefresh(function () {

        messaging.getToken()

            .then(function (newtoken) {

                console.log("New Token : "+ newtoken);

            })

            .catch(function (reason) {

                console.log(reason);

				//alert(reason);

            })

    })

    // IntitalizeFireBaseMessaging();

</script>

</body>

</html>
