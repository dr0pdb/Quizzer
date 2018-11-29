<?php

require_once('../classes/db/Database.php');

class User {

    public static function isUserAdmin($id) {
        $query = "SELECT _id FROM admin WHERE user_id = :id";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0)
            return $stmt->fetchColumn();
        else
            return 0;
    }

    public static function isUserStudent($id) {
        $query = "SELECT _id FROM student WHERE user_id = :id";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0)
            return $stmt->fetchColumn();
        else
            return 0;
    }

    public static function isUserInstructor($id) {
        $query = "SELECT _id FROM instructor WHERE user_id = :id";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0)
            return $stmt->fetchColumn();
        else
            return 0;
    }

    public static function getUserInfo($id) {
        $query = "SELECT first_name, last_name FROM user WHERE _id = :id";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getUserDetails($id) {
        $query = "SELECT *, date_format(join_time, '%D %b %Y, %I:%i %p') as join_date  FROM user WHERE user._id = :id";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private static function userExists($id, $method) {
        $query = "SELECT _id FROM user WHERE $method = :$method";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":$method", $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0)
            return $stmt->fetchColumn();
        else
            return 0;
    }

    public static function doesUserExist($id) {
        $_id = self::userExists($id, "username");
        return $_id>0?$_id:self::userExists($id, "email");
    }

    public static function verifyUser($id, $password) {
        $query = "SELECT first_name, password FROM user WHERE _id = :id";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return password_verify($password, $user['password'])?$user['first_name']:false;
        }

        return false;
    }

    private static function insertUser($userArray) {
        $fields = ['first_name', 'last_name', 'email', 'username', 'password', 'ph_no', 'gender'];

        $query = 'INSERT INTO user(' . implode(',', $fields) . ') VALUES(:' . implode(',:', $fields) . ')';

        $db = Database::getInstance()->getDb();
        $stmt = $db->prepare($query);

        $prepared_array = array();
        foreach ($fields as $field) {
            $prepared_array[':'.$field] = @$userArray[$field];
        }
        $prepared_array[':password'] = password_hash($userArray['password'], PASSWORD_DEFAULT);
        
        $stmt->execute($prepared_array);
        $id = $db->lastInsertId();

        return $id;
    }

    public static function insertStudent($studentArray) {
        $fields = ['roll_number', 'user_id'];

        $query = 'INSERT INTO student(' . implode(',', $fields) . ') VALUES(:' . implode(',:', $fields) . ')';

        $db = Database::getInstance()->getDb();
        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $prepared_array = array();
        foreach ($fields as $field) {
            $prepared_array[':'.$field] = @$studentArray[$field];
        }

        try {
            $db->beginTransaction();

            $userId = self::insertUser($studentArray);
            $prepared_array['user_id'] = $userId;

            $stmt->execute($prepared_array);

            $id = $db->lastInsertId();
            $db->commit();
        } catch (PDOException $ex) {
            $db->rollBack();
            return $ex->getMessage();
        }

        return $id;
    }

    public static function insertInstructor($instructorArray) {
        $fields = ['user_id'];

        $query = 'INSERT INTO instructor(' . implode(',', $fields) . ') VALUES(:' . implode(',:', $fields) . ')';

        $db = Database::getInstance()->getDb();
        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $prepared_array = array();
        foreach ($fields as $field) {
            $prepared_array[':'.$field] = @$instructorArray[$field];
        }

        try {
            $db->beginTransaction();

            $userId = self::insertUser($instructorArray);
            $prepared_array['user_id'] = $userId;

            $stmt->execute($prepared_array);

            $id = $db->lastInsertId();
            $db->commit();
        } catch (PDOException $ex) {
            $db->rollBack();
            return $ex->getMessage();
        }

        return $id;
    }

}