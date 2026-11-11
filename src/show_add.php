<?php
    require_once('./includes/header.php');
    
    if (!isset($_SESSION['username']))
    {   
        header("location:login.php");
    }    
    else if (isset($_POST["submit"]))
    {
        $show_id = $_POST['show_id'];
        $show_name = $_POST['show_name'];

        // Query
        $sql = 'INSERT INTO Shows (show_id, show_name) VALUES(?, ?)';

        // Create prepared statement
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql))
        {
            header("location:shows.php?error=true");
        }
        else
        {
            // Binding parameters
            mysqli_stmt_bind_param($stmt, 'is', $show_id, $show_name);
            // Executing prepared statement
            mysqli_stmt_execute($stmt);
            // Redirect to index site
            header("location:shows.php");
        }

        require_once('./includes/footer.php');
    }
    else
    {
        header("location:index.php");
    }
?>
