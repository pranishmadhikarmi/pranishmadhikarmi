<?php

include('connection.php');
session_start();

# Check if admin exists, if not, create one
$check_admin_query = "SELECT * FROM `registered_users` WHERE `username`='admin'";
$result = mysqli_query($con, $check_admin_query);

if ($result && mysqli_num_rows($result) == 0) {
    $admin_username = "admin";
    $admin_email = "admin@gmail.com";
    $admin_password = password_hash("admin123", PASSWORD_BCRYPT);

    $insert_admin_query = "INSERT INTO `registered_users` (`full_name`, `username`, `email`, `password`, `role`) 
                           VALUES ('Admin', '$admin_username', '$admin_email', '$admin_password', 'admin')";
    mysqli_query($con, $insert_admin_query);
}


# For login
if (isset($_POST['login'])) {
    $query = "SELECT * FROM `registered_users` WHERE `email`='{$_POST['email_username']}' OR `username`='{$_POST['email_username']}'";
    $result = mysqli_query($con, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $result_fetch = mysqli_fetch_assoc($result);
            if (password_verify($_POST['password'], $result_fetch['password'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $result_fetch['username'];
                $_SESSION['user_id'] = $result_fetch['id'];


                # Check if user is admin
                if ($result_fetch['username'] == 'admin') {
                    header("location:/Admin/admin.php"); 
                } else {
                    header("location: index.php"); 
                }
                exit();
            } else {
                echo "<script>
                    alert('Incorrect Password');
                    window.location.href='index.php';
                </script>";
            }
        } else {
            echo "<script>
                alert('Email or Username not registered');
                window.location.href='index.php';
            </script>";
        }
        
    }
}

# For registration
if (isset($_POST['register'])) {
    $user_exist_query = "SELECT * FROM `registered_users` WHERE `username`='{$_POST['username']}' OR `email`='{$_POST['email']}'";
    $result = mysqli_query($con, $user_exist_query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $result_fetch = mysqli_fetch_assoc($result);
            if ($result_fetch['username'] == $_POST['username']) {
                echo "<script>
                    alert('$result_fetch[username] - Username already taken.');
                    window.location.href='index.php';
                </script>";
            } else {
                echo "<script>
                    alert('$result_fetch[email] - Email already taken.');
                    window.location.href='index.php';
                </script>";
            }
        } else {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $role = 'user';
            $check_users = mysqli_query($con, "SELECT * FROM `registered_users`");

            if (mysqli_num_rows($check_users) == 0) {
                $role = 'admin'; 
            }

            $query = "INSERT INTO `registered_users`(`full_name`, `username`, `email`, `password`, `role`) 
                      VALUES ('{$_POST['fullname']}', '{$_POST['username']}', '{$_POST['email']}', '$password', '$role')";

            if (mysqli_query($con, $query)) {
                echo "<script>
                    alert('Registration Successful');
                    window.location.href='index.php';
                </script>";
            } else {
                echo "<script>
                    alert('Cannot Run Query');
                    window.location.href='index.php';
                </script>";
            }
        }
    } else {
        echo "<script>
            alert('Cannot Run Query');
            window.location.href='index.php';
        </script>";
    }
}
?>
