<?php 
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

class DumpCammand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'deleteolddb';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Export db dump';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		//delete old than 7 days
		$dir = 'public/backups';
        $files = scandir($dir);
        foreach($files as $f){
	         if($f!='index.html' && strlen($f) > 5){
	         	$ex = explode('.', $f);
	         	$date1=date_create(date('Y-m-d'));
				$date2=date_create(date('Y-m-d', strtotime($ex[0])));
				$diff=date_diff($date1,$date2);
				if($diff->d > 7) unlink($dir.'/'.$f);
	         }
        } 
	}

}
