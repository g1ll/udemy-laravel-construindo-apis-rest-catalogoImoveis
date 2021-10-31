<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RealState;
use App\Repository\RealStateRepository;
use Illuminate\Http\Request;

class RealStateSearchController extends Controller
{

    private $realState;
    public function __construct(RealState $realState){
        $this->realState = $realState;
    }

    public function index(Request $request)
    {
        // 1 | RS -> 1 | Pelotas-> 1
        return $this->realState->whereHas('address', function ($q){
          $q->where('state_id',1)
              ->where('city_id',1)
              ->where('address_id',5);
        })->get();
        $repository = new RealStateRepository($this->realState);
        if($request->all()){
            if($request->has('conditions'))
                $repository->addConditions($request->get('conditions'));

            if($request->has('fields'))
                $repository->selectFilter($request->get('fields'));
        }

        return response()->json([
            'data' => $repository->getResult()->paginate(10)
        ],200);
    }


    public function show($id)
    {
        //
    }
}
