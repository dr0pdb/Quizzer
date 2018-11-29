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
            $query = "SELECT * FROM quiz WHERE `_id` IN (SELECT quiz_id FROM instructor_quiz WHERE instructor_id = :id)";
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

    private static function insertParticipantResponses($responses, $participant_id) {
        $fields = ['quiz_participant_id', 'question_number', 'response'];

        $query = 'INSERT INTO quiz_participant_response(' . implode(',', $fields) . ') VALUES(:' . implode(',:', $fields) . ')';
        $db = Database::getInstance()->getDb();
        $stmt = $db->prepare($query);

        foreach ($responses as $response) {
            $prepared_array = array();
            $response['quiz_participant_id'] = $participant_id;
            foreach ($fields as $field) {
                $prepared_array[':'.$field] = @$response[$field];
            }
        
            $stmt->execute($prepared_array);
        }
    }

    public static function insertParticipantAndResponses($participantArray, $responses) {
        $fields = ['quiz_id', 'user_id'];

        $query = 'INSERT INTO quiz_participants(' . implode(',', $fields) . ') VALUES(:' . implode(',:', $fields) . ')';

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
            self::insertParticipantResponses($responses, $id);

            $db->commit();
        } catch (PDOException $ex) {
            $db->rollBack();
            return $ex->getMessage();
        }

        return $id;
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

}