<?php
 //die(date("Y-m-d", strtotime("-3 day")));
 //delete old files
 $dir = '_dbbk';
 $files = scandir($dir);
 foreach($files as $f){
	 if($f!='index.html') unlink($f);
 }
/* 
 * This script only works on linux.
 * It keeps only 31 backups of past 31 days, and backups of each 1st day of past months.
 * http://blog.easycron.com/2011/11/php-to-backup-mysql-database.html
 */
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


define('DB_HOST', 'localhost');
define('DB_NAME', '');
define('DB_USER', 'figix_loan');
define('DB_PASSWORD', '');
define('BACKUP_SAVE_TO', '_dbbk'); // without trailing slash
 
$time = time();
$day = date('j', $time);
if ($day == 1) {
    $date = date('Y-m-d', $time);
} else {
    $date = $day;
}


 
$backupFile = BACKUP_SAVE_TO . '/' . DB_NAME . '_' . $date . '.gz';
if (file_exists($backupFile)) {
    unlink($backupFile);
}
$command = 'mysqldump --opt -h ' . DB_HOST . ' -u ' . DB_USER . ' -p\'' . DB_PASSWORD . '\' ' . DB_NAME . ' | gzip > ' . $backupFile;
system($command);