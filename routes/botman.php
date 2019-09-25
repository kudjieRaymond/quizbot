<?php
use App\Http\Controllers\BotManController;
use App\Conversations\QuizConversation;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});
$botman->hears('Start conversation', BotManController::class.'@startConversation');


$botman->hears('start', function ($bot) {
	$bot->startConversation(new QuizConversation());
});
