<?php
namespace App\Console\Commands\BulkProcesses;

use App\Http\Controllers\Traits\CanUploadImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Predis\Client;

/*use Illuminate\Support\Facades\Redis;*/

class SubscribeToEventsCommand extends Command
{
    use CanUploadImage;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscribe:broadcast-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to broadcasted events.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        try{
            /*$redis = Redis::connection('events');
            $redis->psubscribe(['INSTITUTION_CREATED'], function ($message) {
                echo $message;
            });*/

            $redis = new Client([
                'scheme' => 'tcp',
                'host'   => '127.0.0.1',
                'port'   => 6379,
                'read_write_timeout' => 0
            ],[
                'parameters' => [
                    'password' => 'L+Uvup0fs9/jDjvWbhL2vrVtMMlKNzdSoiBRJMCe56B/8doN+GGYmA6iqc6axpHuibbGFkW4geJGw1nw',
                    'database' => 2
                ]
            ]);

            $pubsub = $redis->pubSubLoop();

            $pubsub->subscribe('EVENTSINSTITUTION_CREATED');

            foreach ($pubsub as $message) {
                switch ($message->kind) {
                    case 'subscribe':
                        echo "Subscribed to {$message->channel}", PHP_EOL;
                        break;

                    case 'message':
                        if ($message->channel == 'control_channel') {
                            if ($message->payload == 'quit_loop') {
                                echo 'Aborting pubsub loop...', PHP_EOL;
                                $pubsub->unsubscribe();
                            } else {
                                echo "Received an unrecognized command: {$message->payload}.", PHP_EOL;
                            }
                        } else {
                            echo "Received the following message from {$message->channel}:",
                            PHP_EOL, "  {$message->payload}", PHP_EOL, PHP_EOL;
                        }
                        break;
                }
            }
            /*$redis->pconnect('127.0.0.1', 6379);

            $redis->auth('L+Uvup0fs9/jDjvWbhL2vrVtMMlKNzdSoiBRJMCe56B/8doN+GGYmA6iqc6axpHuibbGFkW4geJGw1nw');

            if ($redis->ping()) {
                echo "PONGn";
            }*/
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
    }
}
