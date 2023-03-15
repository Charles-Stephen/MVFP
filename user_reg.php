<?php
include("conn.php");

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $name = $_POST["name"];
            $email = $_POST["email"];
            $pass = $_POST["pass"];
            $phone = $_POST["phone"];


            $inp = "INSERT INTO `users`(`name`, `email`, `pass`, `phone`) VALUES ('$name','$email','$pass','$phone')";
            $res = mysqli_query($conn, $inp);

            if($res){
            echo "ok";
            }
            else{
                echo "faild";
            }




            // $sel = "SELECT * FROM `users` WHERE `emalil` = '$email' && `pass` = '$pass'";
            // $res = mysqli_query($db, $sel);
            // if(mysqli_num_rows($res)){
            //     echo $error = "User Already Exists";
            // }
            // else{
                // $inp = "INSERT INTO `users`(`name`, `emalil`, `pass`, `phone`) VALUES ('$name','$email','$pass','$phone')";
                // $res = mysqli_query($db, $inp);
            // }
        }
        else{
            echo "no reuquest";
        }
    
?>