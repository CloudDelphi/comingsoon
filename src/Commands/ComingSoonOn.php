<?php

namespace MBonaldo\ComingSoon\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\InteractsWithTime;
use DateInterval;

class ComingSoonOn extends Command
{
    use InteractsWithTime;
//	use DateTime;
//	use DatePeriod;	
//	use DateInterval;
	
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'comingsoon:on 	{--message= : The message for the coming soon mode}
											{--blade= : The blade page for the coming soon mode}
											{--date= : The date of when it will end the coming soon mode, format (yyyy-mm-dd)}
											{--time= : The time of when it will end the coming soon mode}
											{--indays= : The number of days after which the request may be retried}
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
		$datepassed = $this->getEndingDate();
		$timepassed = $this->getEndingTime();
		$indays = $this->getInDays();
		$enddatetime = $this->buildEndingDateTime($datepassed, $timepassed, $indays); 
        return [
            'time' => $this->currentTime(),
			'blade' => $this->option('blade'),
            'message' => $this->option('message'),
            //'indays' => $this->getInDays(),
            'allowed' => $this->option('allow'),
			//'date' => $datepassed,
			//'time' => $timepassed,
			'enddatetime' => $enddatetime,
        ];
    }

    /**
     * Get the number of seconds the client should wait before retrying their request.
     *
     * @return int|null
     */
    protected function getInDays()
    {
        $indays = $this->option('indays');

        return is_numeric($indays) && $indays > 0 ? (int) $indays : null;
    }

    /**
     * Get the number of seconds the client should wait before retrying their request.
     *
     * @return int|null
     */
    protected function getEndingDate()
    {
		$enddate = null;
		$param = $this->option('date');
		
		echo 'Parameter date: ' . $param . PHP_EOL;

		if (!is_null($param))
		{
			list($year,$month,$day) = explode('-', $param);
			//$year = substr($param,0,4);
			//$month = substr($param,5,2);
			//$day = substr($param,8,2);
	
			echo 'Year ' . $year . ' Month ' . $month . ' Day ' . $day . PHP_EOL;
		
			if (checkdate($month, $day, $year) == TRUE ) 
			{
				$enddate = date_create();
				date_date_set($enddate, $year, $month, $day);
				date_time_set($enddate, 0, 0, 0);
			}
	
			echo 'Parameter passed: ' . date_format($enddate, 'd-m-Y') . PHP_EOL;
		
		}
		
        return $enddate;
    }

    /**
     * Get the number of seconds the client should wait before retrying their request.
     *
     * @return int|null
     */
    protected function getEndingTime()
    {
		$endtime = null;	
		$param = $this->option('time');
		
		echo 'Parameter time: ' . $param . PHP_EOL;

		if (!is_null($param))
		{
			list($hour,$minute,$second) = explode(':', $param);
			//$hour = substr($param,0,2);
			//$minute = substr($param,3,2);
			//$second = substr($param,6,2);

			echo 'Hour ' . $hour . ' Minute ' . $minute . ' Second ' . $second . PHP_EOL;
			
			if ($this->checktime($hour, $minute, $second) == TRUE) 
			{
				$endtime = date_create();
				date_date_set($endtime, 0, 0, 0);
				date_time_set($endtime, $hour, $minute, $second);
			}

			echo 'Parameter passed: ' . date_format($endtime, 'H:i:s') . PHP_EOL;
		}

        return $endtime;
    }

	protected function checktime($hour, $min, $sec) 
	{
		if ($hour < 0 || $hour > 23 || !is_numeric($hour)) 
		{
			return false;
		}
		if ($min < 0 || $min > 59 || !is_numeric($min)) 
		{
			return false;
		}
		if ($sec < 0 || $sec > 59 || !is_numeric($sec)) 
		{
			return false;
		}
		return true;
	}
	
	protected function buildEndingDateTime($date, $time, $days)
	{
		$newtime = date_create();
		
		echo $newtime->format('y-m-d H:i:s') . PHP_EOL;

		if (!is_null($date))
		{		
			echo $date->format('y-m-d H:i:s') . PHP_EOL;
			date_date_set($newtime, $date->format("Y"), $date->format("m"), $date->format("d"));
		}
		else
		{		
			date_time_set($newtime, 0, 0, 0);
			if (!is_null($days))
			{
				$interval = new DateInterval('P' . $days . 'D');
				$newtime->add($interval);
			}
		}

		echo $newtime->format('y-m-d H:i:s') . PHP_EOL;

		if (!is_null($time))
		{		
			echo $time->format('y-m-d H:i:s') . PHP_EOL;	
			date_time_set($newtime, $time->format("H"), $time->format("i"), $time->format("s"));
		}
		else
		{		
			date_time_set($newtime, 0, 0, 0);
		}
		
		echo $newtime->format('y-m-d H:i:s') . PHP_EOL;
		
		return $newtime;
	}
}
