<?php
class User
{
    // tìm tk bằng username
    public static function findUserByUsername($conn, $username)
    {
        $stmt = $conn->prepare("SELECT id, username, password, role, status FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        return null;
    }

    // ktra mail tồn tại
    public static function checkUserExists($conn, $username)
    {
        $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // ktra email  
    public static function checkEmailExists($conn, $email)
    {
        $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // tạo người dùng mới ( trang register)
    public static function createUser($conn, $username, $email, $hashed_password)
    {
        $role = 'user';
        $status = 'active';
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $hashed_password, $role, $status);
        if ($stmt->execute()) {
            return $conn->insert_id;
        }
        return false;
    }

    public static function getAllUsers($conn)
    {
        // lấy ds user thêm stt tự động
        $sql = "
            SELECT (@row_number := @row_number + 1) AS stt, u.*
            FROM users u, (SELECT @row_number := 0) r
            ORDER BY u.id ASC
        ";
        return $conn->query($sql);
    }

    // admin taoh user
    public static function createUserByAdmin($conn, $username, $email, $password, $role)
    {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password_hash, $role);
        return $stmt->execute();
    }

    // admin update user
    public static function updateUserByAdmin($conn, $id, $email, $phone, $role, $password = null)
    {
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET email=?, phone=?, role=?, password=? WHERE id=?");
            $stmt->bind_param("ssssi", $email, $phone, $role, $password_hash, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET email=?, phone=?, role=? WHERE id=?");
            $stmt->bind_param("sssi", $email, $phone, $role, $id);
        }
        return $stmt->execute();
    }

    // admin xóa user
    public static function deleteUser($conn, $id)
    {
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // admin mở/khóa user
    public static function toggleUserStatus($conn, $id, $currentStatus)
    {
        $newStatus = $currentStatus === 'active' ? 'inactive' : 'active';
        $stmt = $conn->prepare("UPDATE users SET status=? WHERE id=?");
        $stmt->bind_param("si", $newStatus, $id);
        return $stmt->execute();
    }

    // ktra mail tồn tại
    public static function checkEmailExistsForUpdate($conn, $email, $id)
    {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // ktra sđt tồn tại
    public static function checkPhoneExistsForUpdate($conn, $phone, $id)
    {
        if (empty($phone))
            return false;
        $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ? AND id != ?");
        $stmt->bind_param("si", $phone, $id);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // Lấy thông tin user theo ID (cho trang profile)
    public static function getUserById($conn, $id)
    {
        // Sử dụng COALESCE để fallback về username nếu fullname chưa có

        $stmt = $conn->prepare("SELECT id, username, email, COALESCE(phone, '') as phone, COALESCE(fullname, username) as fullname FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Đảm bảo phone luôn là string, không phải NULL
            $user['phone'] = isset($user['phone']) ? (string)$user['phone'] : '';

            // Log giá trị phone được lấy
            error_log("GetUserById - Phone value: " . var_export($user['phone'], true));

            $stmt->close();
            return $user;
        }
        $stmt->close();
        return null;
    }

    // Cập nhật thông tin cá nhân (fullname, email, phone)
    public static function updateProfile($conn, $id, $fullname, $email, $phone)
    {
        // trim và giữ nguyên 
        $phone = trim($phone ?? '');

        // Log giá trị phone
        error_log("UpdateProfile - Phone value: " . var_export($phone, true));

        $stmt = $conn->prepare("UPDATE users SET fullname=?, email=?, phone=? WHERE id=?");
        $stmt->bind_param("sssi", $fullname, $email, $phone, $id);
        $result = $stmt->execute();

        // Kiểm tra lỗi nếu có
        if (!$result) {
            error_log("Cập Nhật thất bại: " . $stmt->error);
        } else {

            error_log("Cập nhật thành công: " . $id);
        }

        $stmt->close();
        return $result;
    }

    // Đổi mật khẩu
    public static function changePassword($conn, $id, $oldPassword, $newPassword)
    {
        // lấy mật khẩu cũ (đã hash) từ CSDL
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return "Người dùng không tồn tại.";
        }

        $user = $result->fetch_assoc();
        $currentHash = $user['password'];
        $stmt->close();

        // Xác thực mật khẩu cũ
        if (!password_verify($oldPassword, $currentHash)) {
            // Nếu mật khẩu cũ không khớp, trả về chuỗi lỗi
            return "Mật khẩu cũ không chính xác!";
        }

        // Hash mật khẩu mới
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);

        // Cập nhật CSDL
        $stmt_update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt_update->bind_param("si", $newHash, $id);

        if ($stmt_update->execute()) {
            $stmt_update->close();
            return true; // Thành công
        } else {
            $stmt_update->close();
            return "Lỗi khi cập nhật mật khẩu.";
        }
    }

    // Lấy mật khẩu hiện tại để verify
    public static function getPasswordById($conn, $id)
    {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            return $row['password'];
        }
        return null;
    }
}
