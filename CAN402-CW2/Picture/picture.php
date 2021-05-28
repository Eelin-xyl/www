<?php
if (!session_id()) session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $pid = $_GET["pid"];
} else {
    $uid = $_SESSION['uid'];
    $comment = $_POST['comment'];
    $pid = $_POST['pid'];
    if (!$comment) {
        echo '<script>alert("Please Input Comment !");location="picture.php?pid=' . $pid . '"</script>';
    } else {
        include '../connect.php';
        $sql = "INSERT INTO comment (pid, comment, uid, time) VALUES ('$pid', '$comment', '$uid'," . time() . ")";
        mysqli_query($conn, $sql);
    }
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
$like = $row['ilike'];
$dislike = $row['idislike'];
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

                <?php
                $plike = 'ulike.png';
                $pdislike = 'udislike.png';
                $status = 'unknown';
                if (!empty($_SESSION['uid'])) {
                    $uid = $_SESSION['uid'];
                    $sql = "SELECT * FROM likes WHERE pid = '$pid' AND uid = '$uid'";
                    $result = $conn->query($sql);
                    if ($result == true) {
                        while ($row = $result->fetch_assoc()) {
                            if ($row['ilike'] == 'like') {
                                $plike = "like.png";
                                $status = 'like';
                            }
                            if ($row['ilike'] == 'dislike') {
                                $pdislike = 'dislike.png';
                                $status = 'dislike';
                            }
                        }
                    }
                }
                ?>

                <a href="like.php?pid=<?= $pid ?>&like=like&status=<?= $status ?>"><img src="../Image/Web/<?= $plike ?>" width="50px" height="50px"></a><?= $like ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="like.php?pid=<?= $pid ?>&like=dislike&status=<?= $status ?>"><img src="../Image/Web/<?= $pdislike ?>" width="50px" height="50px"></a><?= $dislike ?>
            </td>
        </tr>
        <?php
        $sql = "SELECT * from comment WHERE pid = $pid";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $s = 'SELECT name from userlist WHERE uid = ' . $row["uid"];
            $res = $conn->query($s);
            $r = $res->fetch_assoc();
            $cname = $r['name'];
            echo '<tr class="cmt"><td>' . $row["comment"] . '</td><td>' . $cname . '<br/>' . date('Y-m-d', $row["time"]) . '</td></tr>';
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