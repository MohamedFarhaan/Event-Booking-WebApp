<?php
    session_start();
    if(isset($_POST['logoutButton'])){
        session_destroy();
        header("location:index.php");
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
        <div style="padding-top:12.5%">
            <center>
            Are you sure, You want to <b>logout<b><br>
            <br><form action="logout.php" method="POST">
                <button type="submit"  name="logoutButton">Yes</button>
                
            </form><button onclick="window.location.href='index.php'">no</button>
            </center>
        </div>
    </body>
</html>