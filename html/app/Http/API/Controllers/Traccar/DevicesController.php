<?php namespace app\Http\API\Controllers\Traccar;

use App\Http\API\Controllers\MyController;

use Illuminate\Routing\Controller;

class DevicesController extends MyController
{
    public function __construct()
    {

    }

    public function index(){

        return $this->view('Traccar.devices');

    }
}