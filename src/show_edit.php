<?php
    require_once('./includes/header.php');
      
    if (!isset($_SESSION['username']))
    {   
        header("location:login.php");
    }    
	
	if (isset($_GET['show_id']) && is_numeric($_GET['show_id']))
	{
		$show_id = (int)$_GET['show_id'];
	}
    else if (isset($_POST["submit"]))
    {
        $show_id = (int)$_POST['show_id'];
        $show_name = $_POST['show_name'];

        // Query
        $sql = 'UPDATE Shows SET show_name = ?, updated_at = NOW() WHERE show_id = ?';

        // Create prepared statement
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql))
        {
            header("location:shows.php?error=true");
        }
        else
        {
            // Binding parameters
            mysqli_stmt_bind_param($stmt, 'si', $show_name, $show_id);
            // Executing prepared statement
            mysqli_stmt_execute($stmt);
            // Redirect to index site
         
            header("location:shows.php?succedit=true");
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

		$sql = "SELECT * FROM Shows WHERE show_id = ? LIMIT 1";		
		$stmt = mysqli_stmt_init($conn);
		
		if(!mysqli_stmt_prepare($stmt, $sql))
		{
			header("location:index.php?error=true");
		}
		else
		{
			mysqli_stmt_bind_param($stmt, "i", $show_id);
			mysqli_stmt_execute($stmt);
			
			$result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result))
            {    
                $show_name = htmlspecialchars($row['show_name'], ENT_QUOTES, 'UTF-8');
            }
            else
            {
                echo "Hiba! Nincs ilyen show_id!";
            }
        }
    ?>

    <form action="show_edit.php" method="POST">
        <div>
                  
            <div class="d-none">
                <input type="hidden" id="show_id" name="show_id" value="<?php echo $show_id ?>" />
            </div>	
            
            <div class="mb-3">
                <label for="show_name" class="form-label">Show name:</label>
                <input type="text" class="form-control" id="show_name" name="show_name" maxlength="250" value="<?php echo $show_name; ?>" required />
            </div>
            
            <div>            
                <button type="submit" name="submit" class="btn btn-primary">Save</button>
            </div>

        </div>        
    </form>       

</div>

<?php require_once('./includes/footer.php'); ?>