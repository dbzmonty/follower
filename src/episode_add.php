<?php
    require_once('./includes/header.php');
    
    if (!isset($_SESSION['username']))
    {   
        header("location:login.php");
    }    
    else if (isset($_POST["submit"]))
    {
        $show_id = $_POST['show_id'];
		$episode_id = $_POST['episode_id'];
        $episode_name = $_POST['episode_name'];
		$season_number = $_POST['season_number'];
        $episode_number = $_POST['episode_number'];
		
        // Query
        $sql = 'INSERT INTO Episodes (show_id, episode_id, episode_name, season_number, episode_number) VALUES(?, ?, ?, ?, ?)';

        // Create prepared statement
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql))
        {
            header("location:episodes.php?error=true");
        }
        else
        {
            // Binding parameters
            mysqli_stmt_bind_param($stmt, 'iisii', $show_id, $episode_id, $episode_name, $season_number, $episode_number);
            // Executing prepared statement
            mysqli_stmt_execute($stmt);
            // Redirect to index site
            header("location:episodes.php?id=" . $show_id);            
        }

        require_once('./includes/footer.php');
    }
    else
    {
        header("location:index.php");
    }
?>
