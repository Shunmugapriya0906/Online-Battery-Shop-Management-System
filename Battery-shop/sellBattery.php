<!DOCTYPE html>
<!--
This page is the mark the orders while delivering.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Online Battery Shop Management System</title>
        <link rel="stylesheet" href="batteryShop.css">
    </head>
    <?php
    session_start();
    date_default_timezone_set("Asia/Calcutta");        
    $db=include('dbConsts.php');
    $showDetails = FALSE;
    $orderId='';
    $orderDate='';
    $expDeliveryDate='';
    $status='';
    $deliveryDate='';
    $errMessage='';
    $succMessage='';
    $bId = 0;
    $cnt = 1;
    $price=0;
    $name='';
    $contact='';
    $address='';    
    if(!isset($_SESSION["user"])) {
        header("Location:logOut.php");
        exit();
    }                          
    if(isset($_POST['btnFind'])) {
        //$showDetails = TRUE;
        if(isset($_POST['txtOrderId'])) {
            $orderId = trim($_POST['txtOrderId']);
            if($orderId=="") {
                $errMessage="Invalid Order Id !";    
            }
            else if(findOrderDetails()) {
                $showDetails=TRUE;                
            }
        }
        else {
            $errMessage="Invalid Order Id !";
        }
    }
    if(isset($_POST['btnBack'])) {
        header("Location:admHome.php");
        exit();
    }
    if(isset($_POST['btnSave'])) {            
        $orderId = $_POST['txtOrderId'];
        if(updateOrderDetails()) {
            $succMessage = "Update Success for Order Id : ".$orderId;
        }
    }
    function findOrderDetails() {
        GLOBAL $db;        
        GLOBAL $orderId;
        GLOBAL $bId;
        GLOBAL $orderDate;  
        GLOBAL $cnt;
        GLOBAL $price;
        GLOBAL $name;
        GLOBAL $contact;
        GLOBAL $address;
        GLOBAL $expDeliveryDate;
        GLOBAL $status;
        GLOBAL $deliveryDate;
        GLOBAL $errMessage;
        $flagSuccess=FALSE;
        $serverName=$db['serverName'];
        $userName=$db['userName'];
        $pwd=$db['pwd'];
        $dbName=$db['dbName'];

        $conn = mysqli_connect($serverName, $userName, $pwd,$dbName);
        if(!$conn) {                
            die('Connection failed : '. mysqli_connect_error());
        }
        //echo "Connected Successfully !! <br>";

        //fetching data
        $fndQuery = 'SELECT * FROM SALES WHERE OrderId="'.$orderId.'"';                    
        //echo $fndQuery;

        $fndOrder = mysqli_query($conn, $fndQuery);
        if(!$fndOrder) {
            die("Could not enter data ". mysqli_error ($conn));                    
        }            

        $row= mysqli_fetch_array($fndOrder,MYSQLI_ASSOC);
        if($row['OrderId']==$orderId) {                
            $bId = $row['BId'];
            $orderDate = $row['OrderDate'];
            $cnt = $row['Count'];
            $price = $row['Price'];
            $name = $row['CustomerName'];
            $contact = $row['ContactNumber'];
            $address = $row['Address'];
            $expDeliveryDate = $row['ExpDeliveryDate'];
            $status = $row['Status'];
            $deliveryDate = $row['DeliveryDate'];
            $flagSuccess=TRUE;
        }
        else {
            $errMessage = "Invalid Order Id !";
        }
        //Close the connection
        mysqli_close($conn);        
        return $flagSuccess;
    }
    function updateOrderDetails() {
        GLOBAL $db;        
        GLOBAL $orderId;        
        $flagSuccess=FALSE;
        $serverName=$db['serverName'];
        $userName=$db['userName'];
        $pwd=$db['pwd'];
        $dbName=$db['dbName'];

        $conn = mysqli_connect($serverName, $userName, $pwd,$dbName);
        if(!$conn) {                
            die('Connection failed : '. mysqli_connect_error());
        }
        //echo "Connected Successfully !! <br>";

        //Update data
        $updQuery = 'UPDATE SALES SET Status="Delivered", DeliveryDate="'
                .date("Y-m-d", time()).'" WHERE OrderId="'.$orderId.'"';            
        //echo $updQuery;

        $retVal = mysqli_query($conn, $updQuery);
        if(!$retVal) {
            die("Could not enter data ". mysqli_error ($conn));                    
        }
        else {                    
            $flagSuccess=TRUE;                
        }

        //Close the connection
        mysqli_close($conn);
        //echo "\nConnection Closed Successfully !! ";
        return $flagSuccess;
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
            <form action="#" method="post" name="deliverProduct">
                <table>
                    <tr> <td colspan="3"> <p class="pageTitle"> Battery Sale Status </p> <br> </td> </tr>
                    <tr> 
                        <td> Order Id </td> 
                        <td> : </td>
                        <td> 
                            <input type="text" name="txtOrderId" value="<?php echo $orderId ?>">
                        </td>
                    </tr>
                    <tr> <td colspan="3"> <br> <br> </td></tr>
                    <tr> 
                        <td colspan="3" align="center"> 
                            <input type="submit" name="btnFind" class="btnMenu" value="Find"> 
                            <input type="submit" name="btnBack" class="btnMenu" value="Back">
                        </td> 
                    </tr>
                </table>
                <br> <br>                
                <p style="color:red"> <?php echo $errMessage; ?> </p>
                <p style="color:green"> <?php echo $succMessage; ?> </p>
    <?php          
    if($showDetails) {
    ?>
                <table border="1">
                    <tr>
                        <td> Order Id </td>
                        <td> : </td>
                        <td> <?php echo $orderId ?> </td>
                    </tr>
                    <tr>
                        <td> Ordered Date </td>
                        <td> : </td>
                        <td> <?php echo $orderDate ?> </td>
                    </tr>
                    <tr>
                        <td> Battery Id </td>
                        <td> : </td>
                        <td> <?php echo $bId ?> </td>
                    </tr>
                    <tr>
                        <td> Customer Name </td>
                        <td> : </td>
                        <td> <?php echo $name ?> </td>
                    </tr>
                    <tr>
                        <td> Contact Number </td>
                        <td> : </td>
                        <td> <?php echo $contact ?> </td>
                    </tr>
                    <tr>
                        <td> Address </td>
                        <td> : </td>
                        <td> <?php echo $address ?> </td>
                    </tr>
                    <tr>
                        <td> Count </td>
                        <td> : </td>
                        <td> <?php echo $cnt ?> </td>
                    </tr>
                    <tr>
                        <td> Price </td>
                        <td> : </td>
                        <td> <?php echo $price ?> </td>
                    </tr>
                    <tr>
                        <td> Total Bill Amount </td>
                        <td> : </td>
                        <td> <?php echo $cnt * $price ?> </td>
                    </tr>
                    <tr>
                        <td> Expected Delivery Date </td>
                        <td> : </td>
                        <td> <?php echo $expDeliveryDate ?> </td>
                    </tr>
                    <tr> 
                        <td> Current Status </td>
                        <td> : </td>
                        <td> <?php echo $status ?> </td>
                    </tr>
    <?php
        if ($deliveryDate=='0000-00-00') {
    ?>
                    <tr>
                        <td> Update Status to </td>
                        <td> : </td>
                        <td> Delivered </td>
                    </tr>
                    <tr> <td colspan="3"> <br> <br> </td></tr>
                    <tr>
                        <td colspan="3" align="center"> 
                            <input type="submit" name="btnSave" class="btnMenu" value="Save"> 
                        </td>
                    </tr>
    <?php
        }
    ?>
                </table>
    <?php    
    }
    ?>
            </form>
        </center>
    </body>
</html>