<?php
    session_start();
    if(!isset($_SESSION['username']))
        header("location:index.php");
    if(isset($_POST['hostEventForm'])){
        $Timing = str_replace('T',' ',$_POST['Timing']);
        $Timing = $Timing.":00";
        $seatCount= $_POST["totalSeats"];
        $i=0;
        while($i<$seatCount){
            $Seats[$i]=$i;
            $i++;
        }
        $Seats=json_encode($Seats,true);
        $con = mysqli_connect("localhost","root","","eventbook");
            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }
            if(mysqli_connect_errno()){
                    echo "Failed to connect : " . mysqli_errno();
            }
        //echo 'INSERT INTO event_dict VALUES("'.$_POST["event_name"].'","'.$_SESSION["username"].'","'.$Seats.'","'.$Timing.'",'.$_POST["duration"].',"'.$_POST["category"].'",0)';
        $query = mysqli_query($con,'INSERT INTO event_dict VALUES("'.$_POST["event_name"].'","'.$_SESSION["username"].'",'.$_POST["totalSeats"].',"'.$Seats.'","'.$Timing.'",'.$_POST["duration"].',"'.$_POST["category"].'",0)');  
        $query = mysqli_query($con,'CREATE TABLE '.$_POST["event_name"].'(username TEXT,seatNumbers TEXT,contactMail TEXT, phoneNumber INT)');
        $query = mysqli_query($con,'SELECT hostedEvent FROM credentials WHERE username="'.$_SESSION["username"].'"');
        while($result = mysqli_fetch_array($query)){
            $hostedEvent=json_decode($result['hostedEvent'],true);
            $i=0;
            while(isset($hostedEvent[$i]))
                $i++;
            $hostedEvent[$i]=$_POST["event_name"];
            $hostedEvent=json_encode($hostedEvent,true);
            $query2 = mysqli_query($con,'UPDATE credentials SET hostedEvent=\''.$hostedEvent.'\' WHERE username="'.$_SESSION["username"].'" ');
        }
        mysqli_close($con);
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
            <?php
                if(!isset($_SESSION['username']))
                    echo '<button onClick="location.href=\'login.php\'" class="navbar-right btn btn-success">LOGIN</button>';
                else
                    echo '<button onClick="location.href=\'logout.php\'" class="navbar-right btn btn-danger">LOGOUT</button>';
            ?>
            </div>
        </nav>
        <div class="container" style="padding-top:5%;padding-left:25%;padding-right:25%">
        <form action="hostEvent.php" method="POST">
            <div class="form-group">
                Event Name
                <input type="text" class="form-control" id="event_name" name="event_name" placehodler="Event Name" required>
            </div>
            <div class="form-group">
                 Total Number of Seats
                <input type="number" class="form-control" id="totalSeats" name="totalSeats" placehodler="Total Number of Seats" required>
            </div>
            <div class="form-group">
                Timing
                <input type="datetime-local" class="form-control" id="Timing" name="Timing"  required>
            </div>
            <div class="form-group">
                Duration<small>&nbsp;(in minutes)</small>
                <input type="number" class="form-control" id="duration" name="duration" placehodler="Duration" required>
            </div>
            <div class="form-group">
                Category
                <select id="category" name="category"  required>
                    <option value="Action">Action</option>
                    <option value="Adventure">Adventure</option>
                    <option value="Comedy">Comedy</option>
                    <option value="Crime">Crime</option>
                    <option value="Drama">Drama</option>
                    <option value="Fantasy">Fantasy</option>
                    <option value="Historical">Historical</option>
                    <option value="others">others</option>
                </select>
            </div>
            <center><button type="submit" class="btn btn-primary" name="hostEventForm">Host the Event</button><center>
        </form>
        </div>
    </body>
</html>