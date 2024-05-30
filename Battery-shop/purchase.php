<!DOCTYPE html>
<!--
This page allows admin to purchase a product to the inventory.
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
    if(!isset($_SESSION["user"])) {
        header("Location:logOut.php");
        exit();
    }           
    $errMessage="";
    $flgSuccess = 0;
    $showDetails=FALSE;
    $arrBatteries;
    $bId='';
    $modelName = '';
    $brand='';
    $type='';
    $capacity='';
    $currStk = 0;
    $purchaseQty=0;
    $price=0;    
    loadBatteries();
    //$dealer='';
    if(isset($_SESSION["BIdPur"])) {
        $bId = $_SESSION["BIdPur"];          
        loadProduct();
        $showDetails=TRUE;
    }
    if(isset($_POST['btnSearch'])) {
        $bId=$_POST['cbModels'];
        loadProduct();
        $showDetails=TRUE;
        $_SESSION['BIdPur']=$bId;  
    }
    if(isset($_POST['btnCancel'])) {            
        session_unset();
        $_SESSION['user']='admin';
        header("Location:admHome.php");
        exit();
    }
    if(isset($_POST['btnSave'])) {
        $purchaseQty = trim($_POST['txtQty']);
        $price = trim($_POST['txtPrice']);        
        if($purchaseQty=='') {
            $errMessage="Invalid Purchase Quantity !";
        }
        elseif(!is_numeric($purchaseQty)) {
            $errMessage="Purchase Quantity should be valid number !";
        }
        elseif ($price=='') {
            $errMessage = "Invalid Price !";
        }
        elseif(!is_numeric($price)) {
            $errMessage="Price should be valid number !";
        }        
        else {
            $iQty = intval($purchaseQty);
            $iPrice = intval($price);
            if($iQty <= 0) {
                $errMessage="Purchase Quantity should be valid !";
            }
            elseif ($iPrice <= 0) {
                $errMessage="Price should be valid !";
            }
            else {
                $flgSuccess= purchaseProduct();
                $showDetails=FALSE;
            }
        }
    }
    function loadBatteries() {
        GLOBAL $db;
        GLOBAL $arrBatteries;
                
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
        $fndBatQuery = 'SELECT * FROM BATTERIES';
        $fndBat = mysqli_query($conn,$fndBatQuery);
        if (!$fndBat) {
            die("Couldnot retrieve data ". mysqli_error ($conn));
        }            
        $cnt=0;
        while($row = mysqli_fetch_array($fndBat,MYSQLI_ASSOC)) {
            $arrBatteries[$cnt]['BId']=$row['BId'];
            $arrBatteries[$cnt]['ModelName']=$row['Brand'].'_'.$row['ModelName'];                                    
            $cnt++;
        }
        //Free the memory allocation
        mysqli_free_result($fndBat);        
        //Close the connection
        mysqli_close($conn);         
    }
    function loadProduct() {
        GLOBAL $db;        
        GLOBAL $bId;
        GLOBAL $modelName;
        GLOBAL $type;
        GLOBAL $brand;
        GLOBAL $capacity;
        
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
        $fndBatQuery = 'SELECT * FROM BATTERIES WHERE BId='.$bId;
        $fndBat = mysqli_query($conn,$fndBatQuery);
        if (!$fndBat) {
            die("Couldnot retrieve data ". mysqli_error ($conn));
        }            
        $row = mysqli_fetch_array($fndBat,MYSQLI_ASSOC);
        if($row['BId']==$bId) {
            $modelName=$row['ModelName'];
            $type=$row['Type'];
            $brand=$row['Brand'];
            $capacity=$row['Capacity'];                        
        }
        //Free the memory allocation
        mysqli_free_result($fndBat);
        
        //Close the connection
        mysqli_close($conn);         
    }        
    function purchaseProduct() {
        GLOBAL $db;
        GLOBAL $bId;
        GLOBAL $purchaseQty;
        GLOBAL $price;        
        $serverName=$db['serverName'];
        $userName=$db['userName'];
        $pwd=$db['pwd'];
        $dbName=$db['dbName'];
        $flgSuccess=0;

        $conn = mysqli_connect($serverName, $userName, $pwd,$dbName);
        if(!$conn) {                
            die('Connection failed : '. mysqli_connect_error());
        }
        //echo "Connected Successfully !! <br>";

        //Insert data
        $insQuery = 'INSERT INTO PURCHASE (BId, PurchaseDate, Count, Price) VALUES ('
                .$bId.', "'.date("Y-m-d", time()).'", '.$purchaseQty.', '.$price.')';                
        //echo $insQuery;
        $retVal = mysqli_query($conn, $insQuery);
        if(!$retVal) {
            die("Could not enter data ". mysqli_error ($conn));                    
        }

        //find Stock
        $currStock = 0;
        $fndStockQuery = 'SELECT * FROM STOCK WHERE BId='.$bId;
        //echo $fndStock;
        $fndStock = mysqli_query($conn, $fndStockQuery);
        if(!$fndStock){
            die("Could not enter data ". mysqli_error ($conn));                    
        }
        $row = mysqli_fetch_array($fndStock, MYSQLI_ASSOC);
        if($row['BId']==$bId) {
            $currStock = $row['Count'];
        }
        $currStock += intval($purchaseQty);

        //Update stock
        $updStkQuery = 'UPDATE STOCK SET COUNT='.$currStock.' WHERE BId='.$bId;
        //echo $updStkQuery;
        $retStkVal = mysqli_query($conn, $updStkQuery);
        if(!$retStkVal){
            die("Could not enter data ". mysqli_error ($conn));                    
        }
        $flgSuccess=1;
        //Free the memory allocation
        mysqli_free_result($fndStock);
        //Close the connection
        mysqli_close($conn);
        //echo "\nConnection Closed Successfully !! ";
        return $flgSuccess;
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
        <a href="logOut.php"> Log Out </a> <br>
        <hr>
        <center>               
            <form action="#" method="post" name="purSearchForm">
                <table border="0">
                    <tr>
                        <td> Model Name </td>
                        <td> : </td>
                        <td> 
                            <select name="cbModels">
    <?php
    $batteryCnt=count($arrBatteries);
    for($i=0;$i<$batteryCnt;$i++) {
        echo '<option value="'.$arrBatteries[$i]['BId'].'">'.$arrBatteries[$i]['ModelName'].'</option>';
    }
    ?>
                            </select>
                        </td>
                        <td> <input class="btnMenu" type="submit" name="btnSearch" value="Search"> </td>
                        <td> <input type="submit" class="btnMenu" name="btnCancel" value="Cancel"> </td>
                    </tr>                    
                </table>            
            <p style="color:red"> <?php echo $errMessage ?> </p> <br>
    <?php 
    if($flgSuccess) {
        echo "<p style='color:green'> Purchase Details Saved Successfully !!</p> <br>";
    }
    ?>
    <?php
    if ($showDetails) {
    ?>
            <h3> <u> Battery Details </u> </h3>            
                <table border="0">
                    <tr>
                        <td> Brand </td>
                        <td> : </td>
                        <td colspan="2"> 
                            <?php echo $brand ?>
                        </td>
                    </tr>
                    <tr> <td colspan="4"> </td></tr>
                    <tr>
                        <td> Model Name </td>
                        <td> : </td>
                        <td colspan="2">
                            <?php echo $modelName ?>
                        </td>
                    </tr>
                    <tr> <td colspan="4"> </td></tr>                    
                    <tr>
                        <td> Quantity Purchased </td>
                        <td> : </td>
                        <td colspan="2"> 
                            <input type="text" name="txtQty" value="0">
                        </td>
                    </tr>
                    <tr> <td colspan="4"> </td></tr>
                    <tr>
                        <td> Price </td>
                        <td> : </td>
                        <td colspan="2"> 
                            <input type="text" name="txtPrice" value="0">
                        </td>                        
                    </tr>
                    <tr> <td colspan="4"> </td></tr>
                    <tr> <td colspan="4"></td></tr>
                    <tr>
                        <td colspan="4"> 
                            <center> 
                                <input type="submit" class="btnMenu" name="btnSave" value="Save"> 
                            </center> 
                        </td>                        
                    </tr>
                </table>
            </form>
    <?php
    }
    ?>
        </center>
    </body>
</html>
