<link rel="stylesheet" href="<?= $href ?>style.css" />
<table width=100% class="head">
    <tr>
        <td width=15% height=75px align="center">
            <a href='<?= $href ?>index.php'><img src='<?= $href ?>Image/Web/logo.png' width=50px height=60px></a>
        </td>
        <td width=50% align="center">
            <form action="<?= $href ?>Picture/search.php" method="post">
                <input type="text" name="search" style="width: 350px; height:40px;" />
                <input type="submit" name="submit" value="Search User" style="width:85px; height:30px;" />
                <input type="submit" name="submit" value="Search Tag" style="width:85px; height:30px;" />
            </form>
        </td>
        <td width=10% align="center">
            <?php
            if (!session_id()) session_start();
            if (empty($_SESSION['uid'])) {
                $tp = 'login.php';
            } else {
                $tp = 'upload.php';
            }
            echo '<a href="' . $href . 'Account/' . $tp . '"><img src="' . $href . 'Image/Web/upload.png" style="width:60px; height:45px;"></a>';
            ?>
        </td>
        <td width=25% align="center">
            <?php
            if (empty($_SESSION['uid'])) {
                echo '<h1><a href="' . $href . 'Account/login.php" style="color:blue">Log In</a>&nbsp;&nbsp;&nbsp;<a href="' . $href . 'Account/register.php" style="color:gray">Sign Up</a></h1>';
            } else {
                echo '<h1><a href="' . $href . 'Account/profile.php" style="color:blue">' . $_SESSION["username"] . '</a>&nbsp;&nbsp;&nbsp;<a href="' . $href . 'exit.php" style="color:gray">Exit</a></h1>';
            }
            ?>
        </td>
    </tr>
</table>