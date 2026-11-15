<?php
    require_once('./includes/header.php');
      
    if (!isset($_SESSION['username']))
    {   
        header("location:login.php");
    }    
		
    if (isset($_POST["submit"]))
    {
        $show_id = (int)$_POST['show_id'];

        // Delete episodes of the show from database
        $sql = 'DELETE FROM Episodes WHERE show_id = ?';

        // Create prepared statement
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql))
        {
            header("location:shows.php?error=true");
        }
        else
        {
            // Binding parameters
            mysqli_stmt_bind_param($stmt, 'i', $show_id);
            // Executing prepared statement
            mysqli_stmt_execute($stmt);            
        }

        // Delete show from database
        $sql = 'DELETE FROM Shows WHERE show_id = ?';

        // Create prepared statement
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql))
        {
            header("location:shows.php?error=true");
        }
        else
        {
            // Binding parameters
            mysqli_stmt_bind_param($stmt, 'i', $show_id);
            // Executing prepared statement
            mysqli_stmt_execute($stmt);
            // Redirect to index site
         
            header("location:shows.php?succdel=true");
        }
    }
	else		
	{
		echo "Érvénytelen vagy hiányzó azonosító.";
	}
?>
