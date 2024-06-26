<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        .errorpage {
            width: 100%;
            height: 100vh;
            background: #224a50;
            color: white;
        }
        .errorDiv {
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            line-height: 45px;
        }
        a.tryAgainButton {
            padding: 8px;
            font-size: 21px;
            background: transparent;
            color: white;
            border: 2px solid white;
            cursor: pointer;
            transition: 0.6s;
            text-decoration: none;

        }

        a.tryAgainButton:hover {
            background: white;
            color: #224A50;
            border: 2px solid #224A50;
        }
    </style>
</head>
<body>


    <div class="errorpage">
        <div class="errorDiv">
            <h2>দুঃখিত!</h2>
            <h4>আপনার ইনপুটকৃত তথ্য সঠিক নয়।</h4>
            <a href="/"  class="tryAgainButton">পুনরায় চেষ্টা করুন</a>
        </div>



    </div>


</body>
</html>
