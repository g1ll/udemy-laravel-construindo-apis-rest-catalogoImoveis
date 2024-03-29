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
//        $this->middleware('auth.basic',
//            ['except'=>
//                [
//                    'index',
//                    'show'
//                ]
//        ]);
    }

    public function index(){
        $realState = auth('api')->user()->real_state()->paginate(10);
        return response()->json($realState,200);
    }

    //Using model bind, require header "Accept=application/json" to avoid redirection to NOT FOUND Laravel page.
//    public function show(RealState $real_state){
    public function show($id){
        try{
//            dd(auth('api')->user()->id);
            $real_state_user= auth('api')->user()->real_state();
            $real_state_photos = $real_state_user->with('photos')
                                                 ->findOrFail($id)
                                                 ->makeHidden('_thumb');
            return response()->json(['data'=>$real_state_photos],201);
        }catch(Exception $error){
            $message = new ApiMessages("An error occurred!",[$error->getMessage()]);
            return response()->json($message->getMessage(),404);
        }
    }

    public function store(RealStateRequest $request){

        $data = $request->all();
        $images = $request->file('images');
        try{
            if(!$data)
                throw new Exception("Error: Dados inválidos!");

            $realState = auth('api')->user()->real_state()->create($data);

            if(isset($data['categories']) && count($data['categories']))
                $realState->categories()->sync($data['categories']);

            if($images) {
                $imagesUploaded = [];
                foreach ($images as $img) {
                    $path = $img->store('images', 'public');
                    $imagesUploaded[] = [
                        'photo'=>$path,
                        'is_thumb'=> false
                    ];
                }
                $realState->photos()->createMany($imagesUploaded);
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
            $real_state = auth('api')->user()->real_state()->findOrfail($id);
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
        $images = $request->file('images');
        try{
            if(!$data)
                throw new Exception("Error: Dados inválidos!");
            $real_state = auth('api')->user()->real_state()->findOrfail($id);
            $real_state->update($data);

            if(isset($data['categories'])&&count($data['categories']))
                $real_state->categories()->sync($data['categories']);

            if($images) {
                $imagesUploaded = [];
                foreach ($images as $img) {
                    $path = $img->store('images', 'public');
                    $imagesUploaded[] = [
                        'photo'=>$path,
                        'is_thumb'=> TRUE
                    ];
                }
                $real_state->photos()->createMany($imagesUploaded);
            }

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
