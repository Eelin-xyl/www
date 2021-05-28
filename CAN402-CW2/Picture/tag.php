<?php
$href = '../';
include '../header.php';
$tag = $_GET['tag'];
?>
<h1>HashTag:&nbsp;&nbsp;[<?= $tag ?>]</h1>
<div align="center">
    <table>
        <tr>

            <?php
            include '../connect.php';
            $sql = "SELECT * FROM picture WHERE tag = '$tag'";
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
            ?>

        </tr>
    </table>
    <p>&nbsp;</p>
    <h4>No More Picture</h4>
</div>