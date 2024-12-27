<?php
require_once '../config/db.php';

class Auth extends DbConnection {

    public function register($username, $password, $name, $phone, $email, $role = 'Member') {
        try {
            $this->connection->beginTransaction();

            $role = ($role === 'Admin') ? 'Admin' : 'Member';

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $sqlUser = "INSERT INTO Users (Username, Password, Role) VALUES (:username, :password, :role)";
            $stmtUser = $this->connection->prepare($sqlUser);
            $stmtUser->execute([
                ':username' => $username,
                ':password' => $hashedPassword,
                ':role' => $role
            ]);

            $userId = $this->connection->lastInsertId();

            if ($role === 'Member') {
                $sqlMember = "INSERT INTO Members (MemberID, Name, Phone, Email) VALUES (:id, :name, :phone, :email)";
                $stmtMember = $this->connection->prepare($sqlMember);
                $stmtMember->execute([
                    ':id' => $userId,
                    ':name' => $name,
                    ':phone' => $phone,
                    ':email' => $email
                ]);
            }

            $this->connection->commit();
            return $userId;
        } catch (Exception $e) {
            $this->connection->rollBack();
            throw new Exception("Registration failed. Please try again.");
        }
    }

    public function login($username, $password) {
        try {
            $sql = "SELECT UserID, Username, Password, Role FROM Users WHERE Username = :username";
            $stmt = $this->connection->prepare($sql);
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


class User {
    private $pdo;
    protected $id;
    protected $username;
    protected $password;
    protected $role;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function setUser($id, $username, $password, $role) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
    }

    public function getUserId() {
        return $this->id;
    }
    public function getUsername() {
        return $this->username;
    }
    public function getUserPassword() {
        return $this->password;
    }
    public function getUserRole() {
        return $this->role;
    }

    // Reservation Methods
    public function getAllReservations() {
        $sql = "SELECT r.ResID, r.ResDate, r.Status, u.Name AS MemberName, a.Name AS ActivityName 
                FROM Reservations r
                JOIN Members u ON r.MemberID = u.MemberID
                JOIN Activities a ON r.ActivityID = a.ActivityID";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateReservationStatus($reservationId, $action) {
        $status = ($action === 'accept') ? 'Confirmed' : 'Cancelled';
        $sql = "UPDATE Reservations SET status = :status WHERE ResID = :reservation_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':status' => $status,
            ':reservation_id' => $reservationId,
        ]);
    }

    // Activity Methods
    public function createActivity($activityName, $activityDescription, $activityImg) {
        $sql = "INSERT INTO Activities (PhotoURL, Name, Description) VALUES (:img, :name, :description)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':img' => $activityImg,
            ':name' => $activityName,
            ':description' => $activityDescription,
        ]);
    }

    public function deleteActivity($activityId) {
        $sql = "DELETE FROM Activities WHERE ActivityID = :activity_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':activity_id' => $activityId]);
    }
}



class Member extends User {
    private $name;
    private $phone;
    private $email;

    public function setMember($id, $username, $password, $name, $phone, $email) {
        parent::setUser($id, $username, $password, 'Member');
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
    }    

    public function getName() {
        return $this->name;
    }
    public function getPhone() {
        return $this->phone;
    }
    public function getEmail() {
        return $this->email;
    }

    public function memberInfo() {
        return "Member: {$this->name}, Email: {$this->email}, Phone: {$this->phone}";
    }
}



class Activity {
    private $id;
    private $name;
    private $image;
    private $description;

    public function __construct($id, $name, $image, $description) {
        $this->id = $id;
        $this->name = $name;
        $this->image = $image;
        $this->description = $description;
    }

    public function getId() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
    public function getImg() {
        return $this->image;
    }
    public function getDescription() {
        return $this->description;
    }

    public function setName($name) {
        $this->name = $name;
    }
    public function setImg() {
        $this->image = $image;
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

?>