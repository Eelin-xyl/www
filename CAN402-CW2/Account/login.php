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
<div align="center">
    <h1>LOGIN</h1>
    <form action="login.php" method="post">
        <table>
            <tr>
                <td>
                    <h3>Account</h3>
                </td>
            </tr>
            <tr>
                <td><input type="text" name="uid" style="width: 300px; height:40px" /></td>
            </tr>
            <tr height=30px></tr>
            <tr>
                <td>
                    <h3>Password</h3>
                </td>
            </tr>
            <tr>
                <td><input type="password" name="password" style="width: 300px;; height:40px" /></td>
            </tr>
            <tr height=30px></tr>
            <tr>
                <td>
                    <input type="submit" name="login" value="login" style="width: 100px; height:30px; background-color: aquamarine;" />
                </td>
            </tr>
        </table>
    </form>
</div>