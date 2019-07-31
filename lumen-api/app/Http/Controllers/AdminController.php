<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
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
      $admin = Admin::where('name', $request->name)->first();
      if($admin !== null) {
        if(Hash::check($request->password, $admin->password)){
             $apikey = base64_encode(str_random(40));
             Admin::where('name', $request->name)->update(['api_key' => "$apikey"]);
             return response()->json(['status' => 'success','api_key' => $apikey,'admin_id' => $admin->admin_id] );
         }else{
             return response()->json(['status' => 'fail', 'msg' => 'Wrong password']);
         }
      } else {
        return response()->json(['status' => 'fail' , 'msg' => 'Admin user does not exist']);
      }

   }

   public function create(Request $request)
   {
     $this->validate($request, [
       'name' => 'required',
       'password' => 'required'
      ]);

      $uniqadmin = Admin::where('name',$request->name)->count();
      if($uniqadmin > 0) {
        return response()->json(['status' => 'fail','msg' => "Admin exists!"],401);
      } else {
        $admin = new Admin;
        $admin->name = $request->name;
        $admin->password = Hash::make($request->password);
        $admin->save();
        return response()->json(['status' => 'success','msg' => "Admin created!"]);
      }

   }
}
?>
