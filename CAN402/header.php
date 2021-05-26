<style>
    .head {
        border: 2px solid #151515
    }
</style>
<table width=100% class="head">
    <tr>
        <td width=15% align="center">
            <a href='<?= $href ?>index.php'><img src='<?= $href ?>Image/logo.png' width=50px height=50px></a>
        </td>
        <td width=50% align="center">
            <form action="<?= $href ?>Picture/search.php" method="post">
                <input type="text" name="search" style="width: 350px; height:30px;" />
                <input type="submit" name="submit" value="Search User" style="width:85px; height:30px;" />
                <input type="submit" name="submit" value="Search Tag" style="width:85px; height:30px;" />
            </form>
        </td>
        <td width=10% align="center">
            <?php
            if (!session_id()) session_start();
            if (empty($_SESSION['uid'])) {
                echo '<a href="' . $href . 'Account/login.php"><button style="width:60px; height:30px;">Upload</button></a>';
            } else {
                echo '<a href="' . $href . 'Account/upload.php"><button style="width:60px; height:30px;">Upload</button></a>';
            }
            ?>
        </td>
        <td width=25% align="center">
            <?php
            if (empty($_SESSION['uid'])) {
                echo '<h2><a href="' . $href . 'Account/login.php" style="color:blue">Log In</a>&nbsp;&nbsp;&nbsp;<a href="' . $href . 'Account/register.php" style="color:gray">Sign Up</a></h2>';
            } else {
                echo '<h2><a href="' . $href . 'Account/profile.php" style="color:blue">' . $_SESSION["username"] . '</a>&nbsp;&nbsp;&nbsp;<a href="' . $href . 'exit.php" style="color:gray">Exit</a></h2>';
            }
            ?>
        </td>
    </tr>
</table>