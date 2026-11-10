<?php
    require_once('./includes/header.php');
      
    if (!isset($_SESSION['username']))
    {   
        header("location:login.php");
    }    
?>

<div class="container">

    <!-- Add note code -->
    <div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLongTitle">Add note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <form action="add.php" method="POST">
                    <div class="modal-body">
                        <textarea class="form-control" id="inputNote" name="inputNote" rows="3" maxlength="255" required></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- List note(s) code -->
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

        // $UserID = $_SESSION['id'];
        // $UserName = $_SESSION['username'] ;

		if (isset($_GET['id']) && is_numeric($_GET['id']))
		{
			$show_id = (int)$_GET['id'];
			$sql = "SELECT * FROM Episodes WHERE show_id=?";
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

                while ($row = mysqli_fetch_assoc($result))
                {
                    $episode_id = (int)$row['episode_id'];
                    $season_number = (int)$row['season_number'];
                    $episode_number = (int)$row['episode_number'];
                    $episode_name = htmlspecialchars($row['episode_name'], ENT_QUOTES, 'UTF-8');
                    //$first_aired = (date)$row['first_aired'];
                    $is_watched = (bool)$row['is_watched'];
                    echo '
                    <div class="col-13 mx-auto"> 
                        <div class="card">
                            <h4 class="card-header">                            
                                                            
                                S'; if ($season_number < 10) echo '0' . $season_number; else echo $season_number; 
                                echo 'E'; if ($episode_number < 10) echo '0' . $episode_number; else echo $episode_number; 
                                echo ': ';echo $episode_name; echo'							
                                
                                <div class="btn-group btn-group-sm float-right" role="group" aria-label="First group">
                                    
                                    <a href="show_edit.php?id=' . $episode_id . '" class="btn btn-info" title="Módosítás">M</a>								
                                    
                                    <form action="show_delete.php" method="POST" class="d-inline" onsubmit="return confirm(\'Biztosan törölni szeretnéd ezt a sorozatot?\');">
                                        <input type="hidden" name="id" value="' . $episode_id . '">
                                        <button type="submit" class="btn btn-warning" title="Törlés">X</button>
                                    </form>
                                
                                </div>
                            </h4>
                        </div>
                    </div>
                    ';
                }
            }			
		}
		else		
		{
			echo "Érvénytelen vagy hiányzó azonosító.";
		}
		?>
</div>

<?php require_once('./includes/footer.php'); ?>