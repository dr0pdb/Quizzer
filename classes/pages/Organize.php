<?php

class Organize extends BasicPage {

    private $defaults = [
        'name' => 'Quiz Title',
        'start_time' => 'Start Date & Time',
        'duration_minutes' => 'Quiz Duration',
        'negative_mark' => '0'
    ];
    private $values = array();

    public function __construct() {
        parent::__construct();
    }

    private function verify() {
        $errors = array();

        $required = ['name',  'start_time', 'duration_minutes'];


        foreach ($required as $field) {
            if (!isset($_POST[$field]) || strlen($_POST[$field]) == 0)
                $errors[] = $field . ' is required!';
            else
                $this->values[$field] = $_POST[$field];
        }

        if($_FILES['questions']['error'] != 0) {
            array_push($errors, $_FILES['questions']['error']);
        }

        return $errors;
    }

    private function getQuestions() {
        $questionsFile = $_FILES['questions']['tmp_name'];

        // TODO: Add checks and error handling.
        $rows   = array_map('str_getcsv', file($questionsFile));
        $header = array_shift($rows);
        $csv    = array();
        foreach($rows as $row) {
            $csv[] = array_combine($header, $row);
        }

        return $csv;
    }

    public function render() {
        $this->setTitle('Organize a Quiz');

        $user = '';

        if($this->getLoginInfo() != 0 ){
            $user = User::getUserInfo($this->getLoginInfo());
        } else {
            $errors[] = 'User does not exist! Please register first!';
        }

        $errors = array();
        $success = "";

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = $this->verify();
            $questions = $this->getQuestions();
            if(count($errors) == 0) {
                $_POST['instructor_id']=$this->getLoginInfo();
                $result = QuizService::insertQuiz($_POST, $questions);

                if($result != 0) {
                    $success = 'Quiz successfully scheduled!';
                } else {
                    $errors[] = 'Quiz creation failed!';
                }
            }
        }

        Renderer::render("organize.php", [
            'user' => $user,
            'defaults' => $this->defaults,
            'values' => $this->values,
            'errors' => $errors,
            'success' => $success
        ]);
    }

}