<?php
    // Manage sessions and session data
    $request_method = $_SERVER["REQUEST_METHOD"];

    function checkIfLoggedIn() {
        // Initialize the session
		session_start();
		// Define response
		$response = array();
		if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
			$response["loggedIn"] = $_SESSION["loggedIn"];
		} else {
            $response["loggedIn"] = false;
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function logout() {
        // Initialize the session
        session_start();
        // Unset all of the session variables
        $_SESSION = array();
        // Destroy the session.
        session_destroy();

        // Define response
        $response = array("loggedIn" => false);

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function getSessionData() {
        // Initialize the session
        session_start();
        $response = array();

        if(isset($_SESSION["loggedIn"]) && ($_SESSION["loggedIn"] === true)) {
            $url = "http://job-board/api/users.php?id=" . $_SESSION["id"];
            $result = file_get_contents($url);
            $response = json_decode($result);
            $response['status'] = 1;
            $response['status_message'] = "User data retrieved successfully.";
        } else {
            $response['status'] = 0;
            $response['status_message'] = "Error : user not logged in.";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    switch($request_method) {
        case 'GET':
            getSessionData();
            break;
        case 'POST':
            if($_POST["callType"] == "logout") {
				logout();
			} else {
                checkIfLoggedIn();
			}
            break;
    }
?>