<?php

class Quizzes extends BasicPage {

    public function render() {
        $this->setTitle('My Quizzes');

        $user = '';

        $user_id = $this->getLoginInfo();
        if($user_id != 0 ){
            $user = User::getUserInfo($this->getLoginInfo());
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['transaction_id']) && strlen($_POST['transaction_id']) != 0) {
                RentalService::removeRental($_POST['transaction_id']);
            }
        }

        Renderer::render("quizzes.php", [
            'user' => $user,
            'quizzes' => QuizService::getQuizzesForUser($user_id, parent::isStudent())
        ]);
    }
}