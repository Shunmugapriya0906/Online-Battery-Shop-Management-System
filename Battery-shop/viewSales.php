<!DOCTYPE html>
<!--
This page allows admin to view all purchases in the inventory.
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
    if(!isset($_SESSION["user"])) {
        header("Location:logOut.php");
        exit();
    }    
    $arrSales;
    $resultCnt=0;            
    $fromDate = date("Y-m-d",time());
    $toDate = date("Y-m-d",time());
    $errMessage='';
    $showResults=FALSE;
    if(isset($_POST['btnSearch'])) {
        $chkDate = strtotime($_POST['dFromDate']);
        if($chkDate==FALSE) {
            $errMessage="From Date is invalid. Date Format - YYYY-MM-DD";
        }
        else {
            $fromDate = date('Y-m-d',$chkDate);
            $chkDate = strtotime($_POST['dToDate']);
            if($chkDate==FALSE) {
               $errMessage="To Date is invalid. Date Format - YYYY-MM-DD";
            }
            else {
                $toDate = date('Y-m-d',$chkDate);
                if ($fromDate <= $toDate) {
                    findSalesDetails();
                    if($resultCnt>0) {
                        $showResults=TRUE;
                    }
                    else {
                        $errMessage='No Reports Found!';
                    }
                }
                else {
                    $errMessage = 'Invalid Range of Dates !';
                }
            }                
        }                        
    }    
    if(isset($_POST['btnBack'])) {
        header("Location:admHome.php");
        exit();
    }        
    function findSalesDetails() {
        GLOBAL $db;        
        GLOBAL $arrSales;        
        GLOBAL $resultCnt;
        GLOBAL $fromDate;
        GLOBAL $toDate;
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
        $fndQuery = 'SELECT * FROM SALES WHERE Status="Delivered" AND DeliveryDate >= "'
                .$fromDate.'" AND DeliveryDate <= "'.$toDate.'" ORDER BY BId';                               
        //echo $fndQuery;

        $fndSales = mysqli_query($conn, $fndQuery);
        if(!$fndSales) {
            die("Could not enter data ". mysqli_error ($conn));                    
        }            
        $resultCnt=0;            
        while($row= mysqli_fetch_array($fndSales,MYSQLI_ASSOC)) {
            $arrSales[$resultCnt]['OrderId'] = $row['OrderId'];
            $arrSales[$resultCnt]['OrderDate'] = $row['OrderDate'];
            $arrSales[$resultCnt]['BId'] = $row['BId'];            
            $arrSales[$resultCnt]['Count'] = $row['Count'];
            $arrSales[$resultCnt]['Price'] = $row['Price'];                            
            $arrSales[$resultCnt]['CustomerName'] = $row['CustomerName'];
            $arrSales[$resultCnt]['ContactNumber'] = $row['ContactNumber'];
            $arrSales[$resultCnt]['Address'] = $row['Address'];
            $arrSales[$resultCnt]['DeliveryDate'] = $row['DeliveryDate'];
            $resultCnt++;
        }        
        //Free the memory allocation
        mysqli_free_result($fndSales);            
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
                    <h1> <center> Online Battery Shop Management System </center> </h1>
                </td>
            </tr>
        </table>   
        </center>
        <a href=logOut.php class="btnMenu"> Log Out </a> <br>
        <br> <br> <hr>
        <center>            
            <h3> Sales Details </h3>
            <form action="#" method="post" name="viewSales">                
                <table border="0">
                    <tr>
                        <td> Sales Report : </td>
                        <td> </td>
                        <td> From : <input type="date" name="dFromDate" value="<?php echo $fromDate; ?>"> </td>
                        <td> </td>
                        <td> To : <input type="date" name="dToDate" value="<?php echo $toDate; ?>"> </td>                        
                        <td> </td>
                        <td> <input type="submit" name="btnSearch" class="btnMenu" value="Search"> </td>                        
                    </tr>
                </table>
                <p style="color: red"><?php echo $errMessage?></p>
    <?php
    if($showResults) {
    ?>
                <table border="1">                    
                    <tr> 
                        <th> S.No </th>
                        <th> Order Id </th>
                        <th> Order Date </th>
                        <th> Battery Id </th>
                        <th> Count </th>
                        <th> Price </th>
                        <th> Customer Name </th>
                        <th> Contact Number </th>
                        <th> Address </th>
                        <th> Delivery Date </th>                        
                    </tr>
    <?php
        for($i=0;$i<$resultCnt;$i++) {                    
    ?>
                    <tr>
                        <td align="center"> <?php echo $i+1 ?> </td>
                        <td align="center"> <?php echo $arrSales[$i]['OrderId'] ?> </td>
                        <td align="center"> <?php echo $arrSales[$i]['OrderDate'] ?> </td>                        
                        <td align="center"> <?php echo $arrSales[$i]['BId'] ?> </td>                        
                        <td align="center"> <?php echo $arrSales[$i]['Count'] ?> </td>                    
                        <td align="center"> <?php echo $arrSales[$i]['Price'] ?> </td>                        
                        <td align="center"> <?php echo $arrSales[$i]['CustomerName'] ?> </td>
                        <td align="center"> <?php echo $arrSales[$i]['ContactNumber'] ?> </td>
                        <td align="center"> <?php echo $arrSales[$i]['Address'] ?> </td>
                        <td align="center"> <?php echo $arrSales[$i]['DeliveryDate'] ?> </td>
                    </tr>
    <?php
        }
    }
    ?>
                    <tr> </tr>
                    <tr>
                        <td colspan="10" align="center"> 
                            <input type="submit" name="btnBack" class="btnMenu" value="Back"> 
                        </td>                    
                    </tr>  
                </table>            
            </form>
        </center>
    </body>
</html>