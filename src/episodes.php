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
	else		
	{
		echo "Érvénytelen vagy hiányzó azonosító.";
	}
?>

<div class="container">

    <!-- Add note code -->
    <div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLongTitle">Add episode</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <form action="episode_add.php" method="POST">
                    <div class="modal-body">
                        
						<div class="d-none">
							<input type=hidden" id="show_id" name="show_id" value="<?php echo $show_id ?>" />
							<input type=hidden" id="episode_id" name="episode_id" value="-1" />
						</div>	
						
						<div class="mb-3">
							<label for="season_number" class="form-label">Season:</label>
							<input type="number" class="form-control" id="season_number" name="season_number" required />
						</div>
						
						<div class="mb-3">
							<label for="episode_number" class="form-label">Episode:</label>
							<input type="number" class="form-control" id="episode_number" name="episode_number" required />
						</div>
						
						<div class="mb-3">
							<label for="episode_name" class="form-label">Episode name:</label>
							<input type="text" class="form-control" id="episode_name" name="episode_name" maxlength="250" required />
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

		if (isset($_GET["succedit"]))
        {		
            if ($_GET["succedit"] == "true")
            {
            print '
                <p>
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Successful edit!</strong>
                    </div>   
                </p>';
            }
        }

        if (isset($_GET["succdel"]))
        {		
            if ($_GET["succdel"] == "true")
            {
            print '
                <p>
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Successful delete!</strong>
                    </div>   
                </p>';
            }
        }

		$sql = "SELECT * FROM Episodes WHERE show_id=? AND season_number > 0 ORDER BY season_number, episode_number";		
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
				$id = (int)$row['id'];
				$season_number = (int)$row['season_number'];
				$episode_number = (int)$row['episode_number'];
				$episode_name = htmlspecialchars($row['episode_name'], ENT_QUOTES, 'UTF-8');
				//$first_aired = (date)$row['first_aired'];
				$is_watched = (bool)$row['is_watched'];
				
				$btn_class = $is_watched ? 'btn-success' : 'btn-info';
				$btn_label = $is_watched ? 'Seen' : 'Watch';
				
				echo '
				<div class="col-13 mx-auto"> 
					<div class="card">
						<h4 class="card-header">                            
														
							S'; if ($season_number < 10) echo '0' . $season_number; else echo $season_number; 
							echo 'E'; if ($episode_number < 10) echo '0' . $episode_number; else echo $episode_number; 
							echo ': ';echo $episode_name; echo'							
							
							<div class="btn-group btn-group-sm float-right" role="group" aria-label="First group">
                            
								<form action="episode_delete.php" method="POST" class="d-inline" onsubmit="return confirm(\'Do you really want to delete this episode?\');">
									<input type="hidden" id="id" name="id" value="' . $id . '" />
									<input type="hidden" id="show_id" name="show_id" value="' . $show_id . '" />
									<button type="submit" name="submit" class="btn btn-warning" title="Delete">X</button>
								</form>
								
								<a href="episode_edit.php?id=' . $id . '&show_id=' . $show_id . '" class="btn btn-primary d-inline-flex align-items-center" title="Módosítás">M</a>								
																
								<form action="episode_watched.php" method="POST" class="d-inline watch-form">
									<input type="hidden" id="id" name="id" value="' . $id . '" />
									<input type="hidden" id="show_id" name="show_id" value="' . $show_id . '" />
									<input type="hidden" id="is_watched" name="is_watched" value="' . (int)$is_watched . '" />
									<button type="submit" name="submit" class="btn ' . $btn_class . '" title="' . $btn_label . '">' . $btn_label . '</button>
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

<script>
document.addEventListener('submit', async function (e) {
    if (!e.target.classList.contains('watch-form')) return;

    e.preventDefault();

    const form = e.target;
    const button = form.querySelector('button[type="submit"]');
    const watchedInput = form.querySelector('input[name="is_watched"]');
    const formData = new FormData(form);

    button.disabled = true;

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData
        });

        const rawText = await response.text();
        let data;

        try {
            data = JSON.parse(rawText);
        } catch (parseErr) {
            alert('PHP hibaüzenet a szervertől:\n\n' + rawText);
            return;
        }

        if (data.success) {
            watchedInput.value = data.is_watched;

            if (data.is_watched === 1) {
                button.classList.remove('btn-info');
                button.classList.add('btn-success');
                button.textContent = 'Seen';
                button.title = 'Seen';
            } else {
                button.classList.remove('btn-success');
                button.classList.add('btn-info');
                button.textContent = 'Watch';
                button.title = 'Watch';
            }
        } else {
            alert(data.message || 'Hiba történt.');
        }
    } catch (err) {
        console.error(err);
        alert('Hálózati hiba történt.');
    } finally {
        button.disabled = false;
    }
});
</script>

<?php require_once('./includes/footer.php'); ?>