<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>CareerHive</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .logo {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 45px;
            height: 55px;
            margin-bottom: 10px;
        }

        .loader{
            width: 130px;
            border-bottom: 1 solid 045be6;
            height: 3px;
            position: absolute;
            top: 150px;
            bottom: 0;
            right: 0;
            left: 20px;
            background: #d5d5d5;
            margin: auto;
            border-radius: 2px;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .loader::after{
            content: '';
            position: absolute;
            width: 70px;
            background: #045be6;
            height: 3px;
            border-radius: 2px;
            animation: loading 1.5s infinite ease;
        }

        @keyframes loading{
            0%,100%{
                transform: translateX(-44px);
            }
            50%{
                transform: translateX(100px);
            }
        }
    </style>
</head>

<body>
    <div class="logo">
        <img src="images/logo.png">
    </div>
    <div class="loader"></div>
</body>
</html>

<?php
if (isset($_COOKIE["id"])) {
    header("refresh:3;home.php");
    exit;
} else {
    header("refresh:3;login.php");
    exit;
}
?>
