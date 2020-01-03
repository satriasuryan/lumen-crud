<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $uker = Auth::user()->ukers()->get();
        return response()->json(['status' => 'success', 'result' => $uker]);
    }

    public function profile()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    public function listusers(Request $request)
    {
        if ($request->has('search')) {
            $search = \App\User::where('name', 'LIKE', '%' . $request->search . '%')->get();
        } else {
            $search = User::all();
        }
        return response()->json(['users' => $search], 200);
    }

    public function store(Request $request)
    {
        //validate incoming request 
        $this->validateRequest($request);

        try {

            $user = new User;
            $user->role = $request->input('role');
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

            $user->save();

            //return successful response
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json(['user' => $user], 200);
        } catch (\Exception $e) {

            return response()->json(['message' => 'user not found!'], 404);
        }
    }

    public function update(Request $request, $id)
    {

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => "The user with {$id} doesn't exist"], 404);
        }

        $this->validateRequest($request);

        $user->role = $request->input('role');
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $plainPassword = $request->input('password');
        $user->password = app('hash')->make($plainPassword);

        $user->save();

        return response()->json(['data' => "The user with id {$user->id} has been updated"], 200);
    }


    // public function update(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'role' => 'string',
    //         'name' => 'string',
    //         'email' => 'email|unique:users',
    //         'password' => 'confirmed',
    //     ]);
    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }
    //     $user = $this->user->query()->find($id);
    //     $user->role = $request->input('role');
    //     $user->name = $request->input('name');
    //     $user->email = $request->input('email');
    //     $plainPassword = $request->input('password');
    //     $user->password = app('hash')->make($plainPassword);
    //     $user->save();

    //     return response()->json(['data' => "The user with id {$user->id} has been updated"], 200);
    // }


    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => "The user with {$id} doesn't exist"], 404);
        }
        $user->delete();
        return response()->json(['data' => "The user with with id {$id} has been deleted"], 200);
    }

    public function validateRequest(Request $request)
    {

        $rules = [
            'role' => 'string',
            'name' => 'string',
            'email' => 'email|unique:users',
            'password' => 'confirmed',
        ];

        $this->validate($request, $rules);
    }
}
