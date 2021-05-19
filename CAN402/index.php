<html>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web";
 
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("connect failed: " . $conn->connect_error);
} 
else
{
    echo "yes";
}
$sql = "SELECT uid, password, nickname, origin, money, regtime FROM userlist";
$result = $conn->query($sql);
 
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "uid: " . $row["uid"]. " password: " . $row["password"]. "nickname: " . $row["nickname"] . "origin: " . $row["origin"] . "money: " . $row["money"] . "regtime: " . $row["regtime"] . "<br>";
    }
} else {
    echo "null";
}
$conn->close();
?>

</body>
</html>
