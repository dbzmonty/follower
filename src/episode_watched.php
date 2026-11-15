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
        $is_watched = (bool)$_POST['is_watched'];
        $is_watched = !$is_watched;

        // Update episode in database
        $sql = 'UPDATE Episodes SET is_watched = ?, updated_at = NOW() WHERE id = ?';

        // Create prepared statement
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql))
        {
            header("location:shows.php?error=true");
        }
        else
        {
            // Binding parameters
            mysqli_stmt_bind_param($stmt, 'ii', $is_watched, $id);
            // Executing prepared statement
            mysqli_stmt_execute($stmt);         
        }

        if ($is_watched)
        {
            // Update show in database
            $sql = 'UPDATE Shows SET last_ep_watched = NOW() WHERE show_id = ?';

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
        }

        header("location:episodes.php?show_id=" . $show_id);
    }
	else		
	{
		echo "Érvénytelen vagy hiányzó azonosító.";
	}
?>