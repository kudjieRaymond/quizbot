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
                ['text' => 'Physicist', 'correct_one' => false],
                ['text' => 'Biologist', 'correct_one' => false],
                ['text' => 'Physician', 'correct_one' => true],
            ],
				],
				[
					'question' => 'Where is the Taj Mahal Located?',
					'points' => '10',
					'answers' => [
							['text' => 'India', 'correct_one' => true],
							['text' => 'France', 'correct_one' => false],
							['text' => 'Nigeria', 'correct_one' => false],
					],
				],
				[
					'question' => 'What is the name of the president of Philipines ?',
					'points' => '5',
					'answers' => [
							['text' => 'John Magafuli', 'correct_one' => false],
							['text' => 'Rodrigo Duterte', 'correct_one' => true],
							['text' => 'Francois Hollande', 'correct_one' => false],
					],
				],
				[
					'question' => 'How many NBA Championship did LeBron James win ?',
					'points' => '10',
					'answers' => [
							['text' => '5', 'correct_one' => false],
							['text' => '2', 'correct_one' => false],
							['text' => '3', 'correct_one' => true],
					],
				],
				[
					'question' => 'Who won the formula 1 championship in 2018-2019?',
					'points' => '5',
					'answers' => [
							['text' => 'Lewis Hamilton', 'correct_one' => true],
							['text' => 'Sebastian Vettel', 'correct_one' => false],
							['text' => 'Fernando Alonso', 'correct_one' => false],
					],
				],
				[
					'question' => 'Where was the Olympic held in  2012?',
					'points' => '5',
					'answers' => [
							['text' => 'Beijing', 'correct_one' =>false],
							['text' => 'Paris', 'correct_one' => false],
							['text' => 'London', 'correct_one' => true],
					],
				],
				[
					'question' => 'what is arachnophobia?',
					'points' => '5',
					'answers' => [
							['text' => 'Fear of height', 'correct_one' => false],
							['text' => 'Fear of spiders', 'correct_one' => true],
							['text' => 'Love for food', 'correct_one' => false],
					],
			],
    ]);
	}
}
