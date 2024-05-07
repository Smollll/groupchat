<?php
session_start();
if (isset($_SESSION['username'])) {
    $name = $_SESSION['username'];

    $con = mysqli_connect("localhost","root","","three_d","3307");

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $result = mysqli_query($con, "SELECT registration_order_no, firstname, middlename, lastname FROM users WHERE username='$name'");
    if (!$result) {
        die("Error in SQL query: " . mysqli_error($con));
    }

    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $firstname = $row['firstname'];
        $fullname = $firstname . " " . $row['middlename'] . " " . $row['lastname'];
        $registration_order_no=$row['registration_order_no'];
        $_SESSION["registration_order_no"] = $registration_order_no;
        
    } else {
        $fullname = "Full name not found";
    }
} else {
    
    header("Location: login.php");
    exit();
}

?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="jquery.js" ></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #140000;
            margin: 0;
            padding: 0;
            margin-top: 3%;
        }

        .container {
            max-width: 1000px;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
        }
       

        .msg-container {
            margin-top: 20px;
        }
        .display-message {
            max-height: 600px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .message {
            margin-bottom: 10px;
            padding: 5px 10px;
            border-radius: 5px;
            word-wrap: break-word;
        }
        

        .msg-container {
    display: flex;
    flex-direction: column;
}

.sender-message {
    background-color: #660000;
    color: white; /* dito po color ng sender text */
    padding: 10px;
    border-radius: 10px;
    margin: 5px 0;
    word-wrap: break-word;
    align-self: flex-end; /* lalagay sa right */
    width: fit-content;
    max-width: 300px;
    order: 1; /* setting order of the messages */
    margin-left: auto;
    text-align: right;

}

.receiver-message {
    background-color: #D8D9DA;
    color: black; /* receiver color text */
    padding: 10px;
    border-radius: 10px;
    margin: 5px 0;
    word-wrap: break-word;
    align-self: flex-start; /* taga left */
    max-width: 300px;
    width: fit-content;
    order: 2; /* odah */
}







        #message {
            width: calc(100% - 20px);
            min-height: 50px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
        }

        input[type="button"] {
            background-color: #660000;
            color: white;
            padding: 20px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="button"]:hover {
            background-color: #0056b3;
        }.btn{
            background-color: rgba(255, 102, 102, 0.7);
            color: white;
            padding: 10px 20px; 
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
            font: 1em sans-serif;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="h2">
            <h1><?php echo $fullname; ?></h1>
        </div>
        <form action="" method="post">
            <button class="btn" type="submit" name="logout">Logout</button>
            <?php
            if (isset($_POST['logout'])) {
                session_destroy();
                header("location:login.php");
            }
            ?>
        </form>
        <div class="msg-container">
            <h1>Message Board</h1>
            <div class="display-message">
                <!-- Display messages here -->
            </div>
            <form action="" method="post">
                <input type="hidden" name="sender" id="sender" value="<?php echo $registration_order_no ?>">
                <textarea name="message" id="message" cols="30" rows="3" placeholder="Type your message here..."></textarea>
                <input type="button" value="Send" onclick="sendmsg()">
            </form>
        </div>
    </div>
    
    
</body>
</html>
<script>
    $(document).ready(function(){
        display();
        setInterval(fetchNewMessages, 3000);
    })

    function fetchNewMessages() {
        $.ajax({
            url: "display.php",
            type: "POST",
            success: function(data) {
                $(".display-message").html(data);

            },
            error: function(jqXHR, textStatus, errorThrown){
                console.error("failed to fetch new message" + textstatus +", " + errorThrown);
            }
        });
    }

    function sendmsg() {
        var send = new FormData();

        var textmsg = $("#message").val();
        var textsender = $("#sender").val();

        send.append("message", textmsg);
        send.append("sender", textsender);

        $.ajax({
            url: "sendmsg.php",
            type: "POST",
            data: send,
            contentType:false,
            processData: false,
            success: function(data) {
                $("message").val("");
                display();
                $(".display-message").scrollTop($(".display-message")[0].scrollHeight);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX request failed: " + textStatus + ", " + errorThrown);
                alert("There is an error while sending the messages.");
            }
        });
    }
        function display(){
            var display = new FormData();
            var textmsg = $("#message").val();
            display.append("message",textmsg);
            $.ajax({
                url: "display.php",
                type: "POST",
                data: display,
                contentType:false,
                processData:false,
                success: function(data){
                    $(".display-message").html(data);
                }
            });
        }
        </script>