<?php
namespace App\Agents;

use Alr\ObjectDotNotation\Data;
use App\AgentInterface;
use App\EventHelper;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use \React\EventLoop\Factory;
use \unreal4u\TelegramAPI\HttpClientRequestHandler;
use \unreal4u\TelegramAPI\TgLog;
use \unreal4u\TelegramAPI\Telegram\Methods\SendMessage;

class Telegram implements AgentInterface
{
    public $name = 'Telegram agent';
    public $description = 'The Telegram Agent receives and collects events and sends them via Telegram.';

    public function process($output, Data $agent)
    {
        $log = new Logger('name');
        $log->pushHandler(new StreamHandler($agent->get('filename').'.log', Logger::INFO));

        $loop = Factory::create();
        $tgLog = new TgLog($agent->get('config.bot_token'), new HttpClientRequestHandler($loop));
        $sendMessage = new SendMessage();
        $sendMessage->chat_id = $agent->get('config.chat_id');

        foreach ($agent->get('sources') as $source) {
            $source_file = dirname($agent->get('filename')) . '/' . $source;
            if(file_exists($source_file)) {
                $md5 = md5($source_file);
                $events = scandir(base_path('muninn-data/events/'));
                foreach ($events as $event) {
                    $event_location = base_path('muninn-data/events/' . $event);
                    if (preg_match('/' . preg_quote($md5) . '/', $event)) {
                        $event = json_decode(file_get_contents($event_location));
                        unlink($event_location);
                        if($event->link ?? false) {
                            $sendMessage->text = '['.($event->text ?? null).']('.($event->link ?? null).')';
                        } else {
                            $sendMessage->text = $event->text ?? null;
                        }
                        $sendMessage->parse_mode = 'markdown';

                        try {
                            $tgLog->performApiRequest($sendMessage);
                            $loop->run();
                        } catch (\Exception $e) {
                            $log->error($e->getMessage());
                        }
                    }
                }
            }
        }
//        $eh = new EventHelper();
//        $eh->getEventsForMe()

    }
}