<?php

namespace App\Http\Controllers;

use App\Helpers\MyResponse;
use App\Models\Tag;
use Exception;
use Illuminate\Http\Request;
use App\Models\Place;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;


class PlaceController extends Controller
{
    use MyResponse;
    public function index(Place $place)
    {
        $place = Place::where('id', $place->id)->with(['pictures' => function ($query) {
            $query->select( //
                'path',
                'place_id'
            );
        }])->withAvg('reviews', 'vote')->withCount('reviews')->with(['tags' => function ($query) {
            $query->select(['tag_id', 'name']);
        }])->with(['user' => function ($query) {
            $query->select(['id', 'username', 'profile_picture']);
        }])->get();
        return $this->returnDataResponse($place);
    }
    public function addPlace(Request $request)
    {

        $validation = Validator::make(
            $request->all(),
            [
                'data' => 'required',
                'file' => 'required',
                'file.*' => 'required|mimes:jpg,jpeg,png,bmp|max:20000',
            ],
            [
                'file.*.required' => 'Please upload an image',
                'file.*.mimes' => 'Only jpeg,png and bmp images are allowed',
                'file.*.max' => 'Sorry! Maximum allowed size for an image is 20MB',
            ]
        );
        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }
        $jsonData = $request->get("data");
        if (!is_array($jsonData)) $jsonData = json_decode($request->get("data"), true);
        $validation = Validator::make($jsonData, [
            'title'  => 'required|string|max:191',
            'description' => 'nullable|string|max:2000',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'municipal_id' => 'required|numeric',
            "tags"=>'array',
            "tags.*.id"=>'integer|distinct|exists:tags,id',
            "tags.*.name"=>'string|distinct',
        ]);


        if ($validation->fails()) {
            return $this->returnValidationResponse($validation->errors());
        }


        try {
            $e = Place::add($jsonData, $request->file('file'));
            $message = ($e) ? ['place inserted successfully', $e] : 'place inserted successfully';
            return $this->returnSuccessResponse($message);
        } catch (Exception $exception) {
            return $this->returnErrorResponse($exception->getMessage());
        }
    }
    public function updatePlaceInfo(Request $request)
    {
        $id = Route::current()->parameter('id');
        $validation = Validator::make($request->all(), [
            'title'  => 'string',
            'description' => 'string',
            'latitude' => 'numeric',
            'longitude' => 'numeric',
            //'pictures'=>'array'
        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 202);
        }
        $allData = $request->all();
        try {
            Place::where('id', $id)->update($allData);
            $data = ['message' => 'place updated successfully', 'data' => $allData, 'response code' => 201];
            return Response()->json($data, 201);
        } catch (Exception $exception) {
            $response = [];
            echo $exception;
            $response['error'] = "an error has occured";
            return response()->json($response, 400);
        }
    }
    public function all(Request $request)
    {
        $places = Place::whereHas('pictures')->with(['pictures' => function ($query) {
            $query->select(
                'path',
                'place_id'
            );
        }])->with(['tags' => function ($query) {
            $query->select(['tag_id', 'name']);
        }])->withAvg('reviews', 'vote')->withCount('reviews')->with(['user' => function ($query) {
            $query->select(['id', 'username', 'profile_picture']);
        }])->paginate(10);
        return $this->returnDataResponse($places);
    }
    public function get(Request $request)
    {
        $id = Route::current()->parameter('id');
        try {
            $place = Place::where('id', $id)->with(['pictures'])->withAvg('reviews', 'vote')->withCount('reviews')->with(['user' => function ($query) {
                $query->select(
                    'username',
                    'profile_picture'
                );
            }])->get();
            if (count($place) == 0) {
                return response()->json(["error" => 401, "message" => "place doesn't exist"], 401);
            }
            return response()->json($place, 200);
        } catch (Exception $exception) {
            return response()->json(["error" => 401, "message" => "place doesn't exist"], 401);
        }
    }

    public function autocomplete(Request $request)
    {
        $keyword = $request->get('query');
        $places = Place::whereHas('pictures')->where(function ($query) use ($keyword) {
            $query->where('title', 'ilike', '%' . $keyword . '%');
            //  ->orWhere('description', 'ilike', '%' . $keyword . '%');
        })->select(['id', 'title', 'description'])->with(['tags' => function ($query) {
            $query->select(['tag_id', 'name']);
        }])->get()->append("model");
        $tags = Tag::where('name', 'ilike', '%' . $keyword . '%')->get()->append("model");
        $data =  $tags->toBase()->merge($places);

        return $this->returnDataResponse($data);
    }
    public function search(Request $request)
    {
        //TODO handle error change to ilike
        $keyword = $request->get('query');
        $query = null;
        if (request('tag')) {
            $query =   Place::whereHas('pictures')->where(function ($query) {
                $query->whereHas('tags', function ($query) {
                    $query->where('tag_id', request('tag'));
                });
            });
        } else {
            $query =   Place::whereHas('pictures')->where(function ($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%')
                    ->orWhereHas('tags', function ($query) use ($keyword) {
                        $query->where('name', 'like', '%' . $keyword . '%');
                    });
            });
        }
        if (request('rating_min')) {
            if (request('rating_min') > 0) {
                $query->whereHas('reviews',  function ($query) {
                    $query->where('vote', '>=', request('rating_min'));
                });
            }
        }
        if (request('rating_max')) {
            if (request('rating_max') < 5) {
                if (request('rating_min') > 0) {
                    $query->whereHas('reviews',  function ($query) {
                        $query->where('vote', '<=', request('rating_max'));
                    });
                } else {
                    $query->where(function ($query) {
                        $query->whereHas('reviews',  function ($query) {
                            $query->where('vote', '<=', request('rating_max'));
                        })->orWhereDoesntHave('reviews');
                    })->get();
                }
            }
        }


        if (request('municipal')) {
            $query->where('municipal_id', request('municipal'));
        } else if (request('state')) {
            $query->whereHas('municipal', function ($query) {
                $query->whereHas('state', function ($query) {
                    $query->where('id', request('state'));
                });
            });
        }
        $data =  $query->with(['pictures' => function ($query) {
            $query->select( //
                'path',
                'place_id'
            );
        }])->withAvg('reviews', 'vote')->withCount('reviews')->with(['tags' => function ($query) {
            $query->select(['tag_id', 'name']);
        }])->with(['user' => function ($query) {
            $query->select(['id', 'username', 'profile_picture']);
        }])->paginate(10);

        return $this->returnDataResponse($data);
    }
}
