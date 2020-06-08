<?php

session_start();
//this is for database
require 'config/db.php';
//this is for email verification
require_once 'emailController.php';



$errors=array();
$username="";
$email="";

//to check if user has clicked sign up button
if (isset($_POST['reg_user'])) {
    // receive all input values from the form
    $username =$_POST['username'];
    $email =$_POST['email'];
    $password =$_POST['password'];
    $confirmpassword =$_POST['confirmpassword'];

//validation
if(empty($username)){
    $errors['username']="Username required";
}
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $errors['email']="Email address is invalid"; 
}

if(empty($email)){
    $errors['email']="Email required";
}
if(empty($password)){
    $errors['password']="Password required";
}
if($password != $confirmpassword){
    $errors['password']="Both passwords do not match";
}
//this query is use to check email id and stop searching if email id is found once
$emailQuery = "SELECT * from users where email=? limit 1";
//to prepare the query
$stmt= $conn->prepare($emailQuery);
//this bind_parameters takes the value which sql query needs & add it to the sql query & tells the database what the parameters are
//in this case there is only one parameter so we wrte single s as it is string,if there were two string parameters and one boolean we have to write ssb
$stmt->bind_param('s',$email);
//to execute
$stmt->execute();
//to fetch the result
$result=$stmt->get_result();
//to count how many rows the result contains
$userCount=$result->num_rows;
$stmt->close();

if($userCount > 0){
    $errors['email']="Email already exists";
}
//till here we are done with email validation

//now to save data in the databse we have to check if there are errors or not
if(count($errors)==0){
    //if there are no errors we will encrypt the password and save in the database
    //$password=password_hash($password, PASSWORD_DEFAULT);
    //we will now generate token which we require during email verification
    $token= bin2hex(random_bytes(50));
    //this random bytes will generate a random unique chain of length 100
    //now we will declare verified as false seems that user have not verified initially
    $verified=false;

    //now finally we can run the query to add data to the database
    $sql =" INSERT into users (username,email,verified,token,password) values (?,?,?,?,?)";
    $stmt= $conn->prepare($sql);
    //here follow the sequence of strings and boolean properly
    $stmt->bind_param('ssbss',$username, $email, $verified, $token, $password);
    
    //if it executes correctly then login the user else give database error
    if($stmt->execute()){
        //login success
        //this command is used to get id of last inserted person
        $user_id=$conn->insert_id;
        //now we will store this id and other info in sessions
        $_SESSION['id']=$user_id;
        $_SESSION['username']=$username;
        $_SESSION['email']=$email;
        $_SESSION['verified']=$verified;

        //now this part is for sending verification link as they signup
        sendVerificationEmail($email,$token);

        //set flash message
        $_SESSION['message']="You are now logged in!";
        //this will display our message in green box
        $_SESSION['alert-class']="alert-success";
        //this will redirect us to home page that is index.php
        header('location: index.php');
        exit();
    }else{
        $errors['db_error']="Database error: failed to register";
    }
}

}
//upto this ,the code was for sign up page
//--------------------------------------------------------------------------------------------------------------
//now we will write code for working of login page

if (isset($_POST['login_user'])) {
    // receive all input values from the form
    $username =$_POST['username'];
    $password =$_POST['password'];

//validation
if(empty($username)){
    $errors['username']="Username required";
}

if(empty($password)){
    $errors['password']="Password required";
}

if(count($errors)==0){
    $sql="SELECT * from users where email=? or username=? limit 1";
//to prepare the query
$stmt= $conn->prepare($sql);
//this bind_parameters takes the value which sql query needs & add it to the sql query & tells the database what the parameters are
//in this case there is only one parameter so we wrte single s as it is string,if there were two string parameters and one boolean we have to write ssb
//in login form we are asking that user can login either by usernmae or email id
//so here we are writing username 2 times bcoz 1st time iss for actual username and 2nd time is for searching username which is related to the email id user puts as in the form we writing name for the field as 'username'..so we can connect only through username
$stmt->bind_param('ss',$username,$username);
//to execute
$stmt->execute();
//to fetch the result
$result=$stmt->get_result();
//this is for storing the user which is fetched from the query
$user=$result->fetch_assoc();

if(password_verify($password, $user['password'])){
    //login success
    $user_id=$conn->insert_id;
        //now we will store this id and other info in sessions
        $_SESSION['id']=$user['id'];
        $_SESSION['username']=$user['username'];
        $_SESSION['email']=$user['email'];
        $_SESSION['verified']=$user['verified'];
        //set flash message
        $_SESSION['message']="You are now logged in!";
        //this will display our message in green box
        $_SESSION['alert-class']="alert-success";
        //this will redirect us to home page that is index.php
        header('location: index.php');
        exit();
}else{
    $errors['login_fail']='Wrong credentials';
}
}
}

