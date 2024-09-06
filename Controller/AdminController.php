<?php 

class AdminController extends Controller
{
    public function createAdmin($username, $pwdHash)
    {
        return $this->access->createAdmin($username, $pwdHash);
    }
    public function verifyCredentials($username, $password)
    {
        return $this->access->verifyCredentials($username, $password);
    }
    public function getAdmin($username)
    {
        return $this->access->getAdmin($username);
    }
}