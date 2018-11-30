<?php

class Participate extends BasicPage {
    private $quiz_id, $participant_id, $end_time;
    private $values = array();
    private $responses = array();
    private $quiz;
    private $questions = array();
    private $participant = array();
   
    public function __construct($id) {
        parent::__construct();
        $this->quiz_id = $id;
    }

    private function init() {
        $this->quiz = QuizService::getQuiz($this->quiz_id);
        $startDate = strtotime($this->quiz['start_time']);
        $end_timestamp = $startDate + (60 * $this->quiz['duration_minutes']);
        $this->end_time = date("Y-m-d H:i:s", $end_timestamp);

        $this->questions = QuizService::getQuizQuestions($this->quiz_id);
        $this->participant_id = QuizService::getQuizParticipantId($this->getLoginInfo(), $this->quiz_id);
        if($this->participant_id == 0) {
            $participantArray = array('quiz_id' => $this->quiz_id, 'user_id' => $this->getLoginInfo());
            $this->participant_id = QuizService::insertParticipant($participantArray);

            $index=1;
            foreach ($this->questions as $question) {
                $this->responses[$index]='E';
                $index++;

                $response = array('quiz_participant_id' => $this->participant_id, 'question_number' => $index, 'response' => 'E');
                QuizService::insertParticipantResponse($response, $this->participant_id);
            }
        } else {
            $saved_responses = QuizService::getParticipantResponses($this->participant_id);
            $index=1;
            foreach ($saved_responses as $saved_response) {
                $this->responses[$index]=$saved_response['response'];
                $index++;
            }
        }

        $participant = QuizService::getQuizParticipant($this->participant_id);
    }

    public function render() {
        $this->init();  
        $this->setTitle($this->quiz['name']);
        $errors = array();
        $success = "";
     
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->participant['locked']) {
                $errors[] = "You cannot edit your responses";
                return;
            }

            $score=0;
            foreach ($_POST as $key => $value) {
                if(isset($_POST['auto']) && $key == 'auto') {
                    continue;
                }

                $this->responses[$key]=$value;
                if ($this->questions[$key-1]['answer'] == $value) {
                    $score += 1.0;
                }
            }

            QuizService::updateParticipantScore($participant_id, $score);
            QuizService::updateParticipantResponses($responses, $participant_id);

            if(!isset($_POST['auto'])) {
                $result = QuizService::lockParticipantSubmissions($participant_id);
                $this->participant = QuizService::getParticipant($participant_id);

                if(is_int($result) && $result != 0) {
                    $success = "Successfully submitted";
                } else if(is_int($result) && $result == 0) {
                    $errors[] = "An Error Occurred!";
                }
            }
        }

        Renderer::render("participate.php", [
            'quiz' => $this->quiz,
            'questions' => $this->questions,
            'errors' => $errors,
            'success' => $success,
            'end_time' => $this->end_time,
            'responses' => $this->responses
        ]);
    }
}