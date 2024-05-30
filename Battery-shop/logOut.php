<!DOCTYPE html>
<!--
Log out page
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Online Battery Shop Management System</title>
        <link rel="stylesheet" href="batteryShop.css">
    </head>
    <?php
    session_start();
    session_destroy();
    ?>
    <body>       
        <center>
        <table border="0">
            <tr>
                <td>
                    <image src="./images/logo.jpg" width="50" height="50">
                </td>
                <td>
                    <h1> <center> Online Battery Shop Management System </center> </h1>
                </td>
            </tr>
        </table>
        </center>        
        <hr>
        <center>
            <h1> Thanks for using Online Battery Shopping ! </h1>
            <img src="./images/logout.jpg" width="400" height="400" class="imgVMiddle"> &emsp;
            <a href="index.php"> Home </a>
        </center>
    </body>
</html>