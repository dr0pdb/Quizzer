<?php

class Participate extends BasicPage {
    private $quiz_id, $participant_id, $end_time;
    private $values = array();
    private $responses = array();
    private $quiz;
    private $questions = array();
    private $participant = array();
    private $too_early = false, $locked=false;
   
    public function __construct($id) {
        parent::__construct();
        $this->quiz_id = $id;
    }

    private function init() {
        $this->quiz = QuizService::getQuiz($this->quiz_id);
        if($this->quiz['start_time'] > time()) {
            $this->too_early = true;
            return;
        }

        $startDate = strtotime($this->quiz['start_time']);
        $end_timestamp = $startDate + (60 * $this->quiz['duration_minutes']);
        $this->end_time = date("Y-m-d H:i:s", $end_timestamp);

        $this->questions = QuizService::getQuizQuestions($this->quiz_id);
        $this->participant= QuizService::getQuizParticipant($this->getLoginInfo(), $this->quiz_id);
        if(!$this->participant) {
            $participantArray = array('quiz_id' => $this->quiz_id, 'user_id' => $this->getLoginInfo());
            $this->participant_id = QuizService::insertParticipant($participantArray);

            $index=1;
            foreach ($this->questions as $question) {
                $this->responses[$index]='E';

                $response = array('quiz_participant_id' => $this->participant_id, 'question_number' => $index, 'response' => 'E');
                QuizService::insertParticipantResponse($response, $this->participant_id);
                $index++;
            }
        } else {
            $this->participant_id = $this->participant['_id'];
            $saved_responses = QuizService::getParticipantResponses($this->participant_id);
            $index=1;
            foreach ($saved_responses as $saved_response) {
                $this->responses[$index]=$saved_response['response'];
                $index++;
            }
        }

        $participant = QuizService::getQuizParticipantWithId($this->participant_id);
        $this->locked = $participant['locked'];
    }

    public function render() {
        $this->init();  
        $this->setTitle($this->quiz['name']);
        $errors = array();
        $success = "";
        
        if($this->too_early == false) {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                if($this->participant['locked']) {
                    $errors[] = "You cannot edit your responses";
                } else {
                    $score=0;
                    foreach ($_POST as $key => $value) {
                        if($key == 'auto') {
                            continue;
                        }

                        $this->responses[$key]=$value;
                        if ($this->questions[ord($key)-ord('0')-1]['answer'] == $value) {
                            $score += 1.0;
                        } else {
                            $score -= $this->quiz['negative_mark'];
                        }
                    }

                    QuizService::updateParticipantScore($this->participant_id, $score);
                    QuizService::updateParticipantResponses($this->responses, $this->participant_id);

                    if(!isset($_POST['auto'])) {
                        $result = QuizService::lockParticipantSubmissions($this->participant_id);
                        $this->participant = QuizService::getQuizParticipantWithId($this->participant_id);
                        $this->locked = $this->participant['locked'];

                        if(is_int($result) && $result != 0) {
                            $success = "Successfully submitted";
                        } else if(is_int($result) && $result == 0) {
                            $errors[] = "An Error Occurred!";
                        }
                    }
                }
            }
        }

        Renderer::render("participate.php", [
            'too_early' => $this->too_early,
            'quiz' => $this->quiz,
            'questions' => $this->questions,
            'errors' => $errors,
            'success' => $success,
            'end_time' => $this->end_time,
            'responses' => $this->responses,
            'locked' => $this->locked
        ]);
    }
}