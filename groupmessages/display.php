<?php
include('connect.php');

session_start();

if (isset($_SESSION['registration_order_no'])) {
    $logged_in_user_registration_order_no = $_SESSION['registration_order_no'];
} else {
    exit("User not logged in");
}


$sql = "
SELECT groupmsg.message_content, users.firstname, users.middlename, users.lastname, users.registration_order_no
FROM groupmsg JOIN users ON groupmsg.sender = users.registration_order_no";

$result = mysqli_query($connect, $sql);

if($result){
    while ($row = mysqli_fetch_assoc($result)) {
        $is_sender_logged_in_user = ($row['registration_order_no'] == $logged_in_user_registration_order_no);

        $message_class = $is_sender_logged_in_user ? "sender-message" : "receiver-message";

        echo "<div class ='$message_class'>";
        echo htmlspecialchars($row['firstname']) . "<br> ";
        echo htmlspecialchars($row['message_content']) . "<br>";
        echo "</div>";
    }
} else {
    echo "error fetching messages: " . mysqli_error($connect);
}
?>