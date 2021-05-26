<?php
if (!session_id()) session_start();
session_destroy();
echo "<script>alert('Log Out !');location='index.php'</script>";
