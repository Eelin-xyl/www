<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uid = $_POST["uid"];
    $password = $_POST["password"];
    $name = $_POST["name"];
    if (!$uid || !$password || !$name) {
        echo "<script>alert('Please Input Information !');location='register.php'</script>";
    } else {
        include '../connect.php';
        $sql = "SELECT uid FROM userlist";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            if ($row["uid"] == $uid) {
                echo "<script>alert('Account already exists !');location='register.php'</script>";
            }
        }
        $sql = "INSERT INTO userlist (uid, password, name) VALUES ('$uid', '$password', '$name')";
        mysqli_query($conn, $sql);
        echo "<script>alert('Register Successfully !');location='login.php'</script>";
    }
}

$href = '../';
include '../header.php';
?>

<h1 align="center">REGISTER</h1>
<form action="register.php" method="post">
    <table align="center">
        <tr>
            <td>Account</td>
        </tr>
        <tr>
            <td>
                <input type="text" name="uid" />
            </td>
        </tr>
        <tr>
            <td>Password</td>
        </tr>
        <tr>
            <td>
                <input type="password" name="password" />
            </td>
        </tr>
        <tr>
            <td>Name</td>
        </tr>
        <tr>
            <td>
                <input type="text" name="name" />
            </td>
        </tr>
        <tr>
            <td>
                <input name="submit" type="submit" value="submit" />
            </td>
        </tr>
    </table>
</form>