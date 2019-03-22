<?php
namespace App\Agents;

use App\AgentInterface;
use \React\EventLoop\Factory;
use \unreal4u\TelegramAPI\HttpClientRequestHandler;
use \unreal4u\TelegramAPI\TgLog;
use \unreal4u\TelegramAPI\Telegram\Methods\SendMessage;

class Telegram implements AgentInterface
{
    public $name = 'Telegram agent';
    public $description = 'The Telegram Agent receives and collects events and sends them via Telegram.';

    public function process(\App\Models\Agent $agent)
    {

        $loop = Factory::create();
        $tgLog = new TgLog(BOT_TOKEN, new HttpClientRequestHandler($loop));
        $sendMessage = new SendMessage();
        $sendMessage->chat_id = A_USER_CHAT_ID;
        $sendMessage->text = 'Hello world!';
        $tgLog->performApiRequest($sendMessage);
        $loop->run();
    }
}