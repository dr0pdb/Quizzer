<?php

class Quizzes extends BasicPage {

    public function render() {
        $this->setTitle('My Quizzes');

        $user = '';

        $user_id = $this->getLoginInfo();
        if($user_id != 0 ){
            $user = User::getUserInfo($this->getLoginInfo());
        }

        Renderer::render("quizzes.php", [
            'user' => $user,
            'quizzes' => QuizService::getQuizzesForUser($user_id, parent::isStudent())
        ]);
    }
}