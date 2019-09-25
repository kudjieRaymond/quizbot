<?php

use Illuminate\Database\Seeder;
use App\Question;
use App\Answer;

class QuestionAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
			Question::truncate();
			Answer::truncate();
			$questionAndAnswers = $this->getData();

			$questionAndAnswers->each(function($item){
				$question = Question::create([
					"text"=>$item['question'],
					"points" =>$item['points'],
				]);

				collect($item['answers'])->each(function($answer) use ($question){
					Answer::create([
						'question_id' => $question->id,
						'text' => $answer['text'],
						'correct_one'=>$answer['correct_one'],
					]);
				});
			});
		}

		
		private function getData()
		{
    	return collect([
        [
            'question' => 'What is the capital of Ghana?',
            'points' => '5',
            'answers' => [
                ['text' => 'Nairobi', 'correct_one' => false],
                ['text' => 'Lagos', 'correct_one' => false],
                ['text' => 'Accra', 'correct_one' => true],
            ],
        ],
        [
            'question' => 'How do call a medical practitioner?',
            'points' => '10',
            'answers' => [
                ['text' => 'Physicist', 'correct_one' => true],
                ['text' => 'Biologist', 'correct_one' => false],
                ['text' => 'Physician', 'correct_one' => true],
            ],
        ],
    ]);
	}
}
