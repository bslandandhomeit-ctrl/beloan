<?php namespace app\Http\API\Controllers\Traccar;

use App\Http\API\Controllers\MyController;

use Illuminate\Routing\Controller;


class UserController extends MyController
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index(){

        return $this->view('Traccar.users', ['permission'=>$this->permission]);

    }
}