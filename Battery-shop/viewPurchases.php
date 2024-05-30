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
    $db=include('dbConsts.php');
    if(!isset($_SESSION["user"])) {
        header("Location:logOut.php");
        exit();
    }    
    $arrPurchases;
    $resultCnt=0;            
    findPurchaseDetails();
    if(isset($_POST['btnBack'])) {
        header("Location:admHome.php");
        exit();
    }        
    function findPurchaseDetails() {
        GLOBAL $db;        
        GLOBAL $arrPurchases;
        //GLOBAL $showAll;
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
        $fndQuery = 'SELECT * FROM PURCHASE';                                
        //echo $fndQuery;

        $fndPurchases = mysqli_query($conn, $fndQuery);
        if(!$fndPurchases) {
            die("Could not enter data ". mysqli_error ($conn));                    
        }            
        $resultCnt=0;            
        while($row= mysqli_fetch_array($fndPurchases,MYSQLI_ASSOC)) {
            $arrPurchases[$resultCnt]['PurchaseNo'] = $row['PurchaseNo'];
            $arrPurchases[$resultCnt]['BId'] = $row['BId'];
            $arrPurchases[$resultCnt]['PurchaseDate'] = $row['PurchaseDate'];
            $arrPurchases[$resultCnt]['Count'] = $row['Count'];
            $arrPurchases[$resultCnt]['Price'] = $row['Price'];                            
            $resultCnt++;
        }        
        //Free the memory allocation
        mysqli_free_result($fndPurchases);            
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
        <a href=logOut.php class="btnMenu"> Log Out </a> <br>
        <br> <br> <hr>
        <center>            
            <h3> Purchase Details </h3>
            <form action="#" method="post" name="viewPurchase">                
                <table border="1">                    
                    <tr> 
                        <th> Purchase No </th>
                        <th> Battery Id </th>
                        <th> Purchase Date </th>
                        <th> Count </th> 
                        <th> Price </th>                        
                    </tr>
    <?php
    for($i=0;$i<$resultCnt;$i++) {                    
    ?>
                    <tr>
                        <td align="center"> <?php echo $arrPurchases[$i]['PurchaseNo'] ?> </td>                        
                        <td align="center"> <?php echo $arrPurchases[$i]['BId'] ?> </td>
                        <td align="center"> <?php echo $arrPurchases[$i]['PurchaseDate'] ?> </td>
                        <td align="center"> <?php echo $arrPurchases[$i]['Count'] ?> </td>                    
                        <td align="center"> <?php echo $arrPurchases[$i]['Price'] ?> </td>                        
                    </tr>
    <?php
    }
    ?>
                    <tr> </tr>
                    <tr>
                        <td colspan="5" align="center"> 
                            <input type="submit" name="btnBack" class="btnMenu" value="Back"> 
                        </td>                    
                    </tr>  
                </table>            
            </form>
        </center>
    </body>
</html>