<?php
include("conn.php");

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $name = $_POST["name"];
            $email = $_POST["email"];
            $pass = $_POST["pass"];
            $phone = $_POST["phone"];


            $inp = "INSERT INTO `users`(`name`, `email`, `pass`, `phone`, `role`) VALUES ('$name','$email','$pass','$phone',2)";
            $res = mysqli_query($conn, $inp);

            if($res){
            echo "ok";
            }
            else{
                echo "faild";
            }

        }
        else{
            echo "no reuquest";
        }
    
?> 