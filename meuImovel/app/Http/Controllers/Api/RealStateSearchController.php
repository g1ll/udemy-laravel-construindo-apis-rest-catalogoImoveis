<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RealState;
use App\Repository\RealStateRepository;
use Illuminate\Http\Request;

class RealStateSearchController extends Controller
{

    private $realState;
    private $country;
    private $state;
    private $city;
    private $address;

    public function __construct(RealState $realState){
        $this->realState = $realState;
    }

    public function index(Request $request)
    {
        // 1 | RS -> 1 | Pelotas-> 1
//        return $this->realState->whereHas('address', function ($q){
//          $q->where('state_id',1)
//              ->where('city_id',1)
//              ->where('address_id',5);
//        })->get();
        $repository = new RealStateRepository($this->realState);
        if($request->all()){
            if($request->has('conditions'))
                $repository->addConditions($request->get('conditions'));

            if($request->has('fields'))
                $repository->selectFilter($request->get('fields'));
        }

        $repository->setLocation($request->all(['state','city','address']));
        return response()->json([
            'data' => $repository->getResult()
                ->with('address')
                ->with('photos')
                    ->paginate(10)
        ],200);
    }


    public function show($real_state_id)
    {
        try {
            $realState = $this->realState->with('address')
                ->with('photos')
                ->findOrFail($real_state_id);
            return response()->json([
                'data' => $realState
            ],200);
        }catch( \Exception $e){
                return response()->json([
                    'error' => $e->getMessage()
                ],404);
        }
    }

//    public function location($country, $state, $city, $address){
    public function location( $state, $city, $address=null){

//        $this->country = $country;
        $this->state = $state;
        $this->city = $city;
        $this->address = $address;
        $foundedRealState = $this->realState->whereHas('address', function ($q){
            $q->where('state_id',$this->state);
            if($this->city)
                $q->where('city_id',$this->city);
            if($this->address)
                $q->where('address_id',$this->address);
        })->get();
        return response()->json($foundedRealState,200);
    }
}
