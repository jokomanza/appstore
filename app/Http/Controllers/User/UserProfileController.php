<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Permission;
use App\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return Factory|Application|View
     */
    public function show(Request $request)
    {
        $user = $request->user();

        $applications = Permission::with('app')->where('user_registration_number', $user->registration_number)->get();

        return view('user.profile.show', compact('user', 'applications'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        return view('user.profile.edit', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function editPassword(Request $request)
    {
        return view('user.profile.password.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserPasswordRequest $request
     * @return RedirectResponse
     */
    public function updatePassword(UpdateUserPasswordRequest $request)
    {
        $user = $request->user();

        if (Hash::check($request->password, $user->password)) return back()->withErrors("Old password incorrect");

        $user->password = bcrypt($request->password);

        if ($user->update()) return redirect()->route('user.profile.show')->with('messages', ['Successfully update your password']);
        else return back()->withErrors('Failed to change password');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @return RedirectResponse
     */
    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();

        $user->fill($request->all());

        if ($user->update()) return redirect()->route('user.profile.show')->with('messages', ['Successfully update your data']);
        else return back()->withErrors('Failed to update profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return Application|Redirector|RedirectResponse
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        if (User::destroy($user->registration_number)) {
            Auth::guard('user')->logout();
            return redirect('/');
        } else return back()->withErrors('Failed to delete user account');
    }
}
