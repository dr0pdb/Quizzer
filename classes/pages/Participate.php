<?php

class Participate extends BasicPage {
    private $quiz_id;
    private $values = array();
   
    public function __construct($id) {
        parent::__construct();
        $this->quiz_id = $id;
    }

    public function render() {
        $quiz = QuizService::getQuiz($this->quiz_id);
        $startDate = strtotime($quiz['start_time']);
        $end_timestamp = $startDate + (60 * $quiz['duration_minutes']);
        $end_time = date("Y-m-d H:i:s", $end_timestamp);
        $questions = QuizService::getQuizQuestions($this->quiz_id);

        $this->setTitle($quiz['name']);
        $errors = array();
        $success = "";
     
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $participantArray = array('quiz_id' => $this->quiz_id, 'user_id' => $parent->getLoginInfo());
            $responses = array();

            foreach ($_POST as $key => $value) {
                $responses[] = array('question_number' => $key, 'response' => $value);
            }

            $result = QuizService::insertParticipantAndResponses($participantArray, $responses);

            if(is_int($result) && $result != 0) {
                $success = "Successfully submitted";
            } else if(is_int($result) && $result == 0) {
                $errors[] = "An Error Occurred!";
            }
        }

        Renderer::render("participate.php", [
            'quiz' => $quiz,
            'questions' => $questions,
            'errors' => $errors,
            'success' => $success,
            'end_time' => $end_time
        ]);
    }
}