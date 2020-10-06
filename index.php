<?php
    session_start();
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
                <a class="navbar-brand" href="#"><b>EVENT BOOK</b></a>
                </div>
            <?php
                if(!isset($_SESSION['username']))
                    echo '<button onClick="location.href=\'login.php\'" class="navbar-right btn btn-success">LOGIN</button>';
                else
                    echo '<button onClick="location.href=\'logout.php\'" class="navbar-right btn btn-danger">LOGOUT</button>';
            ?>
            </div>
        </nav>
        <?php
        if(!isset($_SESSION['username']))
            echo '<div style="display:none">';
    ?>
        <div class="container" style="padding-top:5%">
            <div class="row">
                <div class="col-sm">
                <center><h1>My events</h1><br>
                   <button class="btn btn-primary" onClick="location.href='manageMyEvent.php'">Manage My events</button><br><br>
                    <button class="btn btn-info" onClick="location.href='hostEvent.php'">Host an event</button><br><br>
                    <button class="btn btn-success" onClick="location.href='bookForEvent.php'"> Book for an Event</button></center><br>
                </div>
                <div class="col-sm">
                   <center> <h1>My Upcoming Events</h1> </center>
                </div>
            </div>
        </div>
        <?php
        if(!isset($_SESSION['username']))
            echo '</div >';
    ?>
    </body>
</html>