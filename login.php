<?php
    session_start();
    if(isset($_POST['user_Signin'])){
        $con = mysqli_connect("localhost","root","","eventbook");
		if (!$con) {
		  die("Connection failed: " . mysqli_connect_error());
		}
		if(mysqli_connect_errno()){
				echo "Failed to connect : " . mysqli_errno();
        }
        $password=md5($_POST['password']);
        $query = mysqli_query($con,'SELECT * from credentials WHERE username="'.$_POST['username'].'" AND password="'.$password.'"');
        if(mysqli_num_rows($query) != 0){
            while($result = mysqli_fetch_array($query)){
                if ($result['password']==$password && $result['username']==$_POST['username']){
                    $_SESSION['username']=$_POST['username'];
                    $_SESSION['phn_no']=$result['phn_no'];
                    $_SESSION['contact_mail'] = $result['contact_mail'];
                    $_SESSION['hostedEvent'] = $result['hostedEvent'];
                    $_SESSION['bookedEvent'] = $result['bookedEvent'];
                    header("location:index.php");
                }
            }
            echo "Incrrect Credentials.!";
        }
        echo "Incrrect Credentials.!";
    }
    if(isset($_POST['signUP'])){
        $password=md5($_POST['password_Signup']);
        $con = mysqli_connect("localhost","root","","eventbook");
		if (!$con) {
		  die("Connection failed: " . mysqli_connect_error());
		}
		if(mysqli_connect_errno()){
				echo "Failed to connect : " . mysqli_errno();
		}
        $query = mysqli_query($con,'INSERT INTO credentials(username,password,age,phn_no,contact_mail) VALUES ("'.$_POST["username_Signup"].'","'.$password.'",'.$_POST["age_Signup"].','.$_POST["phn_no_Signup"].',"'.$_POST["contact_mail_Signup"].'");');
        mysqli_close($con);
        }
        if(isset($_REQUEST['username_check'])){
            $con = mysqli_connect("localhost","root","","eventbook");
            if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
            }
            if(mysqli_connect_errno()){
                    echo "Failed to connect : " . mysqli_errno();
            }
            $query = mysqli_query($con,'SELECT username FROM credentials WHERE username="'.$_REQUEST['username_check'].'"');
            if(mysqli_fetch_array($query))
                echo "TR";
            else
                echo "FAL";
            mysqli_close($con);
        }
?>
<html>
    <head>
        <title>Event Book</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    </head>
    <body>
        <nav class="navbar  navbar-dark bg-dark">
            <div class="container-fluid">
                <div class="navbar-header">
                <a class="navbar-brand" href="index.php"><b>EVENT BOOK</b></a>
                </div>
            </div>
        </nav>
        <div class="container" style="padding-top:5%">
            <div class="row">
                <div class="col-sm">
                <center><h1>Sign In</h1></center><br>
                    <form style="padding-left:25%;padding-right:25%" action="login.php" method="POST">
                        <div class="form-group">
                            Username
                            <input class="form-control" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            Password
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                        <center><button type="submit" name="user_Signin" class="btn btn-success">Login</button></center>
                    </form>
                </div>
                <div class="col-sm">
                     <center><h1>Sign Up</h1></center>
                     <form style="padding-left:25%;padding-right:25%" action="login.php" method="POST" >
                        <div class="form-group">
                            Username
                            <input class="form-control" name="username_Signup" oninput="checkUserName(this.value)" placeholder="Username"required> 
                            <small><p id="usernameCheck" style="color:red;display:none">Already Username exists. Please use some other Username</p></small><br>
                        </div>
                        <div class="form-group">
                            Password
                            <input type="password" class="form-control" name="password_Signup" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                            Age
                            <input type="number" class="form-control" name="age_Signup" placeholder="Age" required>
                        </div>
                        <div class="form-group">
                            Phone Number
                            <input type="number" class="form-control" name="phn_no_Signup" placeholder="Phone Number" required>
                        </div>
                        <div class="form-group">
                            Contact Mail-ID
                            <input type="email" class="form-control" name="contact_mail_Signup" placeholder="Contact Mail-ID" required>
                        </div>
                        <center><button type="submit" class="btn btn-primary" id="signUP" name="signUP">Signup</button></center>
                    </form>
                        <script>
                                function checkUserName(userName_check){
                                    var xmlhttp = new XMLHttpRequest();
                                    xmlhttp.onreadystatechange = function() {
                                        if (this.readyState == 4 && this.status == 200) {
                                            if(this.responseText[0]=="T"){
                                                document.getElementById('usernameCheck').style.display = "block";
                                                document.getElementById("signUP").disabled = true;
                                            }
                                            else{
                                                document.getElementById('usernameCheck').style.display = "none";
                                                document.getElementById("signUP").disabled = false;
                                            }
                                        }
                                    };
                                    xmlhttp.open("POST", "login.php?username_check=" + userName_check, true);
                                    xmlhttp.send();
                                }
                            </script>
                </div>
            </div>
        </div>
    </body>
</html>