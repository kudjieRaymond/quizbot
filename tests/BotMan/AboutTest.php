<?php

namespace Tests\BotMan;

use Tests\TestCase;

class AboutTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testReplyWithAboutText()
    {
        $this->bot
            ->receives('/about')
            ->assertReply('QuizBot is a Telegram bot built for educational purposes');
    }


}
