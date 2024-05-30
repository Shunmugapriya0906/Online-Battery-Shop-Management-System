<!DOCTYPE html>
<!--
This page allows admin to add a new product to the inventory.
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
    $errMessage="";
    $modelName="";
    $type='';
    $brand='';
    $capacity='';    
    $features='';
    $replacementWarranty='';
    $iRepWarranty=0;
    $warranty='';
    $iWarranty=0;
    $price='';
    $iPrice = 0;       
    $flgSuccess=0;   
    if(isset($_POST['btnAdd'])) {
        $modelName=trim($_POST['txtModelName']);
        $type=$_POST['cbType'];
        $brand=$_POST['cbBrand'];
        $capacity=trim($_POST['txtCapacity']);
        $features=trim($_POST['txtFeatures']);
        $replacementWarranty=trim($_POST['txtRepWarranty']);
        $warranty=trim($_POST['txtWarranty']);
        $price=trim($_POST['txtPrice']);
        if($modelName=='') {
            $errMessage="Model Name is Mandatory!!";
        }
        elseif($capacity=='') {
            $errMessage="Capacity is Mandatory!!";
        }
        elseif(!is_numeric($capacity)) {
            $errMessage="Enter valid Capacity!!";
        }
        elseif($features=='') {
            $errMessage="Key Features is Mandatory!!";
        }
        elseif($replacementWarranty=='') {
            $errMessage="Replacement Warranty is Mandatory!!";
        }
        elseif(!is_numeric($replacementWarranty)) {
            $errMessage="Enter Replacement Warranty (in months)";
        }
        elseif($warranty=='') {
            $errMessage="Warranty is Mandatory!!";
        }
        elseif(!is_numeric($warranty)) {
            $errMessage="Enter Warranty (in months)";
        }
        elseif($price=='') {
            $errMessage="Price is Mandatory!!";
        }
        elseif (!is_numeric($price)) {
            $errMessage="Price should be a valid number!!";                    
        }
        else {
            $iRepWarranty=intval($replacementWarranty);
            $iWarranty=intval($warranty);
            $iPrice = intval($price);
            if($iRepWarranty<0) {
                $errMessage="Enter valid Replacement Warranty Months";
            }
            else if($iWarranty<0) {
                $errMessage="Enter valid Warranty Months";
            }
            else if($iPrice<0) {
                $errMessage="Enter valid values for Price!";
            }                                       
            else {
                $flgSuccess=addBattery();                    
                $modelName='';
                $capacity='';
                $features='';
                $replacementWarranty='';                
                $warranty='';                
                $price='';
            }            
        }
    }  
    if(isset($_POST['btnCancel'])) {            
        header("Location:admHome.php");
        exit();
    }        
    //This function adds a new Battery
    //Inputs - None
    //Output - True, if the given Product is added successfully
    function addBattery()
    {
        GLOBAL $db;        
        $serverName=$db['serverName'];
        $userName=$db['userName'];
        $pwd=$db['pwd'];
        $dbName=$db['dbName'];
        $flgSuccess=0;
        GLOBAL $modelName;
        GLOBAL $type;
        GLOBAL $brand;
        GLOBAL $capacity;
        GLOBAL $features;
        GLOBAL $iRepWarranty;
        GLOBAL $iWarranty;
        GLOBAL $iPrice;

        $conn = mysqli_connect($serverName, $userName, $pwd,$dbName);
        if(!$conn) {                
            die('Connection failed : '. mysqli_connect_error());
        }
        //echo "Connected Successfully !! <br>";

        //fetching data
        $fndBIdQuery = 'SELECT MAX(BId) FROM BATTERIES';
        $fndBId = mysqli_query($conn, $fndBIdQuery);
        if(!$fndBId) {
            die("Couldnot retrieve data ". mysqli_error ($conn));                    
        }

        $row= mysqli_fetch_array($fndBId,MYSQLI_NUM);
        $newBId = $row[0] + 1;
        $insQuery = 'INSERT INTO BATTERIES (BId, ModelName, Type, Brand, Capacity, '
                .'Features, ReplacementWarranty, Warranty, Price) VALUES ('.$newBId
                .', "'.$modelName.'", "'.$type.'", "'.$brand.'", "'.$capacity.' AH", "'
                .$features.'", '.$iRepWarranty.', '.$iWarranty.', '.$iPrice.')';                
        //echo $insQuery;
        $insQueryStk = 'INSERT INTO STOCK (BId, Count) VALUES ('.$newBId.', 0)';

        $retVal = mysqli_query($conn, $insQuery);        

        if(!$retVal) {
            die("Could not enter data ". mysqli_error ($conn));                    
        }
        else {                    
            $retValStk = mysqli_query($conn, $insQueryStk);
            $flgSuccess=1;
        }
        mysqli_free_result($fndBId);

        //Close the connection
        mysqli_close($conn);
        //echo "\nConnection Closed Successfully !! ";
        return $flgSuccess;
    }
    ?>    
    <body onload='document.addform.txtModelName.focus();'>
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
        <a href=logOut.php> Log Out </a> <br>
        <hr>
        <center>
            <p style="color:red"> <?php echo $errMessage ?> </p> <br>
    <?php 
    if($flgSuccess) {
        echo "<p style='color:green'> Battery Details Added Successfully!!</p> <br>";                
    }
    ?>
            <form action="#" method="post" name="addform">                
                <table border="0">                  
                    <tr> <td colspan="4" align="center" class="pageTitle"> Add Details of Battery </td></tr>
                    <tr> <td colspan="4"></td></tr>                    
                    <tr>
                        <td>Model Name </td>
                        <td> : </td>
                        <td colspan="2"> <input type="text" name="txtModelName" value="<?php echo $modelName?>"> </td>
                    </tr>
                    <tr> <td colspan="4"></td></tr>
                    <tr>
                        <td>Type </td>
                        <td> : </td>
                        <td colspan="2"> 
                            <select name="cbType">
                                <option>Solar</option>
                                <option>Battery</option>
                            </select>
                        </td>                        
                    </tr>
                    <tr> <td colspan="4"></td></tr>
                    <tr>
                        <td>Brand </td>
                        <td> : </td>
                        <td colspan="2"> 
                            <select name="cbBrand">
                                <option>Luminous</option>
                                <option>Exide</option>
                                <option>LivGaurd</option>
                                <option>LivFast</option>
                                <option>MicroTek</option>
                                <option>Amaron</option>
                                <option>Okaya</option>
                                <option>PowerZone</option>
                            </select>
                        </td>
                    </tr>
                    <tr> <td colspan="4"></td></tr>
                    <tr>
                        <td> Capacity </td>
                        <td> : </td>
                        <td colspan="2"> <input type="text" name="txtCapacity" value="<?php echo $capacity?>">AH </td>
                    </tr>
                    <tr> <td colspan="4"></td></tr>
                    <tr>
                        <td> Key Features </td>
                        <td> : </td>
                        <td colspan="2"> 
                            <textarea name="txtFeatures" rows="4" cols="30"><?php echo $features?></textarea>
                        </td>
                    </tr>
                    <tr> <td colspan="4"></td></tr>
                    <tr>
                        <td> Replacement Warranty <br> (in months) </td>
                        <td> : </td>
                        <td colspan="2"> <input type="text" name="txtRepWarranty" value="<?php echo $replacementWarranty?>"> </td>
                    </tr>
                    <tr> <td colspan="4"></td></tr>
                    <tr>
                        <td> Warranty <br> (in months) </td>
                        <td> : </td>
                        <td colspan="2"> <input type="text" name="txtWarranty" value="<?php echo $warranty?>"> </td>
                    </tr>
                    <tr> <td colspan="4"></td></tr>
                    <tr>
                        <td> Price </td>
                        <td> : </td>
                        <td colspan="2"> <input type="text" name="txtPrice" value="<?php echo $price?>"> </td>
                    </tr>
                    <tr> <td colspan="4"></td></tr>
                    <tr>
                        <td colspan="2"> <center> <input type="submit" name="btnAdd" value="Add Battery" class="btnMenu"> </center> </td>                        
                        <td colspan="2"> <center> <input type="submit" name="btnCancel" value="Cancel" class="btnMenu"> </center> </td>
                    </tr>
                </table>
            </form>
        </center>
    </body>
</html>