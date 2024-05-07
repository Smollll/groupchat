<?php 
$con=mysqli_connect("localhost","root","","three_d","3307");

if(isset($_POST['message']) && isset($_POST['sender'])){

    $message= $_POST['message'];
    $registration_order_no = $_POST['sender'];
    $messageInsert = mysqli_query($con, "INSERT INTO groupmsg(message_content, sender)
    VALUES ('$message', '$registration_order_no')");
}

?>