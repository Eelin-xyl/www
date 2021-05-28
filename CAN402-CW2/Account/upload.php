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

<div align="center">
    <p></p>
    <h1>Uploaded Pictures</h1>

    <table>

        <?php
        $sql = "SELECT pid, description, tag, src FROM picture WHERE uid = '$uid'";
        $result = $conn->query($sql);
        echo "<tr>";
        $num = 0;
        while ($row = $result->fetch_assoc()) {
            $num += 1;
            echo '<td>
                    <a href="../Picture/picture.php?pid=' . $row["pid"] . '">
                        <img src="../' . $row["src"] . '" width = 300px hight = 300px>
                    </a><br/><br/>
                    <a class="tag" href="../Picture/tag.php?tag=' . $row["tag"] . '">' . $row["tag"] . '</a>
                        <br/>
                    <a>Description:&nbsp;&nbsp;' . $row["description"] . '</a>
              </td>';
            if ($num == 5) {
                echo '</tr><tr height="40px"></tr><tr>';
                $num = 0;
            }
        }
        echo "</tr>";

        ?>

    </table>

    <h1>New Picture</h1>
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