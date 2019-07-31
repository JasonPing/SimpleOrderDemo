<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
  public function __construct()
   {
     //  $this->middleware('auth:api');
   }
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function authenticate(Request $request)
   {
      $this->validate($request, [
       'name' => 'required',
       'password' => 'required'
      ]);
      $user = User::where('name', $request->name)->first();
      if($user !== null) {
        if(Hash::check($request->password, $user->password)){
             $apikey = base64_encode(str_random(40));
             User::where('name', $request->name)->update(['api_key' => "$apikey"]);
             return response()->json(['status' => 'success','api_key' => $apikey,'user_id' => $user->id] );
         }else{
             return response()->json(['status' => 'fail', 'msg' => 'Wrong password']);
         }
      } else {
        return response()->json(['status' => 'fail' , 'msg' => 'User does not exist']);
      }

   }

   public function checkApiKey(Request $request)
   {
     $this->validate($request, [
      'user_id' => 'required',
      'api_key' => 'required'
     ]);
     $exist = User::where('id', $request->user_id)->where('api_key',  $request->api_key)->count();
     if($exist > 0) {
       return response()->json(['status' => 'success','msg' => 'validated user']);
     } else {
       return response()->json(['status' => 'fail','msg' => 'not validated user']);
     }
   }

   public function create(Request $request)
   {
     $this->validate($request, [
       'name' => 'required',
       'password' => 'required'
      ]);

      $uniquser = User::where('name',$request->name)->count();
      if($uniquser > 0) {
        return response()->json(['status' => 'fail','msg' => "user exists!"],401);
      } else {
        $user = new User;
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['status' => 'success','msg' => "user created!"]);
      }

   }

   public function showAllUsers()
   {
       $userinfo = User::select('id','name')->get();
       return response()->json(['status' => 'success','userinfo' => $userinfo]);
   }
}
?>
