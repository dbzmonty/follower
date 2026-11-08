<?php      
    if (!isset($_SESSION['username']))
    {           
        header("location:login.php");
    }    
    
    header("location:shows.php");
?>
