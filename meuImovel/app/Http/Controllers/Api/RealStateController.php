<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RealState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RealStateController extends Controller
{
    private $realState;

    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }

    public function index(){
        $realState = $this->realState->paginate(10);
        return response()->json($realState,200);
    }

    public function save(Request $request){
        $data = $request->all();

        if(!$data){
            return response()->json(['Error'=>'Invalid data sent!'],400);
        }
        return response()->json(Auth::user()->real_state()->create($data),201);
    }

    public function remove(RealState $realState){
        if(!$realState->delete())
            return response()->json(['Error to remove, try later!']);
        return response()->json(['Property removed successfully!']);
    }
}
