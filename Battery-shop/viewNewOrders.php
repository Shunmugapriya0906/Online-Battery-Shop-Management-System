<!DOCTYPE html>
<!--
This page is to view the orders.
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
    $showResults = FALSE;   
    $arrOrders;
    $resultCnt=0;                
    $errMessage = '';
    if(!isset($_SESSION["user"])) {
        header("Location:logOut.php");
        exit();
    }
    getOrderReport();
    if($resultCnt>0) {
        $showResults=TRUE;        
    }    
    if(isset($_POST['btnBack'])) {
        header("Location:admHome.php");
        exit();
    }        
    function getOrderReport() {
        GLOBAL $db;        
        GLOBAL $arrOrders;        
        GLOBAL $resultCnt;
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
        $fndQuery = 'SELECT * FROM SALES WHERE Status="Ordered"';                    
        //echo $fndQuery;

        $fndOrders = mysqli_query($conn, $fndQuery);
        if(!$fndOrders) {
            die("Could not enter data ". mysqli_error ($conn));                    
        }            
        $resultCnt=0;            
        while($row= mysqli_fetch_array($fndOrders,MYSQLI_ASSOC)) {
            $arrOrders[$resultCnt]['OrderId'] = $row['OrderId'];
            $arrOrders[$resultCnt]['OrderDate'] = $row['OrderDate'];
            $arrOrders[$resultCnt]['BId'] = $row['BId'];
            $fndBatteryQuery = 'SELECT * FROM BATTERIES WHERE BId='.$row['BId'];
            $fndBattery= mysqli_query($conn, $fndBatteryQuery);
            $rowBattery=mysqli_fetch_array($fndBattery, MYSQLI_ASSOC);
            $arrOrders[$resultCnt]['ModelName']=$rowBattery['ModelName'];
            $arrOrders[$resultCnt]['Count'] = $row['Count'];
            $arrOrders[$resultCnt]['Price'] = $row['Price'];
            $arrOrders[$resultCnt]['CustomerName'] = $row['CustomerName'];
            $arrOrders[$resultCnt]['Contact'] = $row['ContactNumber'];                                
            $arrOrders[$resultCnt]['Address'] = $row['Address'];
            $arrOrders[$resultCnt]['ExpDeliveryDate'] = $row['ExpDeliveryDate'];                            
            $resultCnt++;
            mysqli_free_result($fndBattery);
        }       
        //echo $resultCnt;
        //Free the memory allocation
        mysqli_free_result($fndOrders);
        //Close the connection
        mysqli_close($conn);        
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
                    <h1> <center> Online Battery Shop System </center> </h1>
                </td>
            </tr>
        </table>   
        </center>
        <a href=logOut.php> Log Out </a> 
        <hr>
        <center>                
            <form action="#" method="post" name="viewOrder">                
    <?php            
    if ($showResults) {
    ?>
                <table border="1">
                    <tr> <td colspan="9"> <center> <p class="pageTitle"> New Orders </p> </center> </td> </tr>
                    <tr> <td colspan="9"> </td></tr>
                    <tr> <td colspan="9"> </td></tr>
                    <tr> <td colspan="9"> </td></tr>
                    <tr> 
                        <th> Order Id </th>
                        <th> Order Date </th>
                        <th> Battery Id </th> 
                        <th> Model Name </th>
                        <th> Count </th>
                        <th> Price </th>
                        <th> Name </th>
                        <th> Contact </th>
                        <th> Expected Delivery Date </th>      
                    </tr>
    <?php
        for($i=0;$i<$resultCnt;$i++) {            
    ?>
                    <tr>
                        <td align="center" style="border-right: solid 2px blueviolet"> <?php echo $arrOrders[$i]['OrderId'] ?> </td>
                        <td align="center" style="border-right: solid 2px blueviolet"> <?php echo $arrOrders[$i]['OrderDate'] ?> </td>
                        <td align="center" style="border-right: solid 2px blueviolet"> <?php echo $arrOrders[$i]['BId'] ?> </td>
                        <td align="center" style="border-right: solid 2px blueviolet"> <?php echo $arrOrders[$i]['ModelName']?> </td>
                        <td align="center" style="border-right: solid 2px blueviolet"> <?php echo $arrOrders[$i]['Count'] ?> </td>
                        <td align="center" style="border-right: solid 2px blueviolet"> <?php echo $arrOrders[$i]['Price'] ?> </td>
                        <td align="center" style="border-right: solid 2px blueviolet"> <?php echo $arrOrders[$i]['CustomerName'] ?> </td>
                        <td align="center" style="border-right: solid 2px blueviolet"> <?php echo $arrOrders[$i]['Contact'] ?> </td>
                        <td align="center" style="border-right: solid 2px blueviolet"> <?php echo $arrOrders[$i]['ExpDeliveryDate'] ?> </td>                        
                    </tr>
    <?php
        }    
    }
    else {
    ?>   
                    <p style="color:red"> Sorry ! Try Later ! </p> <br>
    <?php
    }
    ?>
                    <tr> <td colspan="9"> <center> <input type="submit" class="btnMenu" name="btnBack" value="Back"> </center> </td> </tr>
                </table>            
            </form>
        </center>
    </body>
</html>