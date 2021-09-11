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
        $this->middleware('auth.basic',
            ['except'=>
                [
                    'index',
                    'show'
                ]
        ]);
    }

    public function index(){
        $realState = $this->realState->paginate(10);
        return response()->json($realState,200);
    }

    public function show(RealState $real_state){
        if(!$real_state)
            return response()->json(["Error, state not found"],404);
        return response()->json($real_state,200);
    }

    public function store(Request $request){
        $data = $request->all();
        if(!$data){
            return response()->json(['Error'=>'Invalid data sent!'],400);
        }
        return response()->json(Auth::user()->real_state()->create($data),201);
    }

    public function destroy(RealState $real_state){
        if(!$real_state->delete())
            return response()->json(['Error to remove, try later!']);
        return response()->json(['Property removed successfully!']);
    }

    public function update(RealState $real_state, Request $request){
        $data = $request->all();
        if(!$data){
            return response()->json(['Error'=>'Invalid data sent!'],400);
        }
        if($real_state->update($data))
            return response()->json($real_state,201);
        else return response()->json("Error to update register!",500);
    }
}
