<?php
// session_start();

// include('../config/db-con.php');

// if(isset($_POST['login_btn']))
// {
//     // validate this input
//     $username = $_POST['username'];
//     $password = $_POST['password'];

//     $query = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
//     $result = mysqli_query($conn, $query);

//     if ($result && mysqli_num_rows($result) > 0) {
//         $user = mysqli_fetch_assoc($result);
//         if (password_verify($password, $user['password'])) {
//             $_SESSION['username'] = $username;
//             $_SESSION['user_id'] = $user['user_id'];

//             // if ($user['role'] == 2) { 
//             //     header("Location: ../cashier/index.php");
//             // } else if ($user['role'] == 3) { 
//             //     header("Location: ../admin/index.php");
//             // } else {
//             //     header("Location: ../cashier/index.php");
//             // }
//             switch($user['role']) {
//                 case 3:
//                     header("Location: ../admin/index.php");
//                     break;
//                 default:
//                     header("Location: ../cashier/index.php");
//                     break;
//             }
//             exit();
//         } else {
//             $_SESSION['message'] = "Incorrect password. Please try again.";
//             header("Location: ../login.php");
//             exit();
//         }
//     } else {
//         $_SESSION['message'] = "User not found. Please try again.";
//         header("Location: ../login.php");
//         exit();
//     }
// } else {
//    header("Location: ../login.php");
//    exit();
// }

session_start();

require_once('../config/db-con.php');

// Helper: redirect with session message
function redirect_with_message($message, $location = '../login.php') {
    $_SESSION['message'] = $message;
    header("Location: $location");
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_btn'])) {
    // Basic validation
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        redirect_with_message("Username and password are required.");
    }

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Set session data
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            switch ((int)$user['role']) {
                case 3: // Admin
                    header("Location: ../admin/index.php");
                    break;
                case 2: // Cashier
                default:
                    header("Location: ../cashier/index.php");
                    break;
            }
            exit();
        } else {
            redirect_with_message("Incorrect password. Please try again.");
        }
    } else {
        redirect_with_message("User not found. Please try again.");
    }
} else {
    header("Location: ../login.php");
    exit();
}

?>
