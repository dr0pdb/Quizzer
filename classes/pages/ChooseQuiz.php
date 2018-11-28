<?php

class ChooseQuiz extends BasicPage {

    public function render() {
        $this->setTitle('Take a Quiz');

        $user = '';

        $user_id = $this->getLoginInfo();
        if($user_id != 0 ){
            $user = User::getUserInfo($this->getLoginInfo());
        }

        Renderer::render("choosequiz.php", [
            'user' => $user,
            'current_quizzes' => QuizService::getQuizzesForToday(),
            'future_quizzes' => QuizService::getQuizzesForFuture()
        ]);
    }

}