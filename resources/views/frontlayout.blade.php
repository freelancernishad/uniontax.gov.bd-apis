<!DOCTYPE HTML>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta http-equiv="content-language" content="en">
  <meta name="keywords" content="website, blog, foo, bar">
  <meta name="author" content="John Doe">
  <meta name="publisher" content="John Doe">
  <meta name="copyright" content="John Doe">
  <meta name="description" content="This short description describes my website.">
  <meta name="page-topic" content="Media">
  <meta name="page-type" content="Blogging">
  <meta name="audience" content="Everyone">
  <meta name="robots" content="index, follow">




    <title>:: ইউনিয়ন পরিষদ ডিজিটাল সেবা সিস্টেম ::</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/bangladesh-govt.png') }}" />
    <!-- <link href="https://kit-pro.fontawesome.com/releases/v5.15.2/css/pro.min.css" rel="stylesheet" onload="if(media!=='all')media='all'"> -->
    <!-- CSS only -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pro.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">


    <script type="text/javascript" src="{{asset('assets/js/jquery-1.12.4.min.js')}}"></script>

<meta name="csrf-token"             ="{{ csrf_token() }}" />
	<link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">

<style>
*{
    --defaultColor: @php  if($uniounDetials->defaultColor){echo $uniounDetials->defaultColor;}else{ echo 'green';} @endphp;
}

div#exampleModal {
    z-index: 99999;
}
.serviceBox {
    cursor: pointer;

}
.serviceBox:hover {
  transform: scale(1.1);
  background: none !important;
  text-decoration: none !important;
  box-shadow: 0px 0px 17px -4px #000 !important;
}
a:hover {
  text-decoration: none !important;
}

.serviceBox {
    box-shadow: 0px 7px 9px -2px #0000007a !important;
    padding: 13px 4px;
    height: 120px !important;
    transition: all 0.4s;
    border: 1px solid @php  if($uniounDetials->defaultColor){echo $uniounDetials->defaultColor;}else{ echo 'green';} @endphp;
}
</style>

<!-- <link type="text/css" href="{{ asset('slider/css/jquery.bbslider.css') }}" rel="stylesheet" media="screen"/> -->

<style type="text/css">

  .bbslider-wrapper.carousel {
    width: 1521px;
  }


.prev-control-wrapper.control-wrapper {
    display: none;
}

.next-control-wrapper.control-wrapper {
    display: none;
}
@media only screen and (max-width: 768px) {
    p.footerText {
    float: left !important;
    padding: 0px 0px 0 40px;
    margin-top: 18px;
    font-size: 13px !important;
}

p.help.text-right {
    display: flex;
    justify-content: flex-start !important;
    align-items: center;
    width: 100%;
    padding: 0 0 0 40px;
}


}

.middleHeaderItem.col-md-6.mb-3 {
    z-index: 1;
}


</style>


</head>

<body style="font-family: 'Kalpurush', sans-serif;" >
{{-- <body style="font-family: 'Kalpurush', sans-serif;" oncontextmenu="return false"> --}}

    <div id="app">




        <component :is="$route.meta.layout || 'div'" :unioundetialsprops="{{$uniounDetials}}" :sonodnamesprops="{{$sonodnamesprops}}" :alldivisionprops="{{$allDivision}}" :tradelicenseprops="{{$tradeLicenseKhat}}" >
            <router-view />
          </component>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>


{{-- <script type="text/javascript" src="{{ asset('slider/js/jquery.bbslider.min.js') }}"></script> --}}

{{-- <script type="text/javascript">
  $(document).ready(function () {



    $('#auto').bbslider({auto: true, timer: 3000, controls: true, loop: true, pauseOnHit: false});




    $('#touch').bbslider({controls: false, touch: true, transition: 'slide', touchoffset: 50});

    $(window).resize(rsz);
    $(window).on('load', rsz);
  });
</script> --}}
{{-- <script src="assets/js/custom.js"></script> --}}





    <script src="{{ asset('js/frontend.js?ver=1.5.20') }}" async ></script>

<script>

// document.addEventListener('contextmenu', function(e) {
//   e.preventDefault();
// });

// document.onkeydown = function(e) {
//   if(event.keyCode == 123) {
//      return false;
//   }
//   if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
//      return false;
//   }
//   if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
//      return false;
//   }
//   if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
//      return false;
//   }
//   if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
//      return false;
//   }
// }


    // function linkrun(linkdata){

    //     var link = document.createElement('link');
    //     link.setAttribute("rel", "stylesheet");
    //     link.setAttribute("type", "text/css");
    //     // link.onload = function(){ console.log('loaded'); }
    //     link.setAttribute("href", linkdata);
    //     document.getElementsByTagName("head")[0].appendChild(link);
    //     console.log('load')
    // }


    // linkrun('assets/css/pro.min.css');
    // linkrun('assets/css/style.css');


if ("{{ Auth::user() }}") {
    const storeToken = localStorage.getItem('token');


    if(!storeToken){

        let data = {'_token': "{{ csrf_token() }}"};
        fetch("/logout", {
  method: "POST",
  headers: {'Content-Type': 'application/json'},
  body: JSON.stringify(data)
}).then(res => {

});

        // axios.post('/logout').then(()=>{
        //     // window.location.href = '/'
        // })
    }


}else{
    // User.loggedOut()



		localStorage.removeItem('token')
		localStorage.removeItem('user')
		localStorage.removeItem('userid')
		localStorage.removeItem('role')
		localStorage.removeItem('position')




}

</script>



{{--


<script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-messaging.js"></script>
<script  type="text/javascript">
    var firebaseConfig = {
    apiKey: "AIzaSyD_k0gk3nJNbiZm3xF4wSD1nMIT5jBDzDE",
    authDomain: "webpush2-cc9ed.firebaseapp.com",
    projectId: "webpush2-cc9ed",
    storageBucket: "webpush2-cc9ed.appspot.com",
    messagingSenderId: "16480631991",
    appId: "1:16480631991:web:db0e4adbad0ce8e475e132",
    measurementId: "G-DS86HSG4Y5"
    };
    firebase.initializeApp(firebaseConfig);
    const messaging=firebase.messaging();

    function IntitalizeFireBaseMessaging() {
        messaging
            .requestPermission()
            .then(function () {
                console.log("Notification Permission");

                return messaging.getToken();
            })
            .then(function (token) {
                console.log("Token : "+token);


			var myHeaders = new Headers();
			var requestOptions = {
			  method: 'GET',
			  headers: myHeaders,
			  redirect: 'follow'
			};
            // console.log(window.location.origin)
            var orgin = window.location.origin;
			fetch(orgin+"/api/set/notification?key="+token, requestOptions)
			  .then(response => response.text())
			  .then(result => console.log(result))
			  .catch(error => console.log('error', error));


               // document.getElementById("token").innerHTML=token;
            })
            .catch(function (reason) {
                console.log(reason);
            });
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
    IntitalizeFireBaseMessaging();
</script>
 --}}

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-7GHRXSGJBX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-7GHRXSGJBX');
</script>



</body>
</html>
