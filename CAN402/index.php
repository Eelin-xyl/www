<style>
    td {
        text-align: center
    }

    .tag {
        background-color: yellow;
    }
</style>

<?php
$href = '';
include 'header.php';
?>
<h1>WELCOME !</h1>
<div align="center">
    <table>
        <tr>

            <?php
            include 'connect.php';
            $sql = "SELECT * FROM picture";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo '<td><a href="Picture/picture.php?pid=' . $row["pid"] . '"><img src="' . $row["src"] . '" width = 300px hight = 300px></a></td>';
            }
            echo "</tr>";
            echo "<tr>";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo '<td><a class="tag" href="Picture/tag.php?tag=' . $row["tag"] . '">' . $row["tag"] . '</a><br/><a>Description:&nbsp;&nbsp;' . $row["description"] . '</a></td>';
            }

            ?>
        </tr>
    </table>
</div>