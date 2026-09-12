<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Http\Controllers;

/**
 * Description of SettingController
 *
 * @author chuch
 */
use Request;
use DB; 
use Illuminate\Support\Facades\Session;
use Artisan;
use Response;

class SettingController extends Controller{
    //put your code here
    public function __construct()
    {
        $this->middleware('xss');
        $this->middleware('auth');
    }

    public function index()
    {
    	//work with file
    	$n = "global.php";
    	$data['l'] = fopen($n, "a+");
    	
    	if(Request::has('_token')){
    		$con = "<?php\n";
    		for($m=0; $m < count(Request::input('constant_value')); $m++){
    			$label = Request::input('constant_label')[$m];
    			$value = Request::input('constant_value')[$m];
    			$comment = Request::input('constant_comment')[$m];
    			//$str = is_numeric($value)?'':'"';
                $str = '"';
    			$com = $comment?' //'.$comment:'';
    			$con .= 'define("'.$label.'", '.$str.$value.$str.');'.$com. "\n";
    		}

    		$myfile = fopen($n, "w");
	    	fwrite($myfile, $con);
	    	fclose($myfile);
    	}
    	
        return $this->view('settings.index', $data);
    }
    
    function tableToCsv(){
    	//https://mattstauffer.co/blog/export-an-eloquent-collection-to-a-csv-with-league-csv
    	
    	$tables = DB::select('SHOW TABLES');
    	foreach ($tables as $ta){ 
    		$val = $ta->Tables_in_loansystem;
    		
    		$ex = explode('tb_', $val, 2);
    		$tab = $ex[1];
    		$c = \Schema::getColumnListing($tab);
    		$r = DB::table($tab)->first();
    		$csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
    		$csv->insertOne($c);
    		if($r){
    			$r_arr = array();
    			foreach ($c as $col){
    				$r_arr[] = $r->$col;
    			}
    			$csv->insertOne($r_arr);
    		}
    		
    		$csv_handler = fopen('csvs/'.$tab.'.csv','w');
    		fwrite ($csv_handler, $csv);
    		fclose ($csv_handler);
    		
    		//$csv->output($tab.'.csv');break;
    	}
    	
    	Session::flash('message', 'CSVs successfully saved!');
    	return redirect()->back();
    }

    //export db by click
    function export_db(){
        Artisan::call('db:backup');

        $dir = 'backups';
        $files = scandir($dir);
        foreach($files as $f){
         if($f!='index.html' && strlen($f) > 5) $file_arr[$f] = $f;
        } 
        $ff = end($file_arr);
        return Response::download($dir.'/'.$ff, $ff);
    }
}
