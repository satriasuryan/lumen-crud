<?php

namespace App\Http\Controllers;

use App\User;
use App\Uker;
use App\Kanwil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UkerController extends Controller
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
    public function index()
    {
        return response()->json(['ukers' =>  Uker::all()], 200);
    }

    public function show($id)
    {
        try {
            $uker = Uker::findOrFail($id);

            return response()->json(['uker' => $uker], 200);
        } catch (\Exception $e) {

            return response()->json(['message' => 'uker not found!'], 404);
        }
    }

    public function store(Request $request)
    {
        //validate incoming request 
        $this->validateRequest($request);

        try {

            $uker = new Uker;
            $uker->name = $request->get('name');
            $uker->user_id = $request->get('user_id');
            $uker->kanwil_id = $request->get('kanwil_id');
            $uker->save();

            //return successful response
            return response()->json(['uker' => $uker, 'message' => 'CREATED'], 201);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'uker Registration Failed!'], 409);
        }
    }


    public function update(Request $request, $id)
    {

        $uker = Uker::find($id);

        if (!$uker) {
            return response()->json(['message' => "The uker with {$id} doesn't exist"], 404);
        }

        $this->validateRequest($request);
        $uker->name = $request->get('name');
        $uker->user_id = $request->get('user_id');
        $uker->kanwil_id = $request->get('kanwil_id');
        $uker->save();

        return response()->json(['data' => "The uker with id {$uker->id} has been updated"], 200);
    }


    public function destroy($id)
    {
        $uker = Uker::find($id);
        if (!$uker) {
            return response()->json(['message' => "The uker with {$id} doesn't exist"], 404);
        }
        $uker->delete();
        return response()->json(['data' => "The uker with with id {$id} has been deleted"], 200);
    }


    public function indexUkerKanwil($kanwil_id)
    {

        $kanwil = Kanwil::with('ukers')->where('id', $kanwil_id)->first();

        if (!$kanwil) {
            return response()->json(['message' => "The kanwil with id {$kanwil_id} doesn't exist"], 404);
        }

        $ukers = $kanwil->ukers;
        // return response()->json(['data' => $ukers], 200);
        return response()->json(['ukers' => $ukers], 200);
    }

    public function indexUkerUser($user_id)
    {

        $user = User::with('ukers')->where('id', $user_id)->first();

        if (!$user) {
            return response()->json(['message' => "The user with id {$user_id} doesn't exist"], 404);
        }

        $ukers = $user->ukers;
        // return $this->success($ukers, 200);
        return response()->json(['ukers' => $ukers], 200);
    }


    public function validateRequest(Request $request)
    {

        $rules = [
            'name' => 'string|required',
            'user_id' => 'integer|required',
            'kanwil_id' => 'integer|required'
        ];

        $this->validate($request, $rules);
    }
}
