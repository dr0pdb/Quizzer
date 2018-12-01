<?php

require_once('../classes/db/Database.php');

class QuizService {

    public static function getQuizzes() {
        $query = "SELECT * FROM quiz";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getQuiz($id) {
        $query = "SELECT *  FROM quiz WHERE _id = :id ";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getQuizQuestions($id) {
        $query = "SELECT *  FROM question WHERE quiz_id = :id";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function removeQuiz($id) {
        $query = "DELETE from quiz WHERE `_id` = :id";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->execute();

    }

    public static function getQuizzesForUser($id, $isStudent) {
        $query = "SELECT * FROM quiz WHERE `instructor_id` = :id";
        if($isStudent) {
            $query = "SELECT * FROM quiz JOIN quiz_participant ON quiz._id = quiz_participant.quiz_id WHERE user_id = :id AND (quiz.end_time <= NOW() OR quiz_participant.locked = 1)";
        }

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getQuizzesForToday() {
        $query = $query = "SELECT * FROM quiz WHERE DATE(start_time) = CURDATE()";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getQuizzesForFuture() {
        $query = "SELECT * FROM quiz WHERE DATE(start_time) > CURDATE()";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function insertQuestions($questions, $quiz_id) {
        // insert the questions into the db.
        $fields = ['statement', 'option_one', 'option_two', 'option_three', 'option_four', 'answer', 'quiz_id'];

        $query = 'INSERT INTO question(' . implode(',', $fields) . ') VALUES(:' . implode(',:', $fields) . ')';
        $db = Database::getInstance()->getDb();
        $stmt = $db->prepare($query);

        foreach ($questions as $question) {
            $prepared_array = array();
            $question['quiz_id'] = $quiz_id;
            foreach ($fields as $field) {
                $prepared_array[':'.$field] = @$question[$field];
            }
        
            $stmt->execute($prepared_array);
        }

    }

    public static function insertParticipantResponse($response, $participant_id) {
        $fields = ['quiz_participant_id', 'question_number', 'response'];

        $query = 'INSERT INTO quiz_participant_response(' . implode(',', $fields) . ') VALUES(:' . implode(',:', $fields) . ')';
        $db = Database::getInstance()->getDb();
        $stmt = $db->prepare($query);

        $prepared_array = array();
        $response['quiz_participant_id'] = $participant_id;
        foreach ($fields as $field) {
            $prepared_array[':'.$field] = @$response[$field];
        }

        try {
            $db->beginTransaction();

            $stmt->execute($prepared_array);
            $id = $db->lastInsertId();

            $db->commit();
        } catch (PDOException $ex) {
            $db->rollBack();
            return $ex->getMessage();
        }

        return $id;
    }

    public static function updateParticipantResponses($responses, $participant_id) {
        $db = Database::getInstance()->getDb();
        $index = 1;

        foreach ($responses as $response) {
            $query = 'UPDATE quiz_participant_response SET response = :response WHERE quiz_participant_id = :quiz_participant_id AND question_number = :index';
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(":response", $responses[$index]);
            $stmt->bindParam(":quiz_participant_id", $participant_id);
            $stmt->bindParam(":index", $index);
        
            $stmt->execute();
            $index++;
        }
    }

    public static function getParticipantResponses($id) {
        $query = "SELECT * FROM quiz_participant_response WHERE quiz_participant_id = :id ";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getQuizParticipant($user_id, $quiz_id) {
        $query = "SELECT *  FROM quiz_participant WHERE user_id = :user_id AND quiz_id = :quiz_id ";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":quiz_id", $quiz_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getQuizParticipantWithId($id) {
        $query = "SELECT *  FROM quiz_participant WHERE _id = :id ";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function insertParticipant($participantArray) {
        $fields = ['quiz_id', 'user_id'];

        $query = 'INSERT INTO quiz_participant(' . implode(',', $fields) . ') VALUES(:' . implode(',:', $fields) . ')';

        $db = Database::getInstance()->getDb();
        $stmt = $db->prepare($query);

        $prepared_array = array();
        foreach ($fields as $field) {
            $prepared_array[':'.$field] = @$participantArray[$field];
        }

        try {
            $db->beginTransaction();

            $stmt->execute($prepared_array);
            $id = $db->lastInsertId();

            $db->commit();
        } catch (PDOException $ex) {
            $db->rollBack();
            return $ex->getMessage();
        }

        return $id;
    }

    public static function lockParticipantSubmissions($id) {
        $query = "UPDATE quiz_participant SET locked = 1 WHERE _id = :id";

        $db = Database::getInstance()->getDb();
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $id);

        try {
            $db->beginTransaction();

            $stmt->execute();

            $db->commit();
        } catch (PDOException $ex) {
            $db->rollBack();
            return $ex->getMessage();
        }

        return 1;
    }

    public static function updateParticipantScore($id, $score) {
        $query = "UPDATE quiz_participant SET score = :score WHERE _id = :id";

        $db = Database::getInstance()->getDb();
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":score", $score);

        try {
            $db->beginTransaction();

            $stmt->execute();

            $db->commit();
        } catch (PDOException $ex) {
            $db->rollBack();
            return $ex->getMessage();
        }

        return 1;
    }

    public static function insertQuiz($quizArray, $questions) {
        $fields = ['name', 'start_time', 'duration_minutes', 'instructor_id'];

        $query = 'INSERT INTO quiz(' . implode(',', $fields) . ') VALUES(:' . implode(',:', $fields) . ')';

        $db = Database::getInstance()->getDb();
        $stmt = $db->prepare($query);

        $prepared_array = array();
        foreach ($fields as $field) {
            $prepared_array[':'.$field] = @$quizArray[$field];
        }

        try {
            $db->beginTransaction();

            $stmt->execute($prepared_array);

            $id = $db->lastInsertId();
            self::insertQuestions($questions, $id);

            $db->commit();
        } catch (PDOException $ex) {
            $db->rollBack();
            return $ex->getMessage();
        }

        return $id;
    }

    public static function getQuizScoreCard($id) {
        $query = "SELECT user.first_name AS first_name, user.last_name AS last_name, student.roll_number AS roll_number, quiz_participant.score AS score FROM user JOIN student ON user._id = student.user_id JOIN quiz_participant ON quiz_participant.user_id = user._id WHERE quiz_participant.quiz_id = :id ORDER BY quiz_participant.score DESC";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}