<?php

namespace App\Http\Controllers;

use App\DataTables\TagsDataTable;
use App\Helpers\MyResponse;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;
use Yajra\DataTables\DataTables;

class TagController extends Controller
{
    use MyResponse;

    //
    /*    public function index(TagsDataTable $dataTable)
        {
            return $dataTable->render('tags');
        }*/
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Tag::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" class="edit btn btn-info btn-sm ml-2">View</a>';
                    $btn = $btn . '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm ml-2">Edit</a>';/*
                    $btn = $btn.'<a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-id="'.$row -> id.'">Delete</a>';*/
                    $btn = $btn . '<button data-toggle="modal" data-target="#modal-delete" class="delete btn btn-danger btn-sm ml-2" data-id="' . $row->id . '">Delete</button>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('tags');
    }

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|unique:tags',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->with(['error' => $validation->errors()]);
        }
        try {
            Tag::create(['name' => $request->name]);
            flash()->overlay('<div class="text-center">
					<i class="far fa-check-circle fa-5x mr-1 text-green"></i>
									<p class="mt-4 h5 " >Tag added successfully</p>

				</div>
				', ' ');

            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => "error Occurred"]);

        }
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $validation = Validator::make($request->all(), [
            'id' => 'required|exists:tags',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->with(['error' => $validation->errors()]);
        }
        $tag = Tag::find($id);
        if (!$tag) {
            return redirect()->back()->with(['error' => "tag does not exist"]);
        }
        try {
            $tag->delete();
            flash()->overlay('<div class="text-center">
					<i class="far fa-check-circle fa-5x mr-1 text-green"></i>
									<p class="mt-4 h5 " >Tag deleted successfully</p>

				</div>
				', ' ');

            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => "error Occurred"]);

        }
    }

    public function tags(Request $request)
    {
        $query = $request->get('query');
        if ($query) {
            $data = Tag::where("name", "like", "%" . $query . "%")->select("id", "name")->get();
        } else {
            $data = Tag::select("id", "name")->get();

        }
        return response()->json($data, 200);
    }

    public function top(Request $request){
        $data= Tag::query()->take(10)->get();
        return $this->returnDataResponse($data);

    }
}
