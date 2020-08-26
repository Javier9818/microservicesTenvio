<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;


use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];




    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function payload(Request $request){
        try {
            //JWTAuth::setToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1ODQyMzk1NzQsImF1ZCI6IjQ5MjliOWU4ODAxZmI5MmYyZmM2MDQ5YThkMWVjYjY2ZWNhY2Y4MTEiLCJkYXRhIjp7ImlkcGVyc29uYSI6IjcwNTciLCJpZHRyYWJhamFkb3IiOiJFU0NVRUxBIEFDQURFTUlDTyBQUk9GRVNJT05BTCBERSBJTkdFTklFUklBIERFIFNJU1RFTUFTIiwibG9naW4iOiIxMDUzMzAwNzE4IiwicGVyX2NvZGlnbyI6IjEwNTMzMDA3MTgiLCJwZXJfcGF0ZXJubyI6IkFMVkFSQURPIiwicGVyX21hdGVybm8iOiJBUk1BUyIsInBlcl9ub21icmVzIjoiQ1JJU1RPUEhFUiBBTlRPTkkiLCJpZEVtcHJlc2EiOiIxIiwiaWRBcmVhIjoiMjMiLCJpZEVzdHJ1Y3R1cmEiOiIyMSIsImVzdHJfZGVzY3JpcGNpb24iOm51bGwsImlkc2VkZSI6IjEiLCJyb2wiOiJBbHVtbm8iLCJlbXByZXNhbmFtZSI6IlVOSVZFUlNJREFEIE5BQ0lPTkFMIERFIFRSVUpJTExPIiwic2lzdGVtYW5hbWUiOiJTR0EgVU5UIiwibG9nbyI6InVudC5naWYiLCJwZXJfY2FyZ28iOiI3MDU3IiwiaWR0cmFiIjoiMTA1MzMwMDcxOCIsImNvZGlnb3RyYWJhamFkb3IiOm51bGx9fQ.VHYYp0Tmehd5vajGDsVnCagEuB1xs1Glr7RLsZVcMWg');
            JWTAuth::parseToken();
            JWTAuth::authenticate();
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
           return false;
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
          return false;
        }catch (\Illuminate\Database\QueryException $e) {
            $data= JWTAuth::payload()['data'];
            return response()->json($data);
        }

    }
}
