<style>
    td {
        text-align: center
    }

    .tag {
        background-color: yellow;
    }
</style>
<?php
if (!session_id()) session_start();
$uid = $_SESSION['uid'];
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $pid = $_GET["pid"];
} else {
    $comment = $_POST['comment'];
    $pid = $_POST['pid'];
    include '../connect.php';
    // $pid = $_SESSION['pid'];
    $sql = "INSERT INTO comment (pid, comment, uid, time) VALUES ('$pid', '$comment', '$uid'," . time() . ")";
    mysqli_query($conn, $sql);
}

$href = '../';
include '../header.php';
include '../connect.php';
$sql = "SELECT * FROM picture WHERE pid = '$pid'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$description = $row['description'];
$tag = $row['tag'];
$src = $row['src'];
$puid = $row['uid'];
$sql = "SELECT uid, name FROM userlist";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    if ($row['uid'] == $puid) {
        $pname = $row['name'];
    }
}
?>

<div align="center">
    <table>
        <tr height=400px>
            <td><img src="../<?= $src ?>" style="width:500px; height:300px;"></td>
            <td width=200px></td>
            <td>
                <a class="tag" href="tag.php?tag=<?= $tag ?>"><?= $tag ?></a><br /><br /><br />
                <a>Description:&nbsp;<?= $description ?></a><br /><br /><br />
                <a>Author:&nbsp;<?= $pname ?></a><br /><br /><br />
                <a href="like.php?pid=<?= $pid ?>"><img src="../Image/like.png" width="50px" height="50px"></a>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="like.php?pid=<?= $pid ?>"><img src="../Image/dislike.png" width="50px" height="50px"></a>
            </td>
        </tr>
        <?php
        $sql = "SELECT * from comment WHERE pid = $pid";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $s = "SELECT name from userlist WHERE uid = '$uid'";
            $res = $conn->query($s);
            $r = $res->fetch_assoc();
            $cname = $r['name'];
            echo '<tr><td>' . $row["comment"] . '</td><td>' . $cname . '<br/>' . date('Y-m-d', $row["time"]) . '</td></tr>';
        }
        ?>
    </table>

    <p>&nbsp;</p>

    <?php
    if (!empty($_SESSION['uid'])) {
        echo '
    <form action="picture.php" method="post">
        <textarea name="comment" cols="50" rows="5"></textarea>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="hidden" name="pid" value="' . $pid . '">
        <input type="submit" name="submit" value="comment" />
    </form>
    ';
    }
    ?>

</div>