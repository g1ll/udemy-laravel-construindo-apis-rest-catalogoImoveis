<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\RealStateRequest;
use App\Models\RealState;
use Illuminate\Support\Facades\Auth;
use Exception;

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

    //Using model bind, require header "Accept=application/json" to avoid redirection to NOT FOUND Laravel page.
    public function show(RealState $real_state){
        try{
            return response()->json(['data'=>$real_state],201);
        }catch(Exception $error){
            $message = new ApiMessages("An error occurred!",[$error->getMessage()]);
            return response()->json($message->getMessage(),400);
        }
    }

    public function store(RealStateRequest $request){
        $data = $request->all();
        $images = $request->file('images');
        try{
            if(!$data)
                throw new Exception("Error: Dados inválidos!");

            $realState = Auth::user()->real_state()->create($data);

            if(isset($data['categories'])&&count($data['categories']))
                $realState->categories()->sync($data['categories']);

            if($images)
                foreach ($images as $img) {
                    $img->store('images','public');
//                    dd($path);
                }

            return response()->json(
                [   'msg'=>'Novo registro de imóvel inserido com sucesso!',
                    'data'=>$realState
                ],201);
        }catch(Exception $error) {
            $message = new ApiMessages("An error occurred!",[$error->getMessage()]);
            return response()->json($message->getMessage(),400);
        }
    }

    public function destroy($id){
        try{
            $real_state = $this->realState->findOrfail($id);
            $real_state->delete();
            return response()->json(
                [   'msg'=>'Registro excluído com sucesso!',
                    'data'=>$real_state
                ],201);
        }catch(Exception $error){
            $message = new ApiMessages("An error occurred!",[$error->getMessage()]);
            return response()->json($message->getMessage(),400);
        }
    }

//    public function update(RealState $real_state, Request $request){
    public function update($id, RealStateRequest $request){
        $data = $request->all();
        try{
            if(!$data)
                throw new Exception("Error: Dados inválidos!");
            $real_state = $this->realState->findOrfail($id);
            $real_state->update($data);

            if(isset($data['categories'])&&count($data['categories']))
                $real_state->categories()->sync($data['categories']);

            return response()->json(
                [   'msg'=>'Registro atualizado com sucesso!',
                    'data'=>$real_state
                ],201);
        }catch(Exception $error){
            $message = new ApiMessages("An error occurred!",[$error->getMessage()]);
            return response()->json($message->getMessage(),400);
        }
    }
}
