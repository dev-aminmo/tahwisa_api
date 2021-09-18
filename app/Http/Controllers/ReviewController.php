<?php

namespace App\Http\Controllers;

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
    public function index(Place $place)
    {
        $reviews = Review::where('place_id', $place->id)->orderBy('id', 'DESC')->paginate(5);
        $data = new ReviewsCollectionResource($reviews);
        return Response()->json(['The list of reviews of this place' => $data], 200);
    }

    public function userReview(Place $place,Request $request)
    {
        $reviews = Review::where([['place_id','=', $place->id] , ['user_id','=', $request->user()->id]] )->first();
        if(!$reviews)
        return Response()->json(['User do not have a review yet'], 200);

        $data = new ReviewsResource($reviews);
        return Response()->json(['User review of this place' => $data], 200);
    }

    function create(Request $request)
    {
        $user_id = $request->user()->id;
        $userReview = Review::where('user_id', $user_id)->first();
        if ($userReview)
            return Response()->json(['message' => 'error, review already exist'], 422);

        $validation = Validator::make($request->all(), [
            'vote'  => 'required|numeric',
            'comment' => 'required|string',
            'place_id' => 'required',
        ]);
        if ($validation->fails())
            return response()->json($validation->errors(), 202);

        $reviewData = [
            'vote'  => $request->vote,
            'comment' => $request->comment,
            'place_id' => $request->place_id,
            'user_id' =>  $user_id,
            'created_at' =>  Carbon::now(),
        ];

        $transactionResponse = DB::transaction(function () use ($reviewData) {
            Review::create($reviewData);
            return 'succes';
        });

        if ($transactionResponse == 'succes')
            return Response()->json(['message' => 'review inserted successfully'], 200);
        else
            return Response()->json(['message' => 'error, review not inserted'], 500);
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
            return Response()->json(['message' => 'review updated successfully'], 200);
        else
            return Response()->json(['message' => 'error, review not updated'], 500);
    }

    public function delete(Review $review)
    {
        $status = $review->delete();
        if ($status == '1')
            return Response()->json(['message' => 'review deleted successfully'], 200);
        else
            return Response()->json(['message' => 'error, review not deleted'], 500);
    }
}
