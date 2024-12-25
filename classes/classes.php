<?php

class User {
    protected $id;
    protected $username;
    protected $password;
    protected $role;

    public function __construct($id, $username, $password, $role) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
    }

    public function getId() {
        return $this->id;
    }
    public function getUsername() {
        return $this->username;
    }
    public function getPassword() {
        return $this->password;
    }
    public function getRole() {
        return $this->role;
    }

    public function setUsername($username) {
        $this->username = $username;
    }
    public function setPassword($password) {
        $this->password = $password;
    }
    public function setRole($role) {
        $this->role = $role;
    }
}


class Member extends User {
    private $firstname;
    private $lastname;
    private $phone;
    private $email;

    public function __construct($id, $username, $password, $firstname, $lastname, $phone, $email) {
        parent::__construct($id, $username, $password, 'Member');
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->phone = $phone;
        $this->email = $email;
    }    

    public function getFirstName() {
        return $this->firstname;
    }
    public function getLastName() {
        return $this->lastname;
    }
    public function getPhone() {
        return $this->phone;
    }
    public function getEmail() {
        return $this->email;
    }


    public function setFirstName($firstname) {
        $this->firstname = $firstname;
    }
    public function setLastName($lastname) {
        $this->lastname = $lastname;
    }
    public function setPhone($phone) {
        $this->phone = $phone;
    }
    public function setEmail($email) {
        $this->email = $email;
    }

    public function afficherInformations() {
        return "Member: {$this->firstname} {$this->lastname}, Email: {$this->email}, Phone: {$this->phone}";
    }
}



class Activity {
    private $id;
    private $name;
    private $description;

    public function __construct($id, $name, $description) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function getId() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
    public function getDescription() {
        return $this->description;
    }

    public function setName($name) {
        $this->name = $name;
    }
    public function setDescription($description) {
        $this->description = $description;
    }
}


class Reservation {
    private $id;
    private $memberId;
    private $activityId;
    private $status;
    private $reservationDate;

    public function __construct($id, $memberId, $activityId, $status, $reservationDate) {
        $this->id = $id;
        $this->memberId = $memberId;
        $this->activityId = $activityId;
        $this->status = $status;
        $this->reservationDate = $reservationDate;
    }

    public function getId() {
        return $this->id;
    }
    public function getMemberId() {
        return $this->memberId;
    }
    public function getActivityId() {
        return $this->activityId;
    }
    public function getStatus() {
        return $this->status;
    }
    public function getReservationDate() {
        return $this->reservationDate;
    }

    public function setMemberId($memberId) {
        $this->memberId = $memberId;
    }
    public function setActivityId($activityId) {
        $this->activityId = $activityId;
    }
    public function setStatus($status) {
        $this->status = $status;
    }
    public function setReservationDate($reservationDate) {
        $this->reservationDate = $reservationDate;
    }
}



class Auth {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function register($username, $password, $firstname, $lastname, $phone, $email) {
        try {
            $this->db->beginTransaction();

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $sqlUser = "INSERT INTO Users (Username, Password, Role) VALUES (:username, :password, 'Member')";
            $stmtUser = $this->db->prepare($sqlUser);
            $stmtUser->execute([
                ':username' => $username,
                ':password' => $hashedPassword
            ]);

            $userId = $this->db->lastInsertId();

            $sqlMember = "INSERT INTO Members (MemberID, FirstName, LastName, Phone, Email) VALUES (:id, :firstname, :lastname, :phone, :email)";
            $stmtMember = $this->db->prepare($sqlMember);
            $stmtMember->execute([
                ':id' => $userId,
                ':firstname' => $firstname,
                ':lastname' => $lastname,
                ':phone' => $phone,
                ':email' => $email
            ]);

            $this->db->commit();
            return $userId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception("Registration failed. Please try again.");
        }
    }

    public function login($username, $password) {
        try {
            $sql = "SELECT * FROM Users WHERE Username = :username";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($password, $user['Password'])) {
                throw new Exception("Login failed. Please check your credentials.");
            }

            return [
                'id' => $user['UserID'],
                'username' => $user['Username'],
                'role' => $user['Role']
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }
}

?>