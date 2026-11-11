<?php
    require_once('./includes/header.php');
      
    if (!isset($_SESSION['username']))
    {   
        header("location:login.php");
    }    
?>

<div class="container">

    <!-- Add show code -->
    <div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLongTitle">Add show</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <form action="show_add.php" method="POST">
                    <div class="modal-body">
                        						
						<div class="mb-3">
							<label for="show_id" class="form-label">TheTvDB.com Show ID:</label>
							<input type="number" class="form-control" id="show_id" name="show_id" required />
						</div>
						
						<div class="mb-3">
							<label for="show_name" class="form-label">Show name:</label>
							<input type="text" class="form-control" id="show_name" name="show_name" maxlength="250" required />
						</div>
						
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Shows code -->
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

        $sql = 'SELECT * FROM Shows ORDER BY show_name';
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql))
        {
            header("location:index.php?error=true");
        }
        else
        {
            // Binding parameters
            //mysqli_stmt_bind_param($stmt, 's', $UserID);
            
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            
            while ($row = mysqli_fetch_assoc($result))
            {
                $show_id = (int)$row['show_id']; 
				$show_name = htmlspecialchars($row['show_name'], ENT_QUOTES, 'UTF-8');
				//$last_ep_watched = (int)$row['last_ep_watched']; // episode_id-je az adott résznek

                // Show's details
                echo '
                <div class="col-13 mx-auto"> 
                    <div class="card">
                        <h4 class="card-header">                            
							
							<a href="episodes.php?id=' . $show_id . '" class="text-decoration-none text-dark">' . $show_name . '</a>							
                            
							<div class="btn-group btn-group-sm float-right" role="group" aria-label="First group">
                                
								<a href="show_edit.php?id=' . $show_id . '" class="btn btn-info" title="Módosítás">M</a>								
								
								<form action="show_delete.php" method="POST" class="d-inline" onsubmit="return confirm(\'Biztosan törölni szeretnéd ezt a sorozatot?\');">
									<input type="hidden" name="id" value="' . $show_id . '">
									<button type="submit" class="btn btn-warning" title="Törlés">X</button>
								</form>
                            
							</div>
                        </h4>
                    </div>
                </div>
                ';
            }
        }
    ?>

</div>

<?php require_once('./includes/footer.php'); ?>