//upto here our code for login is done
//------------------------------------------------------------------------------------------------------------
//now we will write code for logout 
if(isset($_GET['logout'])){
    session_destroy();
    unset($_SESSION['id']);
    unset($_SESSION['username']);
    unset($_SESSION['email']);
    unset($_SESSION['verified']);
    header('location:login.php');
    exit();

}
//upto here it was for logout page
//-------------------------------------------------------------------------------------------------
//now we will write code to verify the user using the token


function verifyUser($token)
{   
    //to use conn inside our function
    global $conn;
    $sql="SELECT * from users where token='$token' limit 1";
    $result= mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)>0){
        $user = mysqli_fetch_assoc($result);
        $update_query = "UPDATE users set verified=1 where token=$token";
        $result=mysqli_query($conn,$update_query);

        if($result){
            //log user in
            $_SESSION['id']=$user['id'];
            $_SESSION['username']=$user['username'];
            $_SESSION['email']=$user['email'];
            $_SESSION['verified']=true;
            //set flash message
            $_SESSION['message']="Your email was successfully verified!!";
            //this will display our message in green box
            $_SESSION['alert-class']="alert-success";
            //this will redirect us to home page that is index.php
            header('location: index.php');
            exit(0);
        }
    } else {
        echo "No token provided!";
    }

}

//this was upto for email verification
//--------------------------------------------------------------------------------------------------------------------------------
//now we will write code for the user if he clicks forgot password button
if(isset($_POST['forgot_password'])){
    $email=$_POST['email'];
  
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['email']="Email address is invalid"; 
    }
    
    if(empty($email)){
        $errors['email']="Email required";
    }

    if(count($errors)==0){
        $sql= "SELECT * from users where email='$email' limit 1";
        $result= mysqli_query($conn,$sql);
        $user= mysqli_fetch_assoc($result);
        $token= $user['token'];
        sendPasswordResetLink($email,$token);
        header('location: password_message.php');
        exit(0);

    }
}

//this was upto forgot password button
//--------------------------------------------------------------------------------------------------------

//code after user has received and click reset password link
function resetPassword($passwordToken){
    global $conn;
    $sql="SELECT * from users where token='$passwordToken' limit 1";
    $result= mysqli_query($conn,$sql);
    $user=mysqli_fetch_assoc($result);
    $_SESSION['email']= $user['email'];
    header('location:reset_password.php');
    exit(0);
    }

    //now if the user clicks reset password button
if(isset($_POST['reset-password-btn'])){
    $password1 = $_POST['password1'];
    $confirmPassword = $_POST['confirmPassword'];

    if(empty($password1) || empty($confirmPassword)){
        $errors['password']="Password required";
    }
    if($password1 != $confirmPassword){
        $errors['password']="Both passwords do not match";
    }

    //$password1=password_hash($password1, PASSWORD_DEFAULT);
    $email=$_SESSION['email'];

    if(count($errors)==0){
        $sql="UPDATE users set password='$password1' where email='$email'";
        $result=mysqli_query($conn,$sql);
        if($result){
            header('location:login.php');
            exit(0);
        }
    }



}