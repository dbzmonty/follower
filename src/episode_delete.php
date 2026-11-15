<?php
    require_once('./includes/header.php');
      
    if (!isset($_SESSION['username']))
    {   
        header("location:login.php");
    }    
		
    if (isset($_POST["submit"]))
    {
        $id = (int)$_POST['id'];
        $show_id = (int)$_POST['show_id'];

        // Delete episode from database
        $sql = 'DELETE FROM Episodes WHERE id = ?';

        // Create prepared statement
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql))
        {
            header("location:shows.php?error=true");
        }
        else
        {
            // Binding parameters
            mysqli_stmt_bind_param($stmt, 'i', $id);
            // Executing prepared statement
            mysqli_stmt_execute($stmt);         
                        
            header("location:episodes.php?show_id=" . $show_id . "&succdel=true");
        }
    }
	else		
	{
		echo "Érvénytelen vagy hiányzó azonosító.";
	}
?>
