<?php

namespace App\Http\Controllers;

use App\User;
use App\Filters\UserFilter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index(UserFilter $filter)
    {
        return User::filter($filter)->paginate();
    }

    public function store()
    {
        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:4',
        ]);

        User::create(request()->all());

        return response()->json([
            'message' => 'Successful created'
        ]);
    }

    public function update(User $user)
    {
        $this->validate(request(), [
            'name' => 'sometimes|required',
            'email' => ['sometimes', 'required', 'email', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->fill(request()->all())->save();

        return response()->json([
            'message' => 'Successful edited'
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'The user has been successfully deleted'
        ], Response::HTTP_ACCEPTED);
    }
}
