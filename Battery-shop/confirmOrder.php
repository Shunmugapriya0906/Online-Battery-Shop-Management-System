<!DOCTYPE html>
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
    $customerName='';    
    $arrBatteries;
    $selBId=0;
    $errMessage='';
    $orderId='';
    $dispSuccess=FALSE;
    $dispError=TRUE;
    if(!isset($_SESSION["user"])) {
        header("Location:logOut.php");
        exit();
    }     
    $contactNumber=$_SESSION['user'];
    getUserDetails();    
    if(isset($_GET['BId'])) {
        $selBId=$_GET['BId'];                        
        $orderId=date("Ymdhis", time());    
        $dispError=FALSE;
    }  
    else if(isset($_POST['txtBId'])) {
        $selBId=$_POST['txtBId'];
        $orderId=$_POST['txtOrderId'];
        $dispError=FALSE;
    }    
    if(!$dispError && loadBatteries()) {
        $dispError=FALSE;
    }
    $address='';    
    $delDate = date("Y-m-d", time() + (2*24*60*60));    
    if(isset($_POST['btnConfirm'])) {        
        $bId=$_POST['txtBId'];
        $orderId=$_POST['txtOrderId'];                
        $address=$_POST['txtAddress'];
        if($address=='') {
            $errMessage='Address is invalid';
        }
        else if (confirmOrder()) {
            $dispSuccess=TRUE;
        }
    }
    if(isset($_POST['btnBack'])) {
        header("Location:home.php");
        exit();
    }
    function getUserDetails() {                
        GLOBAL $db, $customerName, $contactNumber;        
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
        $fndQuery = 'SELECT * FROM CUSTOMERS WHERE ContactNumber="'.$contactNumber.'"';
        //echo $fndQuery;

        $fndCustomer = mysqli_query($conn, $fndQuery);            
        if(!$fndCustomer) {
            die("Could retrieve data ". mysqli_error ($conn));                                    
        }            
        else if(mysqli_num_rows($fndCustomer)>0) {    
            $row = mysqli_fetch_array($fndCustomer, MYSQLI_ASSOC);
            if($row['ContactNumber']==$contactNumber) {
                $customerName = $row['CustomerName'];                
            }                        
        }
        mysqli_free_result($fndCustomer);
        //Close the connection
        mysqli_close($conn);
        //echo "\nConnection Closed Successfully !! ";            
    }
    function loadBatteries() {                
        GLOBAL $db;        
        $serverName=$db['serverName'];
        $userName=$db['userName'];
        $pwd=$db['pwd'];
        $dbName=$db['dbName'];   
        GLOBAL $arrBatteries;
        GLOBAL $selBId;
        $flgSuccess=FALSE;
                
        $conn = mysqli_connect($serverName, $userName, $pwd,$dbName);
        if(!$conn) {                
            die('Connection failed : '. mysqli_connect_error());
        }
        //echo "Connected Successfully !! <br>";

        //fetching data
        $fndQuery = 'SELECT * FROM BATTERIES WHERE BId='.$selBId;
        //echo $fndQuery;

        $fndBatt = mysqli_query($conn, $fndQuery);            
        if(!$fndBatt) {
            die("Could retrieve data ". mysqli_error ($conn));                                    
        }            
        else if (mysqli_num_rows($fndBatt) > 0) {            
            $row = mysqli_fetch_array($fndBatt, MYSQLI_ASSOC);
            if($row['BId']==$selBId) {
                $arrBatteries['BId']=$row['BId'];
                $arrBatteries['ModelName']=$row['ModelName'];
                $arrBatteries['Type']=$row['Type'];
                $arrBatteries['Brand']=$row['Brand'];
                $arrBatteries['Capacity']=$row['Capacity'];
                $arrBatteries['Features']=$row['Features'];
                $arrBatteries['ReplacementWarranty']=$row['ReplacementWarranty'];
                $arrBatteries['Warranty']=$row['Warranty'];
                $arrBatteries['Price']=$row['Price'];                
                $flgSuccess=TRUE;
            }                        
        }
        mysqli_free_result($fndBatt);
        //Close the connection
        mysqli_close($conn);
        //echo "\nConnection Closed Successfully !! ";            
        return $flgSuccess;
    }
    function confirmOrder() {
        GLOBAL $db;        
        GLOBAL $orderId;    
        GLOBAL $bId;        
        GLOBAL $customerName;
        GLOBAL $contactNumber;
        GLOBAL $address;
        GLOBAL $arrBatteries;
        GLOBAL $delDate;            
        $flgSuccess=TRUE;
        $serverName=$db['serverName'];
        $userName=$db['userName'];
        $pwd=$db['pwd'];
        $dbName=$db['dbName'];

        $currDate = date("Y-m-d", time());
        $conn = mysqli_connect($serverName, $userName, $pwd,$dbName);
        if(!$conn) {                
            die('Connection failed : '. mysqli_connect_error());
        }
        //echo "Connected Successfully !! <br>";


        //inserting data
        $insQuery = 'INSERT INTO SALES (OrderId, OrderDate, BId, Count, Price, '
                .'CustomerName, ContactNumber, Address, ExpDeliveryDate, Status) '
                .'VALUES ("'.$orderId.'", "'.$currDate.'", '.$bId.', 1, '
                .$arrBatteries['Price'].', "'.$customerName.'", "'.$contactNumber
                .'", "'.$address.'", "'.$delDate.'", "Ordered")';                
        //echo $insQuery;

        $retVal = mysqli_query($conn, $insQuery);
        if(!$retVal) {
            die("Could not enter data ". mysqli_error ($conn));                    
            $flgSuccess=FALSE;
        }            

        //Close the connection
        mysqli_close($conn);
        //echo "\nConnection Closed Successfully !! ";            
        return $flgSuccess;
    }
    ?>
    <body>       
        <center>
            <table border="0" style="text-align: center">
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
            <p style="color:blueviolet;font-size:30px">Welcome <?php echo $customerName;?> !! <br>
            <p>Browse... Select...Order...</p><br>   
    <?php
    if(!$dispSuccess) {
    ?>
            <p style="color:red"> <?php echo $errMessage ?> </p>
    <?php 
        if($dispError) {
    ?>
            <p style="color:red"> Sorry !! Try Later !! </p>
    <?php
        }
        else {
    ?>
            <form action="#" name="confirm" method="post">
                <table border="0">        
                    <tr>
                        <td colspan="4"> <u style="color: violet"> <center> <b> Your Order </b> </center> </u> </td>
                    </tr>
                    <tr>
                        <td>Order Id</td>
                        <td> : </td>
                        <td colspan="2"> <input type="text" name="txtOrderId" value="<?php echo $orderId?>" readonly> </td>
                    </tr>
                    <tr>
                        <td>Expected Delivery</td>
                        <td> : </td>
                        <td colspan="2"> <?php echo $delDate?> </td>
                    </tr>
                    <tr>
                        <td colspan="4"> <input type="hidden" name="txtBId" value="<?php echo $selBId?>" readonly> </td>
                    </tr>               
                    <tr>
                        <td>Brand</td>
                        <td> : </td>
                        <td colspan="2"> <?php echo $arrBatteries['Brand']?> </td>
                    </tr>
                    <tr>
                        <td>Model Name</td>
                        <td> : </td>
                        <td colspan="2"> <?php echo $arrBatteries['ModelName']?> </td>
                    </tr>
                    <tr>
                        <td>Features</td>
                        <td> : </td>
                        <td colspan="2"> 
                            <?php echo $arrBatteries['Features']?> <br>
                            <?php echo $arrBatteries['Capacity']?> <br>
                        </td>
                    </tr>
                    <tr>
                        <td>Replacement Warranty</td>
                        <td> : </td>
                        <td colspan="2"> <?php echo $arrBatteries['ReplacementWarranty']?> Months </td>
                    </tr>
                    <tr>
                        <td>Warranty</td>
                        <td> : </td>
                        <td colspan="2"> <?php echo $arrBatteries['Warranty']?> Months </td>
                    </tr>
                    <tr>
                        <td>Price</td>
                        <td> : </td>
                        <td colspan="2"> <input type="text" name="txtPrice" value="<?php echo $arrBatteries['Price']?>" readonly> </td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td> : </td>
                        <td colspan="2"> <input type="text" name="txtName" value="<?php echo $customerName?>" readonly> </td>
                    </tr>
                    <tr>
                        <td>Contact Number</td>
                        <td> : </td>
                        <td colspan="2"> <input type="text" name="txtMobile" value="<?php echo $contactNumber?>" readonly> </td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td> : </td>
                        <td colspan="2"> <input type="text" name="txtAddress" value="<?php echo $address?>"> </td>
                    </tr>                
                    <tr> <td colspan="4"></td></tr>
                    <tr>
                        <td colspan="2"> <input type="submit" class="btnMenu" value="Confirm Order" name="btnConfirm"> </td>
                        <td colspan="2"> <input type="submit" class="btnMenu" value="Back" name="btnBack"> </td>
          
                    </tr>                    
                </table>
            </form>
    <?php
        }
    }
    else {
    ?>
            <img src="./images/success.jpg" width="30" height="30">
            <p style="color:green"> Thanks for the order <?php echo $orderId?> ! 
                        Delivery Date :  <?php echo $delDate ?> </p> <br>    
               
                         
    <?php
    }
    ?>
        </center>
    </body>
</html>