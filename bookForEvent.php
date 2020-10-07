
<?php
    session_start();
    if(isset($_POST['booktckts'])){
        $tcktCount = $_POST['num_of_tckts_to_book'];
        $Eventname= $_POST["bookEventName"];
        $con = mysqli_connect("localhost","root","","eventbook");
            if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
            }
            if(mysqli_connect_errno()){
                    echo "Failed to connect : " . mysqli_errno();
            }
            $i=0;
        $query=mysqli_query($con,'SELECT * FROM event_dict WHERE eventname="'.$Eventname.'"');
        while($result = mysqli_fetch_array($query)){
            $seating=json_decode($result["Seatings"],true);
            $seatsSold = number_format($result['seatsSold']) + $tcktCount;
            while($i<$tcktCount ){
               $SeatforUser[$i]=$seating[$i];
                $i++;
            }
            $seating=array_splice($seating,$tcktCount);
            $seating = json_encode($seating);
            $SeatforUser = json_encode($SeatforUser);
            echo $seatsSold;
           $query = mysqli_query($con,'UPDATE event_dict SET Seatings=\''.$seating.'\', seatsSold='.$seatsSold.' WHERE eventname="'.$Eventname.'" ');
            $query = mysqli_query($con, 'INSERT INTO '.$Eventname.' VALUES("'.$_SESSION["username"].'",\''.$SeatforUser.'\',"'.$_SESSION["contact_mail"].'",'.$_SESSION["phn_no"].')');
            $query = mysqli_query($con, 'SELECT * FROM credentials WHERE username="'.$_SESSION["username"].'"');
            while($result=mysqli_fetch_array($query)){
                $bookedEventsOfUser = json_decode($result['bookedEvent']);
                $i=0;
                while(isset($bookedEventsOfUser[$i]))
                    $i++;
                $bookedEventsOfUser[$i]=$Eventname;
                $bookedEventsOfUser = json_encode($bookedEventsOfUser);
               $query2 = mysqli_query($con,'UPDATE credentials SET bookedEvent=\''.$bookedEventsOfUser.'\' WHERE username="'.$_SESSION["username"].'"');
            }
            mysqli_close($con);
          //  header("location:index.php");
        }
    }
    if(!isset($_SESSION['username']))
        header("location:index.php");
        if(isset($_REQUEST['tktAvailc'])){
            $con = mysqli_connect("localhost","root","","eventbook");
            if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
            }
            if(mysqli_connect_errno()){
                    echo "Failed to connect : " . mysqli_errno();
            }
            $query=mysqli_query($con,'SELECT * FROM event_dict WHERE eventname="'.$_REQUEST['tktAvailc'].'"');
            while($result=mysqli_fetch_array($query)){
                $SeatsAvail = $result['totalSeats'] - $result['seatsSold'];
                echo  $SeatsAvail;
            }
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
                <h1>Select an Event to book ticket for</h1>
                <form action="bookForEvent.php" method="POST">
                    <select name="bookEventName" onchange="TktAvail(this.value)">
                    <option value="deafult">Select an event</event>
                    <?php
                        $con = mysqli_connect("localhost","root","","eventbook");
                        if (!$con) {
                          die("Connection failed: " . mysqli_connect_error());
                        }
                        if(mysqli_connect_errno()){
                                echo "Failed to connect : " . mysqli_errno();
                        }
                        $query = mysqli_query($con,'SELECT eventname FROM event_dict WHERE host<>"'.$_SESSION["username"].'"');
                        while($result=mysqli_fetch_array($query)){
                            echo '<option value="'.$result["eventname"].'">'.$result["eventname"].'</option>' ;   
                        }
                        mysqli_close($con);
                    ?> 
                    </select><br><br>
                        Tickets Available : <span id="tcktAvail"></span><br>
                        <input type="number" name="num_of_tckts_to_book" id="num_of_tckts_to_book" oninput="checkValue(this.value)" required>
                    <button type="submit" name="booktckts" class="btn btn-success">Book Tickets</button>
                    <script>
                        function checkValue(tcktcount){
                            availableSeats = parseInt(document.getElementById("tcktAvail").textContent);
                            if(tcktcount>availableSeats){
                                alert("Please Select Ticket count less than "+availableSeats);
                                document.getElementById("num_of_tckts_to_book").value="";
                            }
                        }
                        function TktAvail(eventNametoCheck){
                            if(eventNametoCheck=="deafult"){
                                alert("Please Select an Option");
                            }
                            else{
                                var xmlhttp = new XMLHttpRequest();
                                xmlhttp.onreadystatechange = function() {
                                    if (this.readyState == 4 && this.status == 200) {
                                        var SeatCountAvail = this.responseText;
                                        SeatCountAvail = parseInt(SeatCountAvail.substring(0,SeatCountAvail.indexOf("<html>")));
                                        document.getElementById("tcktAvail").textContent=SeatCountAvail;
                                }
                                };
                                xmlhttp.open("POST", "bookForEvent.php?tktAvailc=" + eventNametoCheck, true);
                                xmlhttp.send();
                            }
                         }
                    </script>
                </form>
            </center>
        </div>
    </body>
</html>