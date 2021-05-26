<?php
if (!session_id()) session_start();
$uid = $_SESSION['uid'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST["name"];
    $password = $_POST["password"];
    $description = $_POST["description"];
    $file = $_FILES["photo"];
    if (!$name || !$password) {
        echo "<script>alert('Please Input Name and Password !');location='profile.php'</script>";
    } else {
        include '../connect.php';
        if ($file["error"] > 0) {
            $sql = "SELECT photo FROM userlist WHERE uid = '$uid'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $filename = $row['photo'];
        } else {
            $file["tmp_name"];
            $filename = 'Image/Photo/' . time() . $file["name"];
            move_uploaded_file($file["tmp_name"], "../" . $filename);
        }
        $sql = "UPDATE userlist SET name = '$name', password = '$password', description = '$description', photo = '$filename'  WHERE uid = '$uid'";
        mysqli_query($conn, $sql);
        if (!session_id()) session_start();
        $_SESSION['username'] = $name;
        echo "<script>alert('Modify Successfully !');location='profile.php'</script>";
    }
}

$href = '../';
include '../header.php';
include '../connect.php';
$sql = "SELECT uid, password, name, description, photo FROM userlist WHERE uid = '$uid'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$password = $row['password'];
$name = $row['name'];
$dcp = $row['description'];
$pht = $row['photo'];

if (!$pht) {
    $pht = 'Image/Photo/user.png';
}
?>
<style>
    td {
        text-align: center
    }
</style>
<div align="center">
    <p></p>
    <img src="../<?= $pht ?>" width=150px hight=150px><br />
    <p></p>
    <table width=350px>
        <tr>
            <td>Account: </td>
            <td><?= $_SESSION["uid"] ?></td>
        </tr>
        <tr>
            <td>Name: </td>
            <td><?= $name ?></td>
        </tr>
        <tr>
            <td>Password: </td>
            <td><?= $password ?></td>
        </tr>
        <tr>
            <td>Description: </td>
            <td><?= $dcp ?></td>
        </tr>
    </table>
    <p>&nbsp;</p>
    <h2>Modify</h2>
    <form action="profile.php" method="post" enctype="multipart/form-data">
        <table align="center">
            <tr>
                <td>Name</td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="name" value="<?= $name ?>" />
                </td>
            </tr>
            <tr>
                <td>Password</td>
            </tr>
            <tr>
                <td>
                    <input type="password" name="password" value="<?= $password ?>" />
                </td>
            </tr>
            <tr>
                <td>Description</td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="description" value="<?= $dcp ?>" />
                </td>
            </tr>
            <tr>
                <td>Avatar</td>
            </tr>
            <tr>
                <td><input type="file" name="photo" /></td>
            </tr>
            <tr>
                <td>
                    <input name="submit" type="submit" value="submit" />
                </td>
            </tr>
        </table>
    </form>
</div>