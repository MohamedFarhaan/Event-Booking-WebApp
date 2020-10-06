<?php
    session_start();
    if(!isset($_SESSION['username']))
         header("location:index.php");
    if(isset($_POST['delEvent'])){
        $con = mysqli_connect("localhost","root","","eventbook");
            if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
            }
            if(mysqli_connect_errno()){
                    echo "Failed to connect : " . mysqli_errno();
            }
        $query = mysqli_query($con, 'DROP TABLE '.$_POST['eventNametoDelete'].' ');
        $query = mysqli_query($con, 'DELETE FROM event_dict WHERE eventname="'.$_POST['eventNametoDelete'].'"');
        mysqli_close($con);
        header("location:manageMyEvent.php");
    }
    if(isset($_REQUEST['eventnameGET'])){
        $con = mysqli_connect("localhost","root","","eventbook");
            if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
            }
            if(mysqli_connect_errno()){
                    echo "Failed to connect : " . mysqli_errno();
            }
        $query = mysqli_query($con,'SELECT * FROM '.$_REQUEST['eventnameGET'].' ');
        $i=0;
        while($result = mysqli_fetch_array($query)){
            $data['table vals'][$i]['username'] = $result['username'];
            $data['table vals'][$i]['seatNumbers'] = $result['seatNumbers'];
            $data['table vals'][$i]['contactMail'] = $result['contactMail'];
            $data['table vals'][$i]['phoneNumber'] = $result['phoneNumber'];
            $i++;
        }
        $data['count-rows'] = $i;
        $data =json_encode($data,true);
        echo $data;
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
            <?php
                if(!isset($_SESSION['username']))
                    echo '<button onClick="location.href=\'login.php\'" class="navbar-right btn btn-success">LOGIN</button>';
                else
                    echo '<button onClick="location.href=\'logout.php\'" class="navbar-right btn btn-danger">LOGOUT</button>';
            ?>
            </div>
        </nav>
        <div class="container" style="padding-top:5%">
            <center>
                <form action="manageMyEvent.php" method="POST">
                    Event(s) hosted : 
                    <select name="eventNametoDelete" onchange="selectEventDescribe(this.value)">
                    <option value="default">Select an Option</option>
                        <?php
                            $con = mysqli_connect("localhost","root","","eventbook");
                            if (!$con) {
                                die("Connection failed: " . mysqli_connect_error());
                            }
                            if(mysqli_connect_errno()){
                                    echo "Failed to connect : " . mysqli_errno();
                            }
                            $query = mysqli_query($con,'SELECT eventname FROM event_dict WHERE host="'.$_SESSION['username'].'"');
                            while($result=mysqli_fetch_array($query)){
                                echo '<option value = "'.$result['eventname'].'">'.$result['eventname'].'</select>';
                            }
                        ?>
                    </select>
                    <br><br>
                    <table id="eventDescription" style="width:100%">
                        <span id="eventTable">&nbsp;</span>
                        <script>
                        function selectEventDescribe(event_name){
                            
                            if(event_name=="default"){
                                alert("Select an Option");
                                var table = document.getElementById("eventDescription");
                                            table.innerHTML="";
                            }
                            else{
                                    var xmlhttp = new XMLHttpRequest();
                                    xmlhttp.onreadystatechange = function() {
                                        if (this.readyState == 4 && this.status == 200) {
                                            var data = this.responseText;
                                            data =data.substring(0,data.indexOf("<html>")) ;
                                            data = JSON.parse(data);
                                            count = data['count-rows'];
                                            var table = document.getElementById("eventDescription");
                                            table.innerHTML="";
                                            var row = table.insertRow();
                                                var cell1 = row.insertCell(0);
                                                var cell2 = row.insertCell(1);
                                                var cell3 = row.insertCell(2);
                                                var cell4 = row.insertCell(3);
                                                cell1.innerHTML = "Username";
                                                cell2.innerHTML ="Seat_nums";
                                                cell3.innerHTML = "Contact Mail";
                                                cell4.innerHTML ="Phone Number";
                                            i=0;
                                            while(i<count){
                                                var row = table.insertRow();
                                                var cell1 = row.insertCell(0);
                                                var cell2 = row.insertCell(1);
                                                var cell3 = row.insertCell(2);
                                                var cell4 = row.insertCell(3);
                                                cell1.innerHTML = data['table vals'][i]['username'];
                                                cell2.innerHTML = data['table vals'][i]['seatNumbers'];
                                                cell3.innerHTML = data['table vals'][i]['contactMail'];
                                                cell4.innerHTML = data['table vals'][i]['phoneNumber'];
                                                i++;
                                        }
                                        }
                                    };
                                    xmlhttp.open("POST", "manageMyEvent.php?eventnameGET=" + event_name, true);
                                    xmlhttp.send();
                                }
                        }
                    </script>
                    </table>
                    
                   <br> <button type="submit" class="btn btn-danger"  name="delEvent">Delete Event</button>
                </form>
            </center>
        </div>
    </body>
</html>