<?php

namespace Silvanite\Agencms\Controllers;

use Illuminate\Http\Request;
use Silvanite\Agencms\Facades\Agencms;
use Silvanite\Agencms\Handlers\AgencmsHandler;

class ConfigController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(Agencms::plugins());
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
