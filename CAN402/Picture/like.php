<?php
if (!session_id()) session_start();
if (empty($_SESSION['uid'])) {
    echo "<script>alert('Please Log In !');location='../Account/login.php'</script>";
} else {
    $uid = $_SESSION['uid'];
    $pid = $_REQUEST['pid'];
    $like = $_REQUEST['like'];
    $status = $_REQUEST['status'];
    include '../connect.php';

    if ($status == 'unknown') {
        $sql = "INSERT INTO likes (pid, uid, ilike) VALUES ('$pid', '$uid', '$like')";
        mysqli_query($conn, $sql);

        if ($like == 'like') {
            $sql = "SELECT * FROM picture WHERE pid = '$pid'";
            $result = mysqli_query($conn, $sql);
            $row = $result->fetch_assoc();
            $num = $row['ilike'] + 1;
            $sql = "UPDATE picture SET ilike = '$num' WHERE pid = '$pid'";
            mysqli_query($conn, $sql);
        }
        if ($like == 'dislike') {
            $sql = "SELECT * FROM picture WHERE pid = '$pid'";
            $result = mysqli_query($conn, $sql);
            $row = $result->fetch_assoc();
            $num = $row['idislike'] + 1;
            $sql = "UPDATE picture SET idislike = '$num' WHERE pid = '$pid'";
            mysqli_query($conn, $sql);
        }
    }
    if ($status == 'like') {
        $sql = "SELECT * FROM picture WHERE pid = '$pid'";
        $result = mysqli_query($conn, $sql);
        $row = $result->fetch_assoc();
        $num = $row['ilike'] - 1;
        $sql = "UPDATE picture SET ilike = '$num' WHERE pid = '$pid'";
        mysqli_query($conn, $sql);

        if ($like == 'like') {
            $sql = "DELETE FROM likes WHERE pid = '$pid' AND uid = '$uid'";
            mysqli_query($conn, $sql);
        }
        if ($like == 'dislike') {
            $sql = "UPDATE likes SET ilike = '$like' WHERE pid = '$pid' AND uid = '$uid'";

            if ($conn->query($sql) === false) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            mysqli_query($conn, $sql);
            $sql = "SELECT * FROM picture WHERE pid = '$pid'";
            $result = mysqli_query($conn, $sql);
            $row = $result->fetch_assoc();
            $num = $row['idislike'] + 1;
            $sql = "UPDATE picture SET idislike = '$num' WHERE pid = '$pid'";
            mysqli_query($conn, $sql);
        }
    }
    if ($status == 'dislike') {
        $sql = "SELECT * FROM picture WHERE pid = '$pid'";
        $result = mysqli_query($conn, $sql);
        $row = $result->fetch_assoc();
        $num = $row['idislike'] - 1;
        $sql = "UPDATE picture SET idislike = '$num' WHERE pid = '$pid'";
        mysqli_query($conn, $sql);

        if ($like == 'like') {
            $sql = "UPDATE likes SET ilike = '$like' WHERE pid = '$pid' AND uid = '$uid'";
            mysqli_query($conn, $sql);

            $sql = "SELECT * FROM picture WHERE pid = '$pid'";
            $result = mysqli_query($conn, $sql);
            $row = $result->fetch_assoc();
            $num = $row['ilike'] + 1;
            $sql = "UPDATE picture SET ilike = '$num' WHERE pid = '$pid'";
            mysqli_query($conn, $sql);
        }
        if ($like == 'dislike') {
            $sql = "DELETE FROM likes WHERE pid = '$pid' AND uid = '$uid'";
            mysqli_query($conn, $sql);
        }
    }
    header("Location:picture.php?pid=$pid");
}
