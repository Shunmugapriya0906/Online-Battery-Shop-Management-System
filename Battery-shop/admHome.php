<!DOCTYPE html>
<!--
This page is the home page for admin.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Online Battery Shop Management System</title>
        <link rel="stylesheet" href="batteryShop.css">
    </head>
    <?php
    session_start();    
    $db=include('dbConsts.php');    
    date_default_timezone_set("Asia/Calcutta");    
    $userName='';   
    if(!isset($_SESSION["user"])) {
        header("Location:index.php");
        exit();
    } 
    else {
        $userName=$_SESSION['user'];
        if($userName!='admin') {
            header("Location:index.php");
            exit();
        }
    }    
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
        <a href=logOut.php> Log Out </a> 
        <hr>
        <center>                     
            <table border="0">
                <tr>
                    <td colspan="2" style="font-size:30px"> <center> <u> Batteries Info </u> </center> </td> <td> &emsp;&emsp;&emsp;</td>
                    <td colspan="2" style="font-size:30px"> <center> <u> Stocks </u> </center> </td> <td> &emsp;&emsp;&emsp;</td>                    
                    <td colspan="2" style="font-size:30px"> <center> <u> Reports </u> </center> </td> <td> &emsp;&emsp;&emsp;</td>                    
                </tr>
                <tr>
                    <td>
                        <img src="./images/plus.jpg" width="30" height="30" class="imgVMiddle"> 
                    </td>
                    <td> <a href="addBattery.php"> Add Battery Details </a> </td> <td> &emsp;</td>
                    <td> <img src="./images/purchase.jpg" width="30" height="30" class="imgVMiddle"> </td>
                    <td> <a href="purchase.php"> Purchase Battery </a> </td>
                    <td> &emsp;</td>
                    <td> <img src="./images/report.jpg" width="30" height="30" class="imgVMiddle"> </td>
                    <td> <a href="viewPurchases.php"> Purchase Report </a> </td>
                    <td> &emsp;</td>
                </tr>
                <tr>
                    <td> <img src="./images/img06.jpg" width="30" height="30" class="imgVMiddle"> </td>
                    <td> <a href="editBattery.php"> Edit Battery Details </a> </td> <td> &emsp;</td>
                    <td> <img src="./images/newOrders.jpg" width="30" height="30" class="imgVMiddle"> </td>
                    <td> <a href="viewNewOrders.php"> View New Orders </a> </td>
                    <td> &emsp;</td>
                    <td> <img src="./images/report.jpg" width="30" height="30" class="imgVMiddle"> </td>
                    <td> <a href="viewSales.php"> Sales Report </a> </td>
                    <td> &emsp;</td>
                </tr>
                <tr>
                    <td> </td>
                    <td> </td> <td> &emsp;</td>
                    <td> <img src="./images/sellBattery.jpg" width="30" height="30" class="imgVMiddle"> </td>
                    <td> <a href="sellBattery.php"> Sell Battery </a> </td>
                    <td> &emsp;</td>
                    <td> <img src="./images/report.jpg" width="30" height="30" class="imgVMiddle"> </td>
                    <td> <a href="viewProducts.php"> View all Products </a> </td>
                    <td> &emsp;</td>
                </tr>                
            </table>                          
        </center>
    </body>
</html>