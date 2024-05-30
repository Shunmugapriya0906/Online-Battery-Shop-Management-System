<!DOCTYPE html>
<!--
This is the login page for the admin of the Battery Shop project. 
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
    $loginPwd='';
    
    if(isset($_POST['btnLogin'])) {      
        $name=trim($_POST['txtName']);
        $loginPwd=trim($_POST['txtPwd']);
        if($name=='') {                
            $errMessage="Enter Valid Username!";
        }
        else if($loginPwd=='') {
            $errMessage="Enter Valid Password!";
        }
        else {
            if($name=='admin' && $loginPwd=='adminpwd') {  
                $_SESSION['user']='admin';
                header("Location:admHome.php");
            }
            else if (!is_numeric($name)) {
                $errMessage="Your Contact Number is the login user name!";
            }
            else if (strlen($name)!=10) {
                $errMessage="Enter Valid Contact Number to login!";
            }              
            else {
                $flgSuccess=getLoginStatus();          
                if($flgSuccess) {
                    $_SESSION['user']=$name;
                    header("Location:home.php");                                                        
                }                
            }
        }
    }
    if(isset($_POST['btnBack'])) {      
        header("Location:index.php");
        exit();
    }                       
    //This function checks the admin login credentials
    //Inputs - None 
    //Output - True, if the username and password is correct        
    function getLoginStatus() {
        GLOBAL $db;        
        GLOBAL $errMessage;
        GLOBAL $name;
        GLOBAL $loginPwd;
        $flgSuccess=false;
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
        $fndUserQuery = 'SELECT * FROM CUSTOMERS WHERE ContactNumber = "'.$name.'"';
        //echo $fndUserQuery;

        $fndUser = mysqli_query($conn, $fndUserQuery);
        if(!$fndUser) {
            die("Couldnot retrieve data ". mysqli_error ($conn));                
        }

        $row= mysqli_fetch_array($fndUser,MYSQLI_ASSOC);

        //echo "User : ".$row['UserName'].'<br> Pwd : '.$row['Password'];
        if($row['ContactNumber']==$name && $row['Password']==$loginPwd) {
            $flgSuccess=true;            
        }
        else {
            $errMessage="Wrong Credentials. Please try to login again !";                                
        }

        //Free the memory allocation
        mysqli_free_result($fndUser);

        //Close the connection
        mysqli_close($conn);
        //echo "\nConnection Closed Successfully !! ";
        return $flgSuccess;
    }               
    ?>
    <body onload="document.logform.txtName.focus();">  
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
        <hr>
        <center>
        <p style="color:red"> <?php echo $errMessage ?> </p> <br>
        <form action="#" method="post" name="logform">
            <table border="0">
                <tr>
                    <td> <img src="images/login.jpg" width="400" height="400"> </td>
                    <td>
                        <table border="0">
                            <tr>
                                <td> </td>
                                <td> User Name : </td>
                                <td> <input type="text" size="20" name="txtName" tabindex="1"></td>
                                <td> </td>
                            </tr>
                            <tr>
                                <td> </td>
                                <td> Password : </td>
                                <td> <input type="password" size="20" name="txtPwd" tabindex="2"> </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center"> <input type="submit" class="btnMenu" value="Login" name="btnLogin" tabindex="3"/>
                                <td colspan="2" align="center"> <input type="submit" class="btnMenu" value="Back" name="btnBack" tabindex="4"/>
                            </tr>
                        </table>                        
                    </td>
                </tr>
            </table>           
        </form>
        </center>
    </body>
</html>