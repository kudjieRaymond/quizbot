<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Question;
use App\Answer;
use App\Highscore;
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
			
			$this->ask($this->createQuestionTemplate($question), function(BotManAnswer $answer) use ($question) {

				$quizAnswer = Answer::find($answer->getValue());

				if(!$quizAnswer){
					$this->say('Sorry, I did not get that. Please use the buttons.');
					return $this->checkForNextQuestion();
				}

				$this->quizQuestions->forget($question->id);//remove question from collection

				if($quizAnswer->correct_one){
					$this->userPoints += $question->points;
					$this->userCorrectAnswers++;
					$answerResult = '✅';
				}else{
					$correctAnswer = $question->answers()->where('correct_one', true)->first()->text;
					$answerResult = "❌ (Correct: {$correctAnswer})";
				}
				$this->currentQuestion++;
				
				$this->say("Your answer: {$quizAnswer->text} {$answerResult}");

				$this->checkForNextQuestion();
			});

		}

		private function createQuestionTemplate(Question $question)
		{
			$questionText = '➡️ Question: '.$this->currentQuestion.' / '.$this->questionCount.' : '.$question->text;
			$questionTemplate = BotManQuestion::create($questionText);

			foreach($question->answers->shuffle() as $answer){
				$questionTemplate->addButton(Button::create($answer->text)->value($answer->id));
			}
			return $questionTemplate;

		}

		private function showResult()
		{
			$this->say('Finished 🏁');
			$this->say("You made it through all the questions. You reached {$this->userPoints} points! Correct answers: {$this->userCorrectAnswers} / {$this->questionCount}");

			$this->askAboutHighscore();
		}

		private function askAboutHighscore()
		{
			$question = BotManQuestion::create('Do you want to get added to the highscore list? Only your latest result will be saved. To achieve that, we need to store your name and chat id.')->addButtons([
											Button::create('Yes')->value('yes'),
										  Button::create('No')->value('no')]);
			
			$this->ask($question, function(BotManAnswer $answer){

				if($answer->getValue() == 'yes'){

					$user = Highscore::saveUser($this->bot->getUser(), $this->userPoints, $this->userCorrectAnswers);
					$this->say("Done. Your rank is {$user->rank}.");
					return $this->bot->startConversation(new HighscoreConversation());

				}else if($answer->getValue() == 'no'){
					
					return $this->say('Not problem. You were not added to the highscore. Still you can tell your friends about it 😉');

				}else{
					
					return $this->repeat('Sorry, I did not get that. Please use the buttons.');
				}
			});
		}
}
