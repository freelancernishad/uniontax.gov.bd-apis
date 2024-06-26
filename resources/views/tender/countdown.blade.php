
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>অনলাইন দরপত্র দাখিল সিস্টেমে স্বাগতম</title>
    <style>
/*
Author: WebThemez
Author URL: http://webthemez.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
*/
/* @import url(http://fonts.googleapis.com/css?family=Economica:400,700); */

/* latin-ext */
@font-face {
  font-family: 'Economica';
  font-style: normal;
  font-weight: 400;
  src: url({{ asset('fonts/Qw3fZQZaHCLgIWa29ZBbOMIBMl0.woff2') }}) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Economica';
  font-style: normal;
  font-weight: 400;
  src: url({{ asset('fonts/Qw3fZQZaHCLgIWa29ZBbNsIB.woff2') }}) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}


/* @import url(http://fonts.googleapis.com/css?family=Lobster+Two:700); */
/* latin */
@font-face {
  font-family: 'Lobster Two';
  font-style: normal;
  font-weight: 700;
  src: url({{ asset('fonts/BngRUXZGTXPUvIoyV6yN5-92w7CGwR0.woff2') }}) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
body {
	color:#FFF;
	font-family: 'Economica', sans-serif;
	min-width:960px;
	top:0;
	left:0;
	position:absolute;
	height:100%;
	width:100%;
	margin:0;
	background-size:cover;
}
.ie body {
	filter: dropshadow(color=#000000, offx=0, offy=1);
}
h1 {
	font-size:68px;
	letter-spacing:-2px;
	text-align:center;
	padding-top:30px;
	font-weight:700;
	font-family: 'Lobster Two', cursive;
	text-shadow: 2px 2px rgba(12, 11, 11, 0.56);
}
.ie h1 {
	filter: dropshadow(color=#000000, offx=0, offy=3);
	padding-bottom:12px;
}
.ie h2 {
	filter: dropshadow(color=#000000, offx=0, offy=3);
}
h3 {
	font-size:25px;
	margin:0.2em 0;
}
.ie h3 {
	filter: dropshadow(color=#000000, offx=0, offy=3);
}
h4 {
	margin-bottom:5px;
}
p, pre {
	margin:0 0 10px 0;
}
code {
	text-transform:uppercase !important;
	color:#EEE;
	border-bottom:1px dotted #666;
}
small {
	background:url(../images/alpha.png);
	background:rgba(0, 0, 0, 0.8) url();
	border:1px solid #111;
	font-size:15px;
	padding:0 5px;
}
a {
	color: rgba(255, 255, 255, 0.57);
	text-decoration:none;
}
a:hover {
	color:#BBB;
}
#Header {
margin-bottom: 62px;
position: relative;
width: 100%;
height: 110px;
line-height: 110px;
z-index: 1;
}
.wrapper {
    margin: 0 auto;
    position: relative;
    width: 920px;
    z-index: 1;
    text-align: center;
    margin-top: 20px;
}
#socialIcons {
display:block;
}
#socialIcons ul {
margin: 21px 0 0;
padding:0px;
text-align: center;
}
#socialIcons ul li {
margin-right: 5px;
height: 34px;
line-height: 34px;
list-style-type: none;
display: inline-block;
}
#socialIcons ul li a{
width:34px;
height:34px;
display: inline-block;
}
.twitterIcon, .facebookIcon, .linkedInIcon, .pintrestIcon{
background: url('../images/social-icons.png') -74px 0px;
}
.facebookIcon{
background-position: 10px 0px;
}
.linkedInIcon{
background-position: -245px 0px;
}
.pintrestIcon{
background-position: -331px 0px;
}
.tempBy{
display:block;
text-align:center;
margin-top:40px;
}
#Content h2 {
display: inline-block;
margin: 25px 0 45px;
padding: 0px;
text-align: center;
font-size: 25px;
font-weight: 300;
color: #fff;
line-height: 42px;
}
#WindowSlide {
margin: 0 auto;
position: relative;
width: 634px;
height: 170px;
display: block;
overflow: hidden;
}
#Mail {
position: absolute;
width: 634px;
top: 0px;
left: 0px;
}
#Content h3 {
text-align: center;
font-size: 20px;
font-weight: normal;
display: block;
clear: both;
}

#subscribe p{
text-align:center;
}
#subscribe input {
background: rgba(255, 255, 255, 0.52);
color: #000;
vertical-align: middle;
width: 293px;
border: 1px solid rgba(255, 255, 255, 0.76);
padding: 0 10px;
height: 40px;
}
#subscribe input[type="button"]{
background: rgba(255, 255, 255, 1);
width: auto;
padding: 0px 25px;
cursor: pointer;
margin-left: -5px;
font-weight: bold;
height: 42px;
display: inline-block;
}


.callback,
.simple {
  font-size: 20px;
  background: #27ae60;
  padding: 0.5em 0.7em;
  color: #ecf0f1;
  margin-bottom: 50px;
  -webkit-transition: background 0.5s ease-out;
  transition: background 0.5s ease-out;
}
.callback{
  cursor: pointer;
}
.ended {
  background: #c0392b;
}
.countdown {
text-align:center;
}

