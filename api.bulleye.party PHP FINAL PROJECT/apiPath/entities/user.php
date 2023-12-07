<?php
class User implements JsonSerializable{
    /**
     * @var userEmail contains the Email
     */
    private $userEmail;
    /**
     * @var userPassword contains the Password (HASHED);
     */
    private $userPassword;
    /**
     * @var userPermission contains the Permission level
     */
    private $userPermission;
    /**
     * Constructor for the User class
     * @param userEmail
     * @param userPassword (HASHED)
     * @param userPermission
     */
    public function __construct($userEmail, $userPassword, $userPermission){
        if(isset($userEmail)){
            $this->userEmail = $userEmail;
        }else{
            throw new Exception("Not a valid Email");
        }
        if(isset($userPassword)){
            $this->userPassword = $userPassword;
        }else{
            throw new Exception("Not a valid password");
        }
        if($this->isValidPermissionLevel($userPermission)){
            $this->userPermission = $userPermission;
        } else {
            throw new Exception("Not a valid permission");
        }
    }
    /**
     * Get the Users Email
     * @return userEmail
     */
    public function getUserEmail(){
        return $this->userEmail;
    }
    /**
     * Get the Users Password (HASHED)
     * @return userPassword
     */
    public function getUserPassword(){
        return $this->userPassword;
    }
    /**
     * Get the Users Permission
     * @return userPermission
     */
    public function getUserPermission(){
        return $this->userPermission;
    }

    public function comparePasstoHash($password){
        return password_verify($password, $this->userPassword);
    }

    /**
     * Verifies if the permission level is one of the 4 for the constructor
     * @param pl <- the permission level to be checked
     * @return true|false
     */
    private function isValidPermissionLevel($pl){
        $permissionLevels = [
            1=>"system",
            2=>"admin",
            3=>"scorekeep",
            4=>"guest"
        ];

        foreach($permissionLevels as $p){
            if($p == $pl){
                return true;
            }
        }
        return false;
    }

    /**
     * The implementation of json JsonSerializable
     * @return get_object_vars($this)
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
?>