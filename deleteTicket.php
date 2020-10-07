
<?php
    session_start();
    if(isset($_POST['deltckts'])){
       
        $con = mysqli_connect("localhost","root","","eventbook");
            if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
            }
            if(mysqli_connect_errno()){
                    echo "Failed to connect : " . mysqli_errno();
            }
            $query = mysqli_query($con,'SELECT * FROM '.$_POST["DeleteEventName"].' ');
            while($result=mysqli_fetch_array($query)){
                $SeatsBookedByUser = json_decode($result["seatNumbers"],true);
                $i=0;
                $query2 = mysqli_query($con,'SELECT * FROM event_dict WHERE eventname = "'.$_POST["DeleteEventName"].'"');
                $event_dict = mysqli_fetch_array($query2);
                $seatsSold=$event_dict['seatsSold'];
                $totalSeats=$event_dict['totalSeats'];
                $seatings_eventdict = json_decode($event_dict['Seatings'],true);
                while($i<count($SeatsBookedByUser)){
                    $seatings_eventdict[($totalSeats-$seatsSold)+$i] = $SeatsBookedByUser[$i];
                    $i++;
                }
                $seatsSold=$seatsSold-$i;
                $seatings_eventdict=json_encode($seatings_eventdict,true);
                $query = mysqli_query($con, 'UPDATE event_dict SET Seatings="'.$seatings_eventdict.'",seatsSold='.$seatsSold.' WHERE eventname="'.$_POST["DeleteEventName"].'" ');
                $query = mysqli_query($con, 'DELETE FROM '.$_POST["DeleteEventName"].' WHERE username="'.$_SESSION["username"].'" ');
               // echo 'DELETE FROM '.$_POST["DeleteEventName"].' WHERE username="'.$_SESSION["username"].'" ';
            }
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
        <div class="container" style="padding-top:5%">
            <center>
                <h1>Delete Event Ticket</h1>
                <form action="deleteTicket.php" method="POST">
                    <select name="DeleteEventName" id="DeleteEventName" onchange=checkOption(this.value)>
                    <option value="deafult">Select an event</event>
                    <?php
                        $con = mysqli_connect("localhost","root","","eventbook");
                        if (!$con) {
                          die("Connection failed: " . mysqli_connect_error());
                        }
                        if(mysqli_connect_errno()){
                                echo "Failed to connect : " . mysqli_errno();
                        }
                        $query = mysqli_query($con,'SELECT bookedEvent FROM credentials WHERE username="'.$_SESSION["username"].'"');
                        while($result=mysqli_fetch_array($query)){
                            $bookedEventsArray = json_decode($result['bookedEvent'],true);
                            $i=0;
                            while(isset($bookedEventsArray[$i]))
                                echo '<option value="'.$bookedEventsArray[$i].'">'.$bookedEventsArray[$i++].'</option>' ;   
                        }
                        mysqli_close($con);
                    ?> 
                    </select><br><br>
                    <button type="submit" id="deltckts" name="deltckts" class="btn btn-danger">Delete Tickets</button>
                    <script>
                        if(document.getElementById("DeleteEventName").value=="deafult")
                            document.getElementById("deltckts").disabled=true;
                        function checkOption(opt){
                         
                            if(opt=="deafult"){
                                alert("PLease select an Option");
                                document.getElementById("deltckts").disabled=true;
                            }
                            else
                            document.getElementById("deltckts").disabled=false;
                        }
                    </script>
                </form>
            </center>
        </div>
    </body>
</html>