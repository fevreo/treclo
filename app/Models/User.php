<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Login a specified created user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $id = Auth::id();
            $user = User::find($id);
            $success['token'] = $user->createToken('TreClo')->accessToken;
            $success['name'] = $user->name;
            $success['id'] = $user->id;

            return response()->json(['success' => $success]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Register a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        $success['token'] = $user->createToken('TreClo')->accessToken;
        $success['name'] = $user->name;
        $success['id'] = $user->id;

        return response()->json(['success' => $success]);
    }

    /**
     * Get a Access Token.
     *
     * @return \Illuminate\Http\Response
     */
    public function accessToken()
    {
        return $this->hasMany('\App\Models\OauthAccessToken');
    }

    /**
     * Get a specified created user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDetails()
    {
        return response()->json(['success' => Auth::user()]);
    }
}
