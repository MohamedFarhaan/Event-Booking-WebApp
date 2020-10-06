
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
            $seatForUser[];$i=0;
        $query=mysqli_query($con,'SELECT * FROM event_dict WHERE eventname="'.$Eventname.'"');
        while($result = mysqli_fetch_array($query)){
            while($i<)
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
                <a class="navbar-brand" href="#"><b>EVENT BOOK</b></a>
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
                    </script>
                </form>
            </center>
        </div>
    </body>
</html>