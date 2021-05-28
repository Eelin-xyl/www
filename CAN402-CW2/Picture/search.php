<?php
$href = '../';
include '../header.php';
$search = $_POST['search'];
if (!$search) {
    echo "<script>alert('Please Input Search Information !');location='../index.php'</script>";
}
?>
<h1><?= $_POST['submit'] ?>&nbsp;&nbsp;[<?= $search ?>]</h1>
<div align="center">
    <table>
        <tr>

            <?php
            include '../connect.php';
            if ($_POST['submit'] == "Search User") {
                $sql = "SELECT uid, name FROM userlist";
                $result = $conn->query($sql);
                $suid = '';
                while ($row = $result->fetch_assoc()) {
                    if ($row['name'] == $search) {
                        $suid = $row['uid'];
                    }
                }
                $sql = "SELECT * FROM picture WHERE uid = '$suid'";
                $result = $conn->query($sql);
                $num = 0;
                while ($row = $result->fetch_assoc()) {
                    $num += 1;
                    echo '<td>
                            <a href="picture.php?pid=' . $row["pid"] . '">
                                <img src="../' . $row["src"] . '" width = 300px hight = 300px>
                            </a><br/><br/>
                            <a class="tag" href="tag.php?tag=' . $row["tag"] . '">' . $row["tag"] . '</a>
                                <br/>
                            <a>Description:&nbsp;&nbsp;' . $row["description"] . '</a>
                      </td>';
                    if ($num == 5) {
                        echo '</tr><tr height="40px"></tr><tr>';
                        $num = 0;
                    }
                }
            } else if ($_POST['submit'] == "Search Tag") {
                $sql = "SELECT * FROM picture WHERE tag = '$search'";
                $result = $conn->query($sql);
                $num = 0;
                while ($row = $result->fetch_assoc()) {
                    $num += 1;
                    echo '<td>
                            <a href="picture.php?pid=' . $row["pid"] . '">
                                <img src="../' . $row["src"] . '" width = 300px hight = 300px>
                            </a><br/><br/>
                            <a class="tag" href="tag.php?tag=' . $row["tag"] . '">' . $row["tag"] . '</a>
                                <br/>
                            <a>Description:&nbsp;&nbsp;' . $row["description"] . '</a>
                      </td>';
                    if ($num == 5) {
                        echo '</tr><tr height="40px"></tr><tr>';
                        $num = 0;
                    }
                }
            }
            ?>

        </tr>
    </table>
    <p>&nbsp;</p>
    <h4>No More Picture</h4>
</div>