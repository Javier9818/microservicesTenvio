<?php
namespace App\Http\Controllers;

    use App\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));
    }
    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                    return response()->json(['token_expired'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                    return response()->json(['token_invalid'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                    return response()->json(['token_absent'], $e->getStatusCode());
            }
            return response()->json(compact('user'));
    }

    public function getTokenData(){
        try {
            JWTAuth::setToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1ODQyMzM2MTgsImF1ZCI6IjQ5MjliOWU4ODAxZmI5MmYyZmM2MDQ5YThkMWVjYjY2ZWNhY2Y4MTEiLCJkYXRhIjp7ImlkcGVyc29uYSI6IjcwNTciLCJpZHRyYWJhamFkb3IiOiJFU0NVRUxBIEFDQURFTUlDTyBQUk9GRVNJT05BTCBERSBJTkdFTklFUklBIERFIFNJU1RFTUFTIiwibG9naW4iOiIxMDUzMzAwNzE4IiwicGVyX2NvZGlnbyI6IjEwNTMzMDA3MTgiLCJwZXJfcGF0ZXJubyI6IkFMVkFSQURPIiwicGVyX21hdGVybm8iOiJBUk1BUyIsInBlcl9ub21icmVzIjoiQ1JJU1RPUEhFUiBBTlRPTkkiLCJpZEVtcHJlc2EiOiIxIiwiaWRBcmVhIjoiMjMiLCJpZEVzdHJ1Y3R1cmEiOiIyMSIsImVzdHJfZGVzY3JpcGNpb24iOm51bGwsImlkc2VkZSI6IjEiLCJyb2wiOiJBbHVtbm8iLCJlbXByZXNhbmFtZSI6IlVOSVZFUlNJREFEIE5BQ0lPTkFMIERFIFRSVUpJTExPIiwic2lzdGVtYW5hbWUiOiJTR0EgVU5UIiwibG9nbyI6InVudC5naWYiLCJwZXJfY2FyZ28iOiI3MDU3IiwiaWR0cmFiIjoiMTA1MzMwMDcxOCIsImNvZGlnb3RyYWJhamFkb3IiOm51bGx9fQ.YsZPlLY1s6Yos10LaG3oGRSnRyC-_Lvm2gxKnFQuPgc');
            JWTAuth::authenticate();
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
           return 'El token ha expirado';
        }
        catch (\Illuminate\Database\QueryException $e) {
            $data= JWTAuth::payload()['data'];
            return $data->idpersona;
        }
    }
}
