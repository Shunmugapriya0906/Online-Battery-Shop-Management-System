<!DOCTYPE html>
<!--
This is the register page for customer. 
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
    $errMessage="";
    $flgSuccess=FALSE;
    $name='';
    $contactNumber='';
    $userPwd='';
    $confirmPwd='';
    $registerStatus=FALSE;
    if(isset($_POST['btnRegister'])) {      
        $name=trim($_POST['txtName']);
        $contactNumber=trim($_POST['txtContact']);
        $userPwd=trim($_POST['txtPwd']);
        $confirmPwd=trim($_POST['txtCPwd']);
        if($name=='') {
            $errMessage="Enter Valid Username!";
        }
        else if ($contactNumber=='') {
            $errMessage="Enter Valid Contact Number!";
        }
        else if (strlen($contactNumber)!=10) {
            $errMessage="Enter Valid Contact Number!";
        }
        else if (!is_numeric($contactNumber)) {
            $errMessage="Enter Valid Contact Number!";
        }        
        else if (!isValidRegistration()) {
            $errMessage="This contact number has been already registered.";
        }
        else if ($userPwd=='') {
            $errMessage="Password can not be empty!";
        }
        else if ($userPwd!=$confirmPwd) {
            $errMessage="Password and confirm password should be same!";
        }
        else {                
            register();
        }            
    }
    if(isset($_POST['btnCancel'])) {      
        header("Location:index.php");
        exit();
    }
    //This function checks whether registration is valid
    //Inputs - None 
    //Output - True, if the user is new
    function isValidRegistration() {
        GLOBAL $db;
        GLOBAL $contactNumber;
        $serverName=$db['serverName'];
        $userName=$db['userName'];
        $pwd=$db['pwd'];
        $dbName=$db['dbName'];
        $flgSuccess=TRUE;

        $conn = mysqli_connect($serverName, $userName, $pwd,$dbName);
        if(!$conn) {                
            die('Connection failed : '. mysqli_connect_error());
        }
        //echo "Connected Successfully !! <br>";

        //fetching data
        $fndUserQuery = 'SELECT * FROM CUSTOMERS WHERE ContactNumber = "'.$contactNumber.'"';
        //echo $fndUserQuery;

        $fndUser = mysqli_query($conn, $fndUserQuery);
        if(!$fndUser) {
            die("Couldnot retrieve data ". mysqli_error ($conn));                
        }   
        $row = mysqli_fetch_array($fndUser,MYSQLI_ASSOC);
        //echo mysqli_num_rows($fndUser);
        if(mysqli_num_rows($fndUser)!=0 && $row['ContactNumber']==$contactNumber) {
            $flgSuccess=FALSE; 
        }
        //Free the memory allocation
        mysqli_free_result($fndUser);
                                              
        //Close the connection
        mysqli_close($conn);
        //echo "\nConnection Closed Successfully !! ";
        return $flgSuccess;
    }
    //This function checks the admin login credentials
    //Inputs - None 
    //Output - None        
    function register() {
        GLOBAL $db;
        GLOBAL $name;        
        GLOBAL $contactNumber;
        GLOBAL $userPwd;
        GLOBAL $registerStatus;        
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
        $insQuery = 'INSERT INTO CUSTOMERS (CustomerName, ContactNumber, Password)'
                .' VALUES ("'.$name.'", "'.$contactNumber.'", "'.$userPwd.'")';
        //echo $insQuery;
        $retVal = mysqli_query($conn, $insQuery);

        if(!$retVal) {
            die("Could not enter data ". mysqli_error ($conn));                    
        }
        else {
            $registerStatus=TRUE;
        }
        //Close the connection
        mysqli_close($conn);
        //echo "\nConnection Closed Successfully !! ";
    }               
    ?>
    <body onload="document.registerform.txtName.focus();">
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
        <hr>        
    <?php
    if (!$registerStatus) {
    ?>
        <p style="color:red"> <?php echo $errMessage ?> </p> <br>
        <form action="#" method="post" name="registerform">
            <table border="0">
                <tr> <td colspan="4"> <center> <p class="pageTitle"> Registration </p> </center> </td> </tr>
                <tr>
                    <td> </td>
                    <td> Name : </td>
                    <td> <input type="text" size="20" name="txtName" tabindex="1" value="<?php echo $name?>"></td>
                    <td> </td>
                </tr>
                <tr>
                    <td> </td>
                    <td> Contact Number : </td>
                    <td> <input type="text" size="20" name="txtContact" tabindex="2" value="<?php echo $contactNumber?>"></td>
                    <td> </td>
                </tr>
                <tr> <td colspan="4"> <p style="color:green"> Contact Number is the login id. </p> </td> </tr>
                <tr>
                    <td> </td>
                    <td> Password : </td>
                    <td> <input type="password" size="20" name="txtPwd" tabindex="3"> </td>
                    <td></td>
                </tr>
                <tr>
                    <td> </td>
                    <td> Confirm Password : </td>
                    <td> <input type="password" size="20" name="txtCPwd" tabindex="4"> </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"> 
                        <input type="submit" class="btnMenu" value="Register" name="btnRegister" tabindex="5"/>
                    </td>
                    <td colspan="2" align="center"> 
                        <input type="submit" class="btnMenu" value="Cancel" name="btnCancel" tabindex="6"/>
                    </td>
                </tr>
            </table>
        </form>
    <?php
    }
    else {
    ?>
        <p style="color:green">
            Congrats!! <br> Your Contact Number : <?php echo $contactNumber ?> 
            Registered Successfully! Please Login to view all batteries. 
            <br> <br> <br> <br> <br> <br> <br> <br>
            <a href="login.php" class="btnMenu"> Login </a> &emsp; <a href="index.php" class="btnMenu"> Home </a>
        </p> 
    <?php
    }
    ?>
        </center>
    </body>
</html>