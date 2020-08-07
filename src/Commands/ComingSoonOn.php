<?php

namespace MBonaldo\ComingSoon\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\InteractsWithTime;

class ComingSoonOn extends Command
{
    use InteractsWithTime;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'comingsoon:on 	{--message= : The message for the coming soon mode}
											{--retry= : The number of seconds after which the request may be retried}
											{--allow=* : IP or networks allowed to access the application while in coming soon mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Put the application into coming soon mode';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            if (file_exists(storage_path('framework/comingsoon'))) {
                $this->comment('Application is already on coming soon.');

                return true;
            }

            file_put_contents(storage_path('framework/comingsoon'),
                              json_encode($this->getDownFilePayload(),
                              JSON_PRETTY_PRINT));

            $this->comment('Application is now in coming soon mode.');
        } catch (Exception $e) {
            $this->error('Failed to enter coming soon mode.');

            $this->error($e->getMessage());

            return 1;
        }
    }

    /**
     * Get the payload to be placed in the "down" file.
     *
     * @return array
     */
    protected function getDownFilePayload()
    {
        return [
            'time' => $this->currentTime(),
            'message' => $this->option('message'),
            'retry' => $this->getRetryTime(),
            'allowed' => $this->option('allow'),
        ];
    }

    /**
     * Get the number of seconds the client should wait before retrying their request.
     *
     * @return int|null
     */
    protected function getRetryTime()
    {
        $retry = $this->option('retry');

        return is_numeric($retry) && $retry > 0 ? (int) $retry : null;
    }
}
