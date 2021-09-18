<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->middleware('auth.basic',[
            'except'=>[
                'index',
                'show'
            ]]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = $this->user->paginate(10);
        return response()->json($user,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        $data = $request->all();
        try{

            if(!$data)
                throw new Exception("Dados inválidos!");

            if(isset($data['profile'])){
                $validation = validator($data['profile'],[
                    'phone' => 'required',
                    'mobile_phone' => 'required'
                ]);
                if($validation->fails())
                    throw new Exception("Error: Dados inválidos!");
            }

            if(!$request->has('password')||!$request->get('password'))
                throw new Exception("É necessário informar uma senha para o usuário!");
            else
                $data['password'] = password_hash($data['password'],PASSWORD_DEFAULT);//using defaul php crypt (today is bcrypt)

            $user = Auth::user()->create($data);
            if(isset($data['profile'])){
                $profile = $data['profile'];
                $profile['social_networks'] = serialize($profile['social_networks']);
                $user->profile()->create($profile);
            }
            return response()->json(
                [   'msg'   => 'Novo Usuário inserido com sucesso!',
                    'data'  => $user
//                    'data'=>User::create($data)//Test for unauthenticated
                ],201);
        }catch(Exception $error) {
            $erro_msg['msg'] = $error->getMessage();
            if(isset($validation))
                $erro_msg['validation'] = $validation->errors();
            $message = new ApiMessages("Ocorreu um erro!",$erro_msg);
            return response()->json($message->getMessage(),400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user){
        try{
            $user = $user->load('profile');
            $user['profile']['social_networks'] = unserialize($user->profile->social_networks);
            return response()->json(['data'=>$user],201);
        }catch(Exception $error){
            $message = new ApiMessages("An error occurred!",[$error->getMessage()]);
            return response()->json($message->getMessage(),400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request){
        $data = $request->all();
        try{
            $validation = validator($data['profile'],[
                'phone' => 'required',
                'mobile_phone' => 'required'
            ]);

            if($validation->fails())
                throw new Exception("Error: Dados inválidos!");


            if(!$data)
                throw new Exception("Error: Dados inválidos!");

            if($request->has('password') && $request->get('password'))
                $data['password'] = bcrypt($data['password']); //Using directly bcrypt function
            else unset($data['password']);//Force to remove the password field if empty;

            $user = $this->user->findOrfail($id);
            $user->update($data);
            $profile = $data['profile'];
            $profile['social_networks'] = serialize($profile['social_networks']);
            $user->profile()->update($profile);

            return response()->json(
                [   'msg'=>'Usuário atualizado com sucesso!',
                    'data'=>$user
                ],201);
        }catch(Exception $error){
            $message = new ApiMessages("Ocorreu um erro!",[
                'msg'=>$error->getMessage(),
                'validation'=>$validation->errors()
            ]);
            return response()->json($message->getMessage(),400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id){
        try{
            $user = $this->user->findOrfail($id);
            $user->delete();
            return response()->json(
                [   'msg'=>'Usuário removido com sucesso!',
                    'data'=>$user
                ],201);
        }catch(Exception $error){
            $message = new ApiMessages("An error occurred!",[$error->getMessage()]);
            return response()->json($message->getMessage(),400);
        }
    }
}
