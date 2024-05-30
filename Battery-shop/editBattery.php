<!DOCTYPE html>
<!--
This page allows admin to edit a new product in the inventory.
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
    $dispModelError = TRUE;
    $arrModels;
    $modelCnt=0;
    $bId=0;
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
    if(isset($_POST['cbBrand'])) {
        $brand=$_POST['cbBrand'];
    }
    else {
        $brand='Luminous';
    }
    loadModels();
    if($modelCnt>0) {
        $dispModelError=FALSE;
    }
    if(isset($_POST['btnSearch'])) {
        $brand=$_POST['cbBrand'];
        loadModels();
        if(count($arrModels)>0) {
            $dispModelError=FALSE;
        }
    }
    if(isset($_POST['btnFind'])) {
        $bId=$_POST['cbModelName'];
        loadBattery();
    }
    if(isset($_POST['btnEdit'])) {        
        $bId=trim($_POST['txtBId']);
        $features=trim($_POST['txtFeatures']);
        $replacementWarranty=trim($_POST['txtRepWarranty']);
        $warranty=trim($_POST['txtWarranty']);
        $price=trim($_POST['txtPrice']);
        if($features=='') {
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
            else if($warranty<0) {
                $errMessage="Enter valid Warranty Months";
            }
            else if($iPrice<0) {
                $errMessage="Enter valid values for Price!";
            }                                       
            else {
                $flgSuccess=editBattery();                    
                $bId=0;                
                $features='';
                $replacementWarranty='';                
                $warranty='';                
                $price='';
            }            
        }
        if($bId!=0) {
            loadBattery();
        }
    }  
    if(isset($_POST['btnCancel'])) {            
        header("Location:admHome.php");
        exit();
    }   
    function loadModels() {
        GLOBAL $db;        
        $serverName=$db['serverName'];
        $userName=$db['userName'];
        $pwd=$db['pwd'];
        $dbName=$db['dbName'];
        GLOBAL $arrModels;
        GLOBAL $modelCnt;
        GLOBAL $brand;
        $conn = mysqli_connect($serverName, $userName, $pwd,$dbName);
        if(!$conn) {                
            die('Connection failed : '. mysqli_connect_error());
        }
        //echo "Connected Successfully !! <br>";
        $fndQuery = 'SELECT * FROM BATTERIES WHERE Brand="'.$brand.'"';
        //echo $fndQuery;
        $fndBatteries = mysqli_query($conn, $fndQuery);
        if(!$fndBatteries) {
            die("Couldnot retrieve data ". mysqli_error ($conn));                    
        }
        $modelCnt=0;
        while($row= mysqli_fetch_array($fndBatteries,MYSQLI_ASSOC)) {
            $arrModels[$modelCnt]['BId']=$row['BId'];
            $arrModels[$modelCnt]['ModelName']=$row['ModelName'];
            $modelCnt++;
        }
        //Free the memory allocation
        mysqli_free_result($fndBatteries);

        //Close the connection                
        mysqli_close($conn);
        //echo "\nConnection Closed Successfully !! ";        
    }    
    function loadBattery() {        
        GLOBAL $db;        
        $serverName=$db['serverName'];
        $userName=$db['userName'];
        $pwd=$db['pwd'];
        $dbName=$db['dbName'];
        GLOBAL $bId;
        GLOBAL $modelName;
        GLOBAL $type;        
        GLOBAL $brand;
        GLOBAL $capacity;
        GLOBAL $features;
        GLOBAL $replacementWarranty;
        GLOBAL $warranty;
        GLOBAL $price;
        $conn = mysqli_connect($serverName, $userName, $pwd,$dbName);
        if(!$conn) {                
            die('Connection failed : '. mysqli_connect_error());
        }
        //echo "Connected Successfully !! <br>";
        $fndQuery = 'SELECT * FROM BATTERIES WHERE BId='.$bId;
        //echo $fndQuery;
        $fndBatteries = mysqli_query($conn, $fndQuery);
        if(!$fndBatteries) {
            die("Couldnot retrieve data ". mysqli_error ($conn));                    
        }
        while($row= mysqli_fetch_array($fndBatteries,MYSQLI_ASSOC)) {
            $modelName=$row['ModelName'];
            $type=$row['Type'];
            $brand=$row['Brand'];
            $capacity=$row['Capacity'];
            $features=$row['Features'];
            $replacementWarranty=$row['ReplacementWarranty'];
            $warranty=$row['Warranty'];
            $price=$row['Price'];                        
        }
        //Free the memory allocation
        mysqli_free_result($fndBatteries);

        //Close the connection                
        mysqli_close($conn);
        //echo "\nConnection Closed Successfully !! ";        
    }
    //This function adds a new Battery
    //Inputs - None
    //Output - True, if the given Product is added successfully
    function editBattery()
    {
        GLOBAL $db;        
        $serverName=$db['serverName'];
        $userName=$db['userName'];
        $pwd=$db['pwd'];
        $dbName=$db['dbName'];
        $flgSuccess=0;
        GLOBAL $bId;
        GLOBAL $features;
        GLOBAL $iRepWarranty;
        GLOBAL $iWarranty;
        GLOBAL $iPrice;

        $conn = mysqli_connect($serverName, $userName, $pwd,$dbName);
        if(!$conn) {                
            die('Connection failed : '. mysqli_connect_error());
        }
        //echo "Connected Successfully !! <br>";

        //Query
        $updQuery = 'UPDATE BATTERIES SET Features="'.$features.'", '
                .'ReplacementWarranty='.$iRepWarranty.', Warranty='
                .$iWarranty.', Price='.$iPrice.' WHERE BId='.$bId;
        //echo $updQuery;
                    
        $retVal = mysqli_query($conn, $updQuery);
        
        if(!$retVal) {
            die("Could not enter data ". mysqli_error ($conn));                    
        }
        else {                    
            $flgSuccess=1;
        }        

        //Close the connection
        mysqli_close($conn);
        //echo "\nConnection Closed Successfully !! ";
        return $flgSuccess;
    }
    ?>    
    <body onload='document.editform.txtModelName.focus();'>
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
            <p style="color:red"> <?php echo $errMessage ?> </p> <br>
    <?php 
    if($flgSuccess) {
        echo "<p style='color:green'> Battery Details Edited Successfully!!</p> <br>";                
    }
    ?>
            <form action="#" method="post" name="editform">                
                <table border="0">                  
                    <tr> <td colspan="4" align="center" class="pageTitle"> Edit Details of Battery </td></tr>
                    <tr> <td colspan="4"></td></tr>                    
                    <tr>
                        <td>Brand </td>
                        <td> : </td>
                        <td> 
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
                        <td> <img src="./images/search.jpg" width="30" height="30"> <input type="submit" class="btnMenu" value="Search" name="btnSearch"> </td>
                    </tr>
                    <tr> <td colspan="4"></td></tr>                    
    <?php
    if(!$dispModelError) {
    ?>
                    <tr>         
                        <td>Model Name </td>
                        <td> : </td>
                        <td> 
                            <select name="cbModelName">
    <?php
        $modelCnt=count($arrModels);
        for($i=0;$i<$modelCnt;$i++) {
            echo '<option value="'.$arrModels[$i]['BId'].'">'.$arrModels[$i]['ModelName'].'</option>';
        }
    ?>
                            </select>
                        </td>
                        <td> <input type="submit" class="btnMenu" value="Find Battery Model" name="btnFind"> </td>
                    </tr>
                    <tr> <td colspan="4"></td></tr>
                    <tr> <td colspan="4"> <hr> </td> </tr>
                    <tr> <td colspan="4"> <br> </td></tr>
                    <tr>
                        <td>Battery Id </td>
                        <td> : </td>
                        <td colspan="2"> 
                            <input type="text" name="txtBId" value="<?php echo $bId?>" readonly>
                        </td>
                    </tr>
                    <tr> <td colspan="4"></td></tr>                    
                    <tr>
                        <td> Model Name </td>
                        <td> : </td>
                        <td colspan="2"> 
                            <input type="text" name="txtModelName" value="<?php echo $modelName?>" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>Type </td>
                        <td> : </td>
                        <td colspan="2"> 
                            <input type="text" name="txtType" value="<?php echo $type?>" readonly>
                        </td>                        
                    </tr>
                    <tr> <td colspan="4"></td></tr>                    
                    <tr>
                        <td> Capacity </td>
                        <td> : </td>
                        <td colspan="2"> <input type="text" name="txtCapacity" value="<?php echo $capacity?>" readonly> </td>
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
                        <td colspan="2"> <center> <input type="submit" name="btnEdit" value="Edit Battery" class="btnMenu"> </center> </td>                        
                        <td colspan="2"> <center> <input type="submit" name="btnCancel" value="Cancel" class="btnMenu"> </center> </td>
                    </tr>
                    
    <?php
    }
    else {
    ?>
                    <tr> <td colspan="4"> <p style="color:red"> No Batteries Available for Brand <?php echo $brand?> </p> <br> </td></tr>
                    <tr> <td colspan="4"></td></tr>
                    <td colspan="4"> <center> <input type="submit" name="btnCancel" value="Cancel" class="btnMenu"> </center> </td>
                    
    <?php        
    }
    ?>
                    
                </table>
            </form>
        </center>
    </body>
</html>