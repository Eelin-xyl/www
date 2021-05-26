<style>
    td {
        text-align: center
    }

    .tag {
        background-color: yellow;
    }
</style>

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
            while ($row = $result->fetch_assoc()) {
                echo '<td><a href="picture.php?pid=' . $row["pid"] . '"><img src="../' . $row["src"] . '" width = 300px hight = 300px></a></td>';
            }
            echo "</tr>";
            echo "<tr>";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo '<td><a class="tag" href="tag.php?tag=' . $row["tag"] . '">' . $row["tag"] . '</a><br/><a>Description:&nbsp;&nbsp;' . $row["description"] . '</a></td>';
            }
            ?>

        </tr>
    </table>
</div>