.intro{
font-size:22px;
font-weight:normal;
margin-bottom:30px;
text-align:center;
}
h2.intro{
display: block !important;
}
.styled{
  margin: 136px 0 60px;
  display: block;
}
.styled div {
display: inline-block;
font-size: 80px;
font-weight: 900;
text-align: center;
margin: 0 8px;
width: 136px;
height: 100px;
background: rgba(255, 255, 255, 0.26);
text-shadow: none;
vertical-align: middle;
border: 1px solid rgba(255, 255, 255, 0.58);
text-shadow: 0px 1px 2px rgba(12, 11, 11, 0.54);
border-radius: 38px 0;
padding: 20px 24px 55px;
box-shadow: 0px 1px 4px rgba(107, 55, 0, 0.62);
}
.styled div:last-child{
}
#overlay{
background: rgba(0, 0, 0, 0.04) url(../images/overlays/06.png) top left repeat;
background:rgba(0, 0, 0, 0.14);
position: fixed;
top: 0px;
width: 100%;
bottom: 0px;
opacity:0.8;
}
/* IE7 inline-block hack */
*+html .styled div{
  display: inline;
  zoom: 1;
}
.styled div:first-child {
  margin-left: 0;
}
.styled div span {
display: block;
padding-top: 3px;
font-size: 24px;
font-weight: 400;
text-align: center;
text-transform: capitalize;
font-weight: bold;
}
footer{
width:100%;
height:30px;
background:rgba(0, 0, 0, 0.64);
position:absolute;
bottom:0px;
}
footer span{
float:right;
margin:10px;
}
@media(max-width:768px){
	body {
	min-width: initial !important;
	}
	.wrapper{width:100%;}
	.styled div {
	  margin-bottom:10px;
	}
}
@media(max-width:420px){
	h1{
	float:initial;
	text-align: center;
	margin-left:0px;
	margin-bottom:0px;
	}
	.styled div {
	  font-size: 40px;
	  font-weight: normal;
	  text-align: center;
	}
	#Content h2 {
	margin: 0px 0px 0px 0px;
	padding: 0px;
	text-align: center;
	font-size: 20px;
	font-weight: 300;
	line-height: 30px;
	padding-bottom: 34px;
}
	.styled {
	margin-bottom: 30px;
	}
	#subscribe input[type="button"]{
	margin-top:10px;
	}
	#subscribe input{
	width:80%;
	}
	footer{
	position:relative !important;
	}
}
.countdown.styled div span {
    font-size: 54px;
}
.countdown.styled div {
    background: #ed2024;
}
.wrapper img {
    width: 370px;
}

    </style>
</head>
<body>









<div id="Header">
    <div class="wrapper" style="line-height: 0;">
        {{-- <h1>Orange</h1> --}}

        <img src="{{ asset('assets/img/mail_logo-01.png') }}" alt="">
        <br>
        <img src="{{ asset('es-01.png') }}" alt="">
        <br>
        <br>
        <img style="margin-top: 10px;    max-width: 445px;" src="{{ asset('tenda-01.png') }}" alt="">
    </div>
    </div>
    <div id="Content" class="wrapper">
        <div class="countdown styled"><div>
            <span id="days">000</span> <span>days</span>
        </div>
        <div>
            <span id="hrs">00</span> <span>hrs</span>
        </div>
        <div>
            <span id="min">00</span> <span>min</span>
        </div>
        <div>
            <span id="sec">00</span> <span>sec</span>
        </div>
    </div>
    <img width="500px" src="{{ asset('contin-01.png') }}" alt="">
    <br/>

    <img width="500px" src="{{ asset('documentsup.jpeg') }}" alt="">
    </div>


    <div class="footer" style="text-align: center">


        <img width="100%" src="{{ asset('footer-01.png') }}" alt="">


    </div>







    {{-- <div id="overlay"></div> --}}

{{--

    <div class="backstretch" style="left: 0px; top: 0px; overflow: hidden; margin: 0px; padding: 0px; height: 100vh; width: 100%; z-index: -999999; position: fixed;">
        <img src="{{ asset('road2.jpg') }}" style="position: absolute; margin: 0px; padding: 0px; border: none; width: 1349px; height: 880.89px; max-height: none; max-width: none; z-index: -999999; left: 0px; top: -244.445px;"></div> --}}





<script>
    // Set the date we're counting down to
    var countDownDate = new Date("<?php echo $tender_list->tender_start; ?>").getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {

        // Get today's date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="countdown-timer"
        document.getElementById("days").innerHTML = days;
        document.getElementById("hrs").innerHTML = hours;
        document.getElementById("min").innerHTML = minutes;
        document.getElementById("sec").innerHTML = seconds;
        // document.getElementById("countdown-timer").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            console.log('expired');
            window.location.reload();

            document.getElementById("countdown-timer").innerHTML = "EXPIRED";
        }
    }, 1000);
</script>



</body>
</html>





