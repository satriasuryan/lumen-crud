<?php

namespace App\Http\Controllers;

use App\Kanwil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KanwilController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $search = Kanwil::where('name', 'LIKE', '%' . $request->search . '%')->get();
        } else {
            $search = Kanwil::all();
        }
        return response()->json(['kanwils' => $search], 200);
    }

    public function show($id)
    {
        try {
            $kanwil = Kanwil::findOrFail($id);

            return response()->json(['kanwil' => $kanwil], 200);
        } catch (\Exception $e) {

            return response()->json(['message' => 'kanwil not found!'], 404);
        }
    }

    public function store(Request $request)
    {
        //validate incoming request 
        $this->validateRequest($request);

        try {

            $kanwil = new Kanwil;
            $kanwil->name = $request->get('name');
            $kanwil->save();

            //return successful response
            return response()->json(['kanwil' => $kanwil, 'message' => 'CREATED'], 201);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'kanwil Registration Failed!'], 409);
        }
    }


    public function update(Request $request, $id)
    {

        $kanwil = Kanwil::find($id);

        if (!$kanwil) {
            return response()->json(['message' => "The kanwil with {$id} doesn't exist"], 404);
        }

        $this->validateRequest($request);
        $kanwil->name = $request->get('name');

        $kanwil->save();

        return response()->json(['data' => "The kanwil with id {$kanwil->id} has been updated"], 200);
    }


    public function destroy($id)
    {
        $kanwil = Kanwil::find($id);
        if (!$kanwil) {
            return response()->json(['message' => "The kanwil with {$id} doesn't exist"], 404);
        }
        $kanwil->delete();
        return response()->json(['data' => "The kanwil with with id {$id} has been deleted"], 200);
    }


    public function validateRequest(Request $request)
    {

        $rules = [
            'name' => 'string|unique:kanwils'
        ];

        $this->validate($request, $rules);
    }
}
