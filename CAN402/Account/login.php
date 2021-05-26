<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uid = $_POST["uid"];
    $password = $_POST["password"];
    if (!$uid || !$password) {
        echo "<script>alert('Please Input Account and Password !');location='login.php'</script>";
    } else {
        include '../connect.php';

        $sql = "SELECT uid, password, name FROM userlist";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            if ($row["uid"] == $uid && $row["password"] == $password) {
                if (!session_id()) session_start();
                $_SESSION['uid'] = $uid;
                $_SESSION['username'] = $row["name"];
                header("Location:../index.php");
            }
        }
        echo "<script>alert('Incorrect Account or Password !');location='login.php'</script>";
    }
}

$href = '../';
include '../header.php';
?>
<h1 align="center">LOGIN</h1>
<form action="login.php" method="post">
    <table align="center">
        <tr>
            <td>Account</td>
        </tr>
        <tr>
            <td><input type="text" name="uid" /></td>
        </tr>
        <tr>
            <td>Password</td>
        </tr>
        <tr>
            <td><input type="password" name="password" /></td>
        </tr>
        <tr>
            <td>
                <input type="submit" name="login" value="login" />
            </td>
        </tr>
        <tr>
            <td><a href="register.php">Register An Account</a></td>
        </tr>
    </table>
</form>