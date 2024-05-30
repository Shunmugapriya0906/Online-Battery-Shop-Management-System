<!DOCTYPE html>
<!--
This page is the home page for customers.
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
    $arrBatteries;
    $batteryCnt=0;
    $dispError=FALSE;
    if(!isset($_SESSION["user"])) {
        header("Location:logOut.php");
        exit();
    } 
    else {        
        loadBatteries();
        if($batteryCnt<1) {
            $dispError=TRUE;
        }
    }
    function loadBatteries() {                
        GLOBAL $db;        
        $serverName=$db['serverName'];
        $userName=$db['userName'];
        $pwd=$db['pwd'];
        $dbName=$db['dbName'];   
        GLOBAL $arrBatteries;
        GLOBAL $batteryCnt;
                
        $conn = mysqli_connect($serverName, $userName, $pwd,$dbName);
        if(!$conn) {                
            die('Connection failed : '. mysqli_connect_error());
        }
        //echo "Connected Successfully !! <br>";

        //fetching data
        $fndQuery = 'SELECT * FROM BATTERIES ORDER BY Type';
        //echo $fndQuery;

        $fndBatt = mysqli_query($conn, $fndQuery);            
        if(!$fndBatt) {
            die("Could retrieve data ". mysqli_error ($conn));                                    
        }            
        else {
            $batteryCnt=0;
            while ($row = mysqli_fetch_array($fndBatt, MYSQLI_ASSOC)) {
                $arrBatteries[$batteryCnt]['BId']=$row['BId'];
                $arrBatteries[$batteryCnt]['ModelName']=$row['ModelName'];
                $arrBatteries[$batteryCnt]['Type']=$row['Type'];
                $arrBatteries[$batteryCnt]['Brand']=$row['Brand'];
                $arrBatteries[$batteryCnt]['Capacity']=$row['Capacity'];
                $arrBatteries[$batteryCnt]['Features']=$row['Features'];
                $arrBatteries[$batteryCnt]['ReplacementWarranty']=$row['ReplacementWarranty'];
                $arrBatteries[$batteryCnt]['Warranty']=$row['Warranty'];
                $arrBatteries[$batteryCnt]['Price']=$row['Price'];
                $batteryCnt++;   
            }                        
        }
        mysqli_free_result($fndBatt);
        //Close the connection
        mysqli_close($conn);
        //echo "\nConnection Closed Successfully !! ";            
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
                    <h1> <center> Online Battery Shop System </center> </h1>
                </td>
            </tr>
        </table>   
        </center>
        <table>
            <tr> 
                <td><a href=logOut.php> Log Out </a></td>
            </tr>            
        </table>        
        <hr>
        <center>
    <?php 
    if($dispError) {
    ?>
            <p style="color:red"> Sorry !! Try Later !! </p> <br>
    <?php
    }
    else {
    ?>
            <table border="0">        
                <tr>
                    <th style="border-right: solid 3px violet">S.No</th>
                    <th style="border-right: solid 3px violet">Brand</th>
                    <th style="border-right: solid 3px violet">Model Name</th>
                    <th style="border-right: solid 3px violet">Type</th>
                    <th style="border-right: solid 3px violet">Capacity</th>
                    <th style="border-right: solid 3px violet">Features</th>
                    <th style="border-right: solid 3px violet">Replacement Warranty</th>
                    <th style="border-right: solid 3px violet">Warranty</th>
                    <th style="border-right: solid 3px violet">Price</th>                
                </tr>
                <tr> <td colspan="9"></td></tr>
                    
    <?php
        for($i=0;$i<$batteryCnt;$i++) {
    ?>                
                <tr >
                    <td style="border-right: solid 3px violet;text-align: center;border-bottom: dashed 2px violet"> <?php echo $i+1 ?> </td>
                    <td style="border-right: solid 3px violet;text-align: center;border-bottom: dashed 2px violet"> <?php echo $arrBatteries[$i]['Brand'] ?> </td>
                    <td style="border-right: solid 3px violet;text-align: center;border-bottom: dashed 2px violet"> <?php echo $arrBatteries[$i]['ModelName'] ?> </td>
                    <td style="border-right: solid 3px violet;text-align: center;border-bottom: dashed 2px violet"> <?php echo $arrBatteries[$i]['Type'] ?> </td>
                    <td style="border-right: solid 3px violet;text-align: center;border-bottom: dashed 2px violet"> <?php echo $arrBatteries[$i]['Capacity'] ?> </td>
                    <td style="border-right: solid 3px violet;text-align: center;border-bottom: dashed 2px violet"> <?php echo $arrBatteries[$i]['Features'] ?> </td>
                    <td style="border-right: solid 3px violet;text-align: center;border-bottom: dashed 2px violet"> <?php echo $arrBatteries[$i]['ReplacementWarranty'] ?> </td>
                    <td style="border-right: solid 3px violet;text-align: center;border-bottom: dashed 2px violet"> <?php echo $arrBatteries[$i]['Warranty'] ?> </td>
                    <td style="border-right: solid 3px violet;text-align: center;border-bottom: dashed 2px violet"> <?php echo $arrBatteries[$i]['Price'] ?> </td>                                            
                </tr>                
    <?php    
        }
    }
    ?>
		<tr> <td colspan="9"> </td> </tr>
		<tr> <td colspan="9"> <center> <a href="admHome.php" class="btnMenu"> Back </a> </center> </td> </tr>
		<tr> <td colspan="9"> </td> </tr>
            </table>
        </center>
    </body>
</html>