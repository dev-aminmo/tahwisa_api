<?php
namespace App\Http\Controllers;
use App\Helpers\MyResponse;
use App\Http\Resources\PlacesCollectionResource;
use App\Models\Place;
use App\Models\WishListItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class WishController extends Controller
{
    use MyResponse;

    public function index(Request $request){
        $user_id = $request->user()->id;
        $places_id = WishListItem::where('user_id', $user_id)->paginate(10);
        $data = new PlacesCollectionResource($places_id);
        return $this->returnDataResponse($data);
    }

    public function add(Request $request){
        $validation = Validator::make($request->all(), [
            'place_id'=> 'required',
        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 202);
        }
        try{
            $id= auth()->user()['id'];
            $allData=$request->all();
            $allData['user_id']=$id;
            WishListItem::create($allData);

          return  $this->returnSuccessResponse('place added to wishlist successfully');

       }catch (\Exception $e){
            return $this->returnErrorResponse();
        }

    }
    public function delete(Place $place){

        try{
            $userId= auth()->user()['id'];
            WishListItem::where('user_id',$userId)->where('place_id',$place->id)->delete();
            return  $this->returnSuccessResponse('place removed from wishlist successfully',200);

        }catch (\Exception $e){
            return $this->returnErrorResponse();
        }
    }
    public function all(Request $request){

        try{
        $userId=auth()->user()->id;
       $data= Place::whereHas('wishes',function($query)use($userId){
            $query->where('user_id',$userId);
        })->whereHas('pictures')->with(['pictures'=> function ($query){
           $query->select(//
               'path',
               'place_id'
           );
       }])->with(['tags'=>function($query){
           $query->select(['tag_id','name']);
       }])->withAvg('reviews','vote')->withCount('reviews')->with(['user'=>function($query){
           $query->select(['id','username','profile_picture']);
       }])->paginate(1);

            return $this->returnDataResponse($data);

        }catch (\Exception $e){
            return $this->returnErrorResponse();
        }

    }
}
