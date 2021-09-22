<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Models\RealStatePhoto;
use Illuminate\Support\Facades\Storage;

class RealStatePhotoController extends Controller
{
    private  $realStatePhoto;

    public function __construct(RealStatePhoto $realStatePhoto)
    {
        $this->realStatePhoto = $realStatePhoto;
    }

    public function setThumb($photoId, $realStateId){
        try{
//            $photo = $this->realStatePhoto
//                ->where('real_state_id',$realStateId)
//                ->where('is_thumb',true)->first();
//
//            if($photo->count())$photo->first()->update(['is_thumb'=>false]);

            $photo = $this->realStatePhoto->find($photoId);

            if(!$photo)
                throw new \Exception("Error to find photo with id $photoId!");

            $photo->update(['is_thumb' => !$photo->is_thumb]);
            return response()->json(['msg'=>'Thumb atualizada'],201);

        }catch (\Exception $e){
            $message = new ApiMessages("An error occurred!",[$e->getMessage()]);
            return response()->json($message->getMessage(),400);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $photoId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($photoId)
    {
        try{
            $photo = $this->realStatePhoto->find($photoId);

            if($photo) {
                if($photo->is_thumb)
                    throw new \Exception("Remove thumb is doesn't possible! Select another thumb and try again!");

                Storage::disk('public')->delete($photo->photo);
                $photo->delete();
            }

            return response()->json(['msg'=>'Photo removida com sucesso !!!'],201);

        }catch (\Exception $e){
            $message = new ApiMessages("An error occurred!",[$e->getMessage()]);
            return response()->json($message->getMessage(),400);
        }
    }
}
