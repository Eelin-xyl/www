<?php
$href = '../';
include '../header.php';
include '../connect.php';
if (!session_id()) session_start();
$uid = $_SESSION["uid"];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = $_POST["description"];
    $tag = $_POST["tag"];
    $file = $_FILES["picture"];
    if (!$description || !$tag) {
        echo "<script>alert('Please Input Information !');location='upload.php'</script>";
    } else if ($file["error"] != 0) {
        echo '<script>alert(' . $file["error"] . ');location="upload.php"</script>';
    } else {
        // $file = $_FILES["picture"];
        $file["tmp_name"];
        $filename = 'Image/Picture/' . date('YmdHis') . $file["name"];
        move_uploaded_file($file["tmp_name"], "../" . $filename);
        $sql = "INSERT INTO picture (uid, description, tag, src, ilike, idislike) VALUES ('$uid', '$description', '$tag', '$filename', 0, 0)";
        if ($conn->query($sql) === false) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<style>
    td {
        text-align: center
    }

    .tag {
        background-color: yellow;
    }
</style>
<div align="center">
    <p></p>
    <h2>Uploaded Pictures</h2>

    <table>

        <?php
        $sql = "SELECT pid, description, tag, src FROM picture WHERE uid = '$uid'";
        $result = $conn->query($sql);
        echo "<tr>";
        while ($row = $result->fetch_assoc()) {
            echo '<td><img src="../' . $row["src"] . '" width = 300px hight = 300px></td>';
        }
        echo "</tr>";
        echo "<tr>";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo '<td><a class="tag" href="../Picture/tag.php?tag=' . $row["tag"] . '">' . $row["tag"] . '</a><br/><a>Description:&nbsp;&nbsp;' . $row["description"] . '</a></td>';
        }
        echo "</tr>";

        ?>

    </table>

    <h2>New Picture</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td><input type="file" name="picture" /></td>
            </tr>
            <tr>
                <td>Description</td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="description" />
                </td>
            </tr>
            <tr>
                <td>Tag</td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="tag" />
                </td>
            </tr>
            <tr>
                <td>
                    <input name="submit" type="submit" value="submit" />
                </td>
            </tr>
        </table>
    </form>
</div>