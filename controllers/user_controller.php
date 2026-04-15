<?php
function processAddUser($conn, &$message, &$messageType) {
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $email = trim($_POST['email'] ?? '');
    $contact_no = trim($_POST['contact_no'] ?? '');
    $color_code = $_POST['color_code'] ?? '#4285F4';
    
    // Check if username already exists
    $check_stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $message = "Username already exists!";
        $messageType = "danger";
        $check_stmt->close();
        return false;
    }
    $check_stmt->close();
    
    // Check if email already exists (only if email is not empty)
    if (!empty($email)) {
        $check_email_stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $check_email_stmt->bind_param("s", $email);
        $check_email_stmt->execute();
        $check_email_result = $check_email_stmt->get_result();
        
        if ($check_email_result->num_rows > 0) {
            $message = "Email address already exists!";
            $messageType = "danger";
            $check_email_stmt->close();
            return false;
        }
        $check_email_stmt->close();
    }
    
    // Set email to NULL if empty to avoid unique constraint violation
    $email_to_insert = !empty($email) ? $email : null;
    $contact_no_to_insert = !empty($contact_no) ? $contact_no : null;
    
    $stmt = $conn->prepare("INSERT INTO users (full_name, username, password_hash, role, email, contact_no, color_code) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $full_name, $username, $password, $role, $email_to_insert, $contact_no_to_insert, $color_code);
    
    if ($stmt->execute()) {
        $message = "User added successfully!";
        $messageType = "success";
        $stmt->close();
        return true;
    } else {
        $message = "Error adding user: " . $stmt->error;
        $messageType = "danger";
        $stmt->close();
        return false;
    }
}

function processEditUser($conn, &$message, &$messageType) {
    $user_id = $_POST['user_id'];
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $role = $_POST['role'];
    $email = trim($_POST['email'] ?? '');
    $contact_no = trim($_POST['contact_no'] ?? '');
    $color_code = $_POST['color_code'] ?? '#4285F4';
    
    // Check if username already exists for another user
    $check_stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? AND user_id != ?");
    $check_stmt->bind_param("si", $username, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $message = "Username already exists!";
        $messageType = "danger";
        $check_stmt->close();
        return false;
    }
    $check_stmt->close();
    
    // Check if email already exists for another user (only if email is not empty)
    if (!empty($email)) {
        $check_email_stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
        $check_email_stmt->bind_param("si", $email, $user_id);
        $check_email_stmt->execute();
        $check_email_result = $check_email_stmt->get_result();
        
        if ($check_email_result->num_rows > 0) {
            $message = "Email address already exists!";
            $messageType = "danger";
            $check_email_stmt->close();
            return false;
        }
        $check_email_stmt->close();
    }
    
    // Set email and contact_no to NULL if empty to avoid unique constraint violation
    $email_to_update = !empty($email) ? $email : null;
    $contact_no_to_update = !empty($contact_no) ? $contact_no : null;
    
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, username = ?, password_hash = ?, role = ?, email = ?, contact_no = ?, color_code = ? WHERE user_id = ?");
        $stmt->bind_param("sssssssi", $full_name, $username, $password, $role, $email_to_update, $contact_no_to_update, $color_code, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, username = ?, role = ?, email = ?, contact_no = ?, color_code = ? WHERE user_id = ?");
        $stmt->bind_param("ssssssi", $full_name, $username, $role, $email_to_update, $contact_no_to_update, $color_code, $user_id);
    }
    
    if ($stmt->execute()) {
        $message = "User updated successfully!";
        $messageType = "success";
        $stmt->close();
        return true;
    } else {
        $message = "Error updating user: " . $stmt->error;
        $messageType = "danger";
        $stmt->close();
        return false;
    }
}

function processDeleteUser($conn, &$message, &$messageType, $current_user_id) {
    $user_id = $_GET['delete_user'] ?? 0;
    
    if ($user_id == $current_user_id) {
        $message = "You cannot delete your own account!";
        $messageType = "danger";
        return false;
    }
    
    // Check if user exists before deleting
    $check_stmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows == 0) {
        $message = "User not found!";
        $messageType = "danger";
        $check_stmt->close();
        return false;
    }
    $check_stmt->close();
    
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        $message = "User deleted successfully!";
        $messageType = "success";
        $stmt->close();
        return true;
    } else {
        $message = "Error deleting user: " . $stmt->error;
        $messageType = "danger";
        $stmt->close();
        return false;
    }
}

function getUsers($conn) {
    $result = $conn->query("SELECT user_id, full_name, username, email, contact_no, color_code, role, created_at FROM users ORDER BY created_at DESC");
    return $result;
}

// Additional helper function to get user by ID
function getUserById($conn, $user_id) {
    $stmt = $conn->prepare("SELECT user_id, full_name, username, email, contact_no, color_code, role, created_at FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}

// Additional helper function to check if email exists
function emailExists($conn, $email, $exclude_id = 0) {
    if (empty($email)) return false;
    
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
    $stmt->bind_param("si", $email, $exclude_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

// Additional helper function to check if username exists
function usernameExists($conn, $username, $exclude_id = 0) {
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? AND user_id != ?");
    $stmt->bind_param("si", $username, $exclude_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}
?>