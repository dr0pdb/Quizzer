<?php

class QuizAnalytics extends BasicPage {
    private $quiz_id;

    public function __construct($id) {
        parent::__construct();
        $this->quiz_id = $id;
    }

    public function render() {
        $quiz = QuizService::getQuiz($this->quiz_id);
        $this->setTitle('Quiz Results');

        $user = '';

        $user_id = $this->getLoginInfo();
        if($user_id != 0 ){
            $user = User::getUserInfo($this->getLoginInfo());
        }

        $score_card = QuizService::getQuizScoreCard($this->quiz_id);

        Renderer::render("quizanalytics.php", [
            'quiz' => $quiz,
            'score_card' => $score_card,
        ]);
    }
}