<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class UserController extends Controller
{
    public function viewUsers()
    {
        return view("admin.users.users");
    }

    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }

    public function getUserInfo(Request $request)
    {
        $userId = $request->input("user_id");

        $userExist = User::find($userId);

        if ($userExist) {
            return response()->json($userExist);
        }
    }

    public function addNewUserInfo(Request $request)
    {
        $name = $request->input("name");
        $username = $request->input("username");
        $password = bcrypt($request->input("password"));
        $type = $request->input("type");

        $log = new LogController();
        list($user_id, $user_type) = $log->startLog();

        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required',
            'username' => ['required', 'unique:users,username'],
            'password' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $users = new User();
        $users->name = ucwords($name);
        $users->username = $username;
        $users->password = $password;
        $users->type = $type;

        if ($users->save()) {
            $activity = "User ID [" . $users->id . "] is successfully created.";
            $log->endLog($user_id, $user_type, $activity);

            $response = true;
        } else {
            $response = false;
        }

        return response()->json($response);
    }

    public function updateUserInfo(Request $request)
    {
        $userId = $request->input("id");
        $name = $request->input("name");
        $username = $request->input("username");
        $type = $request->input("type");

        $log = new LogController();
        list($user_id, $user_type) = $log->startLog();

        $userExist = User::find($userId);

        if ($userExist) {
            $userExist->name = ucwords($name);
            $userExist->username = $username;
            $userExist->type = $type;

            if ($userExist->save()) {
                $activity = "User ID [" . $userId . "] information is updated.";
                $log->endLog($user_id, $user_type, $activity);

                $response = true;
            } else {
                $response = false;
            }

            return response()->json($response);
        }
    }

    public function changeUserPassword(Request $request)
    {
        $userId = $request->input("id");
        $newPass = bcrypt($request->input("new_pass"));

        $log = new LogController();
        list($user_id, $user_type) = $log->startLog();

        $user = User::find($userId);

        if ($user) {
            $user->password = $newPass;
            if ($user->save()) {
                $activity = "User ID [" . $userId . "] password is changed.";
                $log->endLog($user_id, $user_type, $activity);

                $response = true;
            } else {
                $response = false;
            }
        }

        return response()->json($response);
    }

    public function deleteUser(Request $request)
    {
        $userId = $request->input("id");

        $log = new LogController();
        list($user_id, $user_type) = $log->startLog();

        $user = User::find($userId);

        if ($user) {
            if ($user->delete()) {
                $activity = "User ID [" . $userId . "] is removed to database.";
                $log->endLog($user_id, $user_type, $activity);

                $response = true;
            } else {
                $response = false;
            }
        }

        return response()->json($response);
    }
}
