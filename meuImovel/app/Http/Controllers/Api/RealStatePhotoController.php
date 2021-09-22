<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Models\RealStatePhoto;
use Illuminate\Http\Request;

class RealStatePhotoController extends Controller
{
    private  $realStatePhoto;

    public function __construct(RealStatePhoto $realStatePhoto)
    {
        $this->realStatePhoto = $realStatePhoto;
    }

    public function setThumb($photoId, $realStateId){
        try{
            $this->realStatePhoto
                ->where('real_state_id',$realStateId)
                ->where('is_thumb',true)->first();

            $photo = $this->realStatePhoto->find($photoId);

            if($photo->count())$photo->update(['is_thumb'=>false]);

            $photo = $this->realStatePhoto->find($photoId);
            $photo->update(['is_thumb' => true]);

            return response()->json(['msg'=>'Thumb atualizada'],201);

        }catch (\Exception $e){
            $message = new ApiMessages("An error occurred!",[$e->getMessage()]);
            return response()->json($message->getMessage(),400);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
