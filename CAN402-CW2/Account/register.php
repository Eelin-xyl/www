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
            <td>
                <h3>Account</h3>
            </td>
        </tr>
        <tr>
            <td>
                <input type="text" name="uid" style="width: 300px; height:40px" />
            </td>
        </tr>
        <tr height=30px></tr>
        <tr>
            <td>
                <h3>Password</h3>
            </td>
        </tr>
        <tr>
            <td>
                <input type="password" name="password" style="width: 300px; height:40px" />
            </td>
        </tr>
        <tr height=30px></tr>
        <tr>
            <td>
                <h3>Name</h3>
            </td>
        </tr>
        <tr>
            <td>
                <input type="text" name="name" style="width: 300px; height:40px" />
            </td>
        </tr>
        <tr height=30px></tr>
        <tr>
            <td>
                <input name="submit" type="submit" value="submit" style="width: 100px; height:30px; background-color: aquamarine;" />
            </td>
        </tr>
    </table>
</form>