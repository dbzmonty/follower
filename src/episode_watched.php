<?php
    session_start();
	require_once('./includes/db.php'); 
        
	header('Content-Type: application/json; charset=utf-8');
	
    if (!isset($_SESSION['username']))
    {   
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Nincs bejelentkezve.']);
        exit;
    }    
		
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['show_id'], $_POST['is_watched']))
    {
        $id = (int)$_POST['id'];
        $show_id = (int)$_POST['show_id'];
        $current_watched = (int)$_POST['is_watched'];
        $new_watched = $current_watched ? 0 : 1;

        // Update episode in database
        $sql = 'UPDATE Episodes SET is_watched = ?, updated_at = NOW() WHERE id = ?';

        // Create prepared statement
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql))
        {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Adatbázis hiba (Episodes).']);
            exit;
        }
        else
        {
            // Binding parameters
            mysqli_stmt_bind_param($stmt, 'ii', $new_watched, $id);
            // Executing prepared statement
            mysqli_stmt_execute($stmt);         
        }

        if ($new_watched === 1)
        {
            // Update show in database
            $sql = 'UPDATE Shows SET last_ep_watched = NOW() WHERE show_id = ?';

            // Create prepared statement
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $sql))
            {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Adatbázis hiba (Shows).']);
                exit;
            }
            else
            {
                // Binding parameters
                mysqli_stmt_bind_param($stmt, 'i', $show_id);
                // Executing prepared statement
                mysqli_stmt_execute($stmt);         
            }
        }

        echo json_encode([
            'success' => true,
            'is_watched' => $new_watched
        ]);
        exit;
    }
	else		
	{
		http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Érvénytelen vagy hiányzó azonosító.']);
        exit;
	}
?>