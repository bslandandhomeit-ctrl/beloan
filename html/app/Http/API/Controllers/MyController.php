<?php namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use Auth;


class MyController extends Controller
{
    protected $permission = [];

    public function __construct()
    {
        $this->permission = Auth::user()->permission; //ALL_FUNCTIONS;
    }
}