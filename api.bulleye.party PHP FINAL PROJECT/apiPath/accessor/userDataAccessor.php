<?php
/*
PHP SQL Median, all the queries for:
    Teams 
    Players
    Games
    and Matchups are stored in here for reference in API.php
*/

require_once dirname(__DIR__, 1) . '/entities/user.php';

class UserDataAccessor
{
    
    private $getUserByIDString = "select * from user where userEmail = :Email";
    private $postUserString = "insert into user values (:Email, :Password, :Permission)";
    private $putUserString = "update user set userEmail = :Email, userPassword = :Password, userPermission = :Permission where userEmail = :Email";
    private $getUserByIDStatement = null;
    private $postUserStatement = null;
    private $putUserStatement = null;



    /**
     * Creates a new instance of the accessor with the supplied database connection.
     * 
     * @param PDO $conn - a database connection
     */
    public function __construct($conn)
    {
        if (is_null($conn)) {
            throw new Exception("no connection");
        }

        $this->getUserByIDStatement = $conn->prepare($this->getUserByIDString);
        if(is_null($this->getUserByIDStatement)){
            throw new Exception("bad statement: '" . $this->getUserByIDString . "'");
        }

        $this->postUserStatement = $conn->prepare($this->postUserString);
        if(is_null($this->postUserStatement)){
            throw new Exception("bad statement: '" . $this->postUserString . "'");
        }

        $this->putUserStatement = $conn->prepare($this->putUserString);
        if(is_null($this->putUserStatement)){
            throw new Exception("bad statement: '" . $this->putUserString . "'");
        }
    }
    /*
        START OF GET BY ID SERIES
    */
    public function getUserByID($email){
        $result = null;
        try{
            $this->getUserByIDStatement->bindParam(":Email", $email);
            $this->getUserByIDStatement->execute();
            $dbresults = $this->getUserByIDStatement->fetch(PDO::FETCH_ASSOC);
            
            if($dbresults){
                $userEmail = $dbresults["userEmail"];
                $userPassword = $dbresults["userPassword"];
                $userPermission = $dbresults["userPermission"];
                $object = new User($userEmail, $userPassword, $userPermission);
                $result = $object;
            }
        }
        catch(Exception $e) {
            $result = null;
            throw new Exception($e);
        } finally {
            if(!is_null($this->getUserByIDStatement)){
                $this->getUserByIDStatement->closeCursor();
            }
        }
        return $result;
        
    }
    /*
        END OF GET BY ID SERIES
        START OF EXISTS SERIES
    */
    public function userExists($item){
        return $this->getUserByID($item->getUserEmail()) !== null;
    }
    /*
        END OF EXISTS SERIES
        START OF POST SERIES
    */
    public function postUser($item){
        if($this->userExists($item)){
            return false;
        }
        $success = false;
        $userEmail = $item->getUserEmail();
        $userPassword = $item->getUserPassword();
        $userPermissions = $item->getUserPermission();

        try{
            $this->postUserStatement->bindParam(":Email", $userEmail);
            $this->postUserStatement->bindParam(":Password", $userPassword);
            $this->postUserStatement->bindParam(":Permission", $userPermissions);

            $success = $this->postUserStatement->execute();
            $success = $this->postUserStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
            throw new Exception($e);
        } finally {
            if (!is_null($this->postUserStatement)) {
                $this->postUserStatement->closeCursor();
            }
        }
        return $success;
    }
    /*
        END OF POST SERIES
        START OF PUT SERIES
    */
    public function putUser($item){
        if(!$this->userExists($item)){
            return false;
        }
        $success = false;
        $userEmail = $item->getUserEmail();
        $userPassword = $item->getUserPassword();
        $userPermissions = $item->getUserPermission();

        try{
            $this->putUserStatement->bindParam(":Email", $userEmail);
            $this->putUserStatement->bindParam(":Password", $userPassword);
            $this->putUserStatement->bindParam(":Permission", $userPermissions);

            $success = $this->putUserStatement->execute();
            $success = $this->putUserStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
            throw new Exception($e);
        } finally {
            if (!is_null($this->putUserStatement)) {
                $this->putUserStatement->closeCursor();
            }
        }
        return $success;
    }
    /*
    END OF PUT SERIES
    START OF DELETE SERIES
    */
    
}
?>