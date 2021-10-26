<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RealStateSearchController extends Controller
{
    private $realState;
    public function __construct(RealState $realState){
        $this->realState = $realState;
    }

    public function index()
    {
        $realState = $this->realState->paginate(10);
        return response()->json([
            'data' => $realState
        ],200);
    }


    public function show($id)
    {
        //
    }
}
