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
            $num = 0;
            while ($row = $result->fetch_assoc()) {
                $num += 1;
                echo '<td>
                            <a href="Picture/picture.php?pid=' . $row["pid"] . '">
                                <img src="' . $row["src"] . '" width = 300px hight = 300px>
                            </a><br/><br/>
                            <a class="tag" href="Picture/tag.php?tag=' . $row["tag"] . '">' . $row["tag"] . '</a>
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
</div>