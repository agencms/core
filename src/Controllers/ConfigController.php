<?php

namespace Agencms\Core\Controllers;

use Illuminate\Http\Request;
use Agencms\Core\Facades\Agencms;
use Agencms\Core\Handlers\AgencmsHandler;

class ConfigController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(collect(Agencms::plugins())->sort()->all());
    }

    /**
     * Return the complete configuration structured data set.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Agencms::all();
    }
}
