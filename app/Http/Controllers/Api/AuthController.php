<?php

namespace App\Http\Controllers\Api;


use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\User;
use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Register New User.
     * @param App\Requests\RegisterRequest $request
     * @return JSONResponse
     *
     */
    public function register(RegisterRequest $request)
    {
        try {
            // Create a new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
            ]);

            if ($user) {
                return ResponseHelper::success(message: 'User has been registered successfully!', data: $user, statusCode: 201);
            }
            return ResponseHelper::error(message: 'Unable to register user! Please try again!', statusCode: 400);
        } catch (Exception $e) {
            // Log the error message
            Log::error('Unable to register user : ' . $e->getMessage() . ' - Line no.' . $e->getLine());
            // Return an error response
            return ResponseHelper::error(message: 'Unable to register user! Please try again!' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Function : Login user.
     * @param App\Requests\LoginRequest $request
     */
    public function login(LoginRequest $request)
    {
        try {
            //If credentials are incorrect
            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return ResponseHelper::error(message: 'Unable to login due to invalid credentials!', statusCode: 400);
            }

            $user = Auth::user();

            //Create API Token
            $token = $user->createToken('My API Token')->plainTextToken;
            $authUser = [
                'user' => $user,
                'token' => $token
            ];
            //Return success response with token
            return ResponseHelper::success(message: 'User has been successfully logged in!', data: $authUser, statusCode: 200);
        } catch (Exception $e) {
            // Log the error message
            Log::error('Unable to login user : ' . $e->getMessage() . ' - Line no.' . $e->getLine());
            // Return an error response
            return ResponseHelper::error(message: 'Unable to login user! Please try again!' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function : Auth user data / Profile data
     * @param NA
     * @return JSONResponse
     */
    public function userProfile()
    {
        try {
            $user = Auth::user();
            //Return success response with user data
            if ($user) {
                return ResponseHelper::success(message: 'User profile fetched successfully', data: $user, statusCode: 200);
            }
            return ResponseHelper::error(message: 'Unable to fetch user data due to invalid token!', statusCode: 400);
        } catch (Exception $e) {
            // Log the error message
            Log::error('Unable to fetch user profile : ' . $e->getMessage() . ' - Line no.' . $e->getLine());
            // Return an error response
            return ResponseHelper::error(message: 'Unable to fetch user profile' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function : User Logout
     * @param NA
     * @return JsonResponse
     */

    public function logoutUser()
    {
        try {
            $user = Auth::user();
            if ($user) {
                $user->currentAccessToken()->delete();
                return ResponseHelper::success(message: 'User logged out successfully!', statusCode: 200);
            }
            return ResponseHelper::success(message: 'Unable to logout user due to invalid token!', statusCode: 200);
        } catch (Exception $e) {
            // Log the error message
            Log::error('Unable to logout user due to some exception!: ' . $e->getMessage() . ' - Line no.' . $e->getLine());
            // Return an error response
            return ResponseHelper::error(message: 'Unable to logout user! Please try again!' . $e->getMessage(), statusCode: 500);
        }
    }
}
