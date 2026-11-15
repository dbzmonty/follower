<?php
    require_once('./includes/header.php');
      
    if (!isset($_SESSION['username']))
    {   
        header("location:login.php");
    }    
	
	if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['show_id']) && is_numeric($_GET['show_id']))
	{
		$id = (int)$_GET['id'];
        $show_id = (int)$_GET['show_id'];
	}
    else if (isset($_POST["submit"]))
    {
        $id = (int)$_POST['id'];    
        $show_id = (int)$_POST['show_id'];                
        $season_number = (int)$_POST['season_number'];
        $episode_number = (int)$_POST['episode_number'];
        $episode_name = $_POST['episode_name'];

        // Query
        $sql = 'UPDATE Episodes SET season_number = ?, episode_number = ?, episode_name = ?, updated_at = NOW() WHERE id = ?';

        // Create prepared statement
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql))
        {
            header("location:shows.php?error=true");
        }
        else
        {
            // Binding parameters
            mysqli_stmt_bind_param($stmt, 'iisi', $season_number, $episode_number, $episode_name, $id);
            // Executing prepared statement
            mysqli_stmt_execute($stmt);
            // Redirect to index site
         
            header("location:episodes.php?show_id=" . $show_id . "&succedit=true");
        }
    }
	else		
	{
		echo "Érvénytelen vagy hiányzó azonosító.";
	}
?>

<div class="container">

    <?php
        // Database error
        if (isset($_GET["error"]))
        {		
            if ($_GET["error"] == "true")
            {
            echo '
                <p>
                    <div class="alert alert-danger">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Something is wrong with the database :(</strong>
                    </div>   
                </p>';
            }
        }

		$sql = "SELECT * FROM Episodes WHERE id = ? LIMIT 1";		
		$stmt = mysqli_stmt_init($conn);
		
		if(!mysqli_stmt_prepare($stmt, $sql))
		{
			header("location:index.php?error=true");
		}
		else
		{
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			
			$result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result))
            {    
                $season_number = (int)$row['season_number'];
                $episode_number = (int)$row['episode_number'];
                $episode_name = htmlspecialchars($row['episode_name'], ENT_QUOTES, 'UTF-8');
            }
            else
            {
                echo "Hiba! Nincs ilyen id!";
            }
        }
    ?>

    <form action="episode_edit.php" method="POST">
        <div>
                  
            <div class="d-none">
                <input type="hidden" id="id" name="id" value="<?php echo $id ?>" />
                <input type="hidden" id="show_id" name="show_id" value="<?php echo $show_id ?>" />
            </div>	
            
            <div class="mb-3">
                <label for="season_number" class="form-label">Season number:</label>
                <input type="number" class="form-control" id="season_number" name="season_number" min="1" value="<?php echo $season_number; ?>" required />
            </div>

            <div class="mb-3">
                <label for="episode_number" class="form-label">Episode number:</label>
                <input type="number" class="form-control" id="episode_number" name="episode_number" min="1" value="<?php echo $episode_number; ?>" required />
            </div>

            <div class="mb-3">
                <label for="episode_name" class="form-label">Episode name:</label>
                <input type="text" class="form-control" id="episode_name" name="episode_name" maxlength="250" value="<?php echo $episode_name; ?>" required />
            </div>

            <div>            
                <button type="submit" name="submit" class="btn btn-primary">Save</button>
            </div>

        </div>        
    </form>       

</div>

<?php require_once('./includes/footer.php'); ?>