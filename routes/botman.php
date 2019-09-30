<?php
use App\Http\Controllers\BotManController;
use App\Conversations\QuizConversation;
use App\Conversations\HighscoreConversation;
use App\Conversations\WelcomeConversation;
use App\Conversations\PrivacyConversation;
use App\Http\Middleware\PreventDoubleClicks;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});
$botman->hears('Start conversation', BotManController::class.'@startConversation');


$botman->hears('/start', function ($bot) {
	$bot->startConversation(new WelcomeConversation());
})->stopsConversation();


$botman->hears('start|/startquiz', function ($bot) {
	$bot->startConversation(new QuizConversation());
})->stopsConversation();

$botman->hears('/highscore|highscore', function ($bot) {
	$bot->startConversation(new HighscoreConversation());
})->stopsConversation();

$botman->hears('/about|about', function ($bot) {
	$bot->reply('QuizBot is a Telegram bot built for educational purposes');
})->stopsConversation();

$botman->hears('/deletedata|deletedata', function ($bot) {
	$bot->startConversation(new PrivacyConversation());
})->stopsConversation();