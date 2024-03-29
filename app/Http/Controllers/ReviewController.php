<?php

namespace App\Http\Controllers;

use App\Helpers\MyResponse;
use App\Http\Resources\ReviewsCollectionResource;
use App\Http\Resources\ReviewsResource;
use App\Models\Place;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ReviewController extends Controller
{
    use MyResponse;
    public function index(Place $place)
    {
        $reviews = Review::where([['place_id', $place->id],['user_id','<>',auth()->user()->id]])->with('user')->orderBy('id', 'DESC')->paginate(10);
        $data = new ReviewsCollectionResource($reviews);
        return $this->returnDataResponse($data);
    }

    public function userReview(Place $place,Request $request)
    {
        $reviews = Review::where([['place_id','=', $place->id] , ['user_id','=', $request->user()->id]] )->with('user')->first();
        if(!$reviews)
            return $this->returnDataResponse(null);

        $data = new ReviewsResource($reviews);
       return $this->returnDataResponse($data);

    }

    function postReview(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'vote'  => 'required|numeric|min:0|max:5',
            'comment' => 'nullable|string',
            'place_id' => 'required',
        ]);
        if ($validation->fails())
            return response()->json($validation->errors(), 202);
        $user_id = $request->user()->id;
        $userReview = Review::where([['user_id', $user_id],['place_id', $request->place_id]])->first();
        $transactionResponse=null;
        if ($userReview){
            $reviewData = [
                'vote'  => $request->vote,
                'comment' => $request->comment,
                'place_id' => $request->place_id,
                'user_id' =>  $user_id,
                'updated_at' =>  Carbon::now(),
            ];
            $transactionResponse = DB::transaction(function () use ($reviewData,&$userReview) {
                $userReview->update($reviewData);
                return 'success';
            });

        }else {
            $reviewData = [
                'vote' => $request->vote,
                'comment' => $request->comment,
                'place_id' => $request->place_id,
                'user_id' => $user_id,
                'created_at' => Carbon::now(),
            ];
            $transactionResponse = DB::transaction(function () use ($reviewData) {
                Review::create($reviewData);
                return 'success';
            });
        }
        if ($transactionResponse == 'success')
            return $this->returnSuccessResponse( 'review inserted successfully');
        else
            return $this->returnErrorResponse('error, review not inserted',500);
    }

    function update(Request $request, Review $review)
    {
        $validation = Validator::make($request->all(), [
            'vote'  => 'required|numeric',
            'comment' => 'required|string'
        ]);
        if ($validation->fails())
            return response()->json($validation->errors(), 202);

        $reviewData = [
            'vote'  => $request->vote,
            'comment' => $request->comment,
            'updated_at' =>  Carbon::now(),
        ];

        $transactionResponse = DB::transaction(function () use ($reviewData, $review) {
            $review->update($reviewData);
            return 'succes';
        });

        if ($transactionResponse == 'succes')
            return $this->returnSuccessResponse( 'review updated successfully');

        else
            return $this->returnErrorResponse('error, review not updated',500);

    }

    public function delete(Review $review)
    {
        $status=null;
        if($review->user->id ==auth()->user()->id){
            $status = $review->delete();
        }
        if ($status == '1')

            return Response()->json(['message' => 'review deleted successfully'], 201);
        else
            return Response()->json(['message' => 'error, review not deleted'], 500);
    }
}
