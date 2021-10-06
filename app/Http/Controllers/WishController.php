<?php
namespace App\Http\Controllers;
use App\Helpers\MyResponse;
use App\Models\Place;
use App\Models\WishListItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class WishController extends Controller
{
    use MyResponse;
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

      //  try{
        $id=auth()->user()->id;

          $data=  WishListItem::where('user_id',$id)->with(['place'=>function($query){
              $query->whereHas('pictures')->with(['pictures'=> function ($query){
                  $query->select(//
                      'path',
                      'place_id'
                  );
              }])->with(['tags'=>function($query){
                  $query->select(['tag_id','name']);
              }])->withAvg('reviews','vote')->withCount('reviews')->with(['user'=>function($query){
                  $query->select(['id','username','profile_picture']);}]);
          }])->get()->pluck('place')->get()->paginate(10);
            $data = ['message' => 'success', 'data'=>$data];
            return Response()->json($data,200);

      //  }catch (\Exception $e){
       //     $response['error']="an error has occurred";
       //     return response()->json($response, 400);

       // }

    }
}
