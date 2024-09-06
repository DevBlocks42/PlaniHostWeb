<?php

class AdminAccess extends Access
{
    public function createAdmin($userName, $pwdHash)
    {
        $sql = "INSERT INTO admins (username, pwd_hash) VALUES(?, ?);";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("ss", $userName, $pwdHash);
        $stmt->execute();
    }
    public function verifyCredentials($username, $password)
    {
        $sql = "SELECT pwd_hash FROM admins WHERE username = ?;";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0) 
        {
            while($row = $result->fetch_assoc())
            {
                return password_verify($password, $row['pwd_hash']);
            }
        }
        return false;
    }
    public function getAdmin($username)
    {
        $sql = "SELECT id FROM admins WHERE username = ?;";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $admin = new Admin($row['id'], $username);
                return $admin;
            }
        }
        return null;   
    }
}