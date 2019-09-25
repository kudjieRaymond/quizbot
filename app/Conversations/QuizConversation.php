<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Question;
use App\Answer;

class QuizConversation extends Conversation
{
	
	protected $questionCount = 0; 

	protected $quizQuestions;

	protected $userPoints = 0;

	protected $userCorrectAnswers = 0;

	protected $currentQuestion = 1;

    /**
     * First question
     */
    public function askReason()
    {
			 
			 
    }

    /**
     * Start the conversation
     */
    public function run()
    {
			$this->questionCount = Question::count();
			$this->quizQuestions = Question::all()->shuffle()->keyBy('id');
        $this->intro();
		}

		public function intro()
		{	

			$this->say('You will be shown '.$this->questionCount.' questions about General Knowledge. You will be rewarded with a certain amount of point for every correct answer . Please keep it fair, and don\'t use any help. All the best! 🍀');
			$this->checkForNextQuestion();
		}

		private function checkForNextQuestion()
		{
			if($this->quizQuestions->count()){
				return $this->askQuestion($this->quizQuestions->first());
			}
			$this->showResult();
		}

		private function askQuestion(Question $question)
		{
			$questionTemplate = BotManQuestion::create($question->text);
			
			foreach($question->answers->shuffle() as $answer){
				$questionTemplate->addButton(Button::create($answer->text)->value($answer->id));
			}
			$this->ask($questionTemplate, function(BotManAnswer $answer) use ($question) {

				$this->quizQuestions->forget($question->id);//remove question from collection
				
				$this->checkForNextQuestion();
			});


		}

		private function showResult()
		{
			$this->say('Finished 🏁');
			$this->say("You made it through all the questions. You reached {$this->userPoints} points! Correct answers: {$this->userCorrectAnswers} / {$this->questionCount}");
		}
}
