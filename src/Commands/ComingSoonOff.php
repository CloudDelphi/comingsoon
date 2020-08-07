<?php

namespace MBonaldo\ComingSoon\Commands;

use Exception;
use Illuminate\Console\Command;

class ComingSoonOff extends Command
{
	
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'comingsoon:off';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bring the application out of coming soon mode';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            if (! file_exists(storage_path('framework/comingsoon'))) {
                $this->comment('Application is already up.');

                return true;
            }

            unlink(storage_path('framework/comingsoon'));

            $this->info('Application is now live.');
        } catch (Exception $e) {
            $this->error('Failed to disable coming soon mode.');

            $this->error($e->getMessage());

            return 1;
        }
    }
}
