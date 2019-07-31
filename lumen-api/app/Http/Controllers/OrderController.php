<?php

namespace App\Http\Controllers;

use App\Orders;
use Illuminate\Http\Request;
use Auth;

class OrderController extends Controller
{

    public function __construct()
    {
      // $this->middleware('auth');
      $this->authid = Auth::user()->id;
    }


    public function showAllOrders()
    {
        $orderinfo = Orders::all();
        return response()->json(['status' => 'success','orderinfo' => $orderinfo]);
    }

    public function showUserOrders($id)
    {
        if ($id == $this->authid) {
          $orderinfo = Orders::where('user_id', $id)->get();
          if(sizeof($orderinfo) > 0) {
              return response()->json(['status' => 'success','orderinfo' => $orderinfo]);
          } else {
              return response()->json(['status' => 'fail','orderinfo' => "This user has no order"]);
          }
        } else {
          return response()->json(['status' => 'fail','msg' => "You are not allowed to check other users' order info"]);
        }
    }

    // public function showOneOrder($id)
    // {
    //     return response()->json(Orders::find($id));
    // }

    public function create(Request $request)
    {

      $this->validate($request, [
        'o_title' => 'required|max:20',
        'o_description' => 'required|max:255',
        'user_id' => 'required'
      ]);

      if ($request->user_id == $this->authid) {
        // $order = Orders::create($request->all());
        $order = new Orders;
        $order->o_title = $request->o_title;
        $order->o_description = $request->o_description;
        $order->o_status = "pending";
        $order->user_id = $request->user_id;
        $order->save();

        return response()->json(['status' => 'success','msg' => "Order created!"]);
      } else {
        return response()->json(['status' => 'fail','msg' => "You are not allowed to create new order under other users' name"]);
      }
    }

    // public function update($id, Request $request)
    // {
    //     $order = Orders::findOrFail($id);
    //     $order->update($request->all());
    //
    //     return response()->json($order, 200);
    // }

    public function updateOrderStatus($id, Request $request)
    {
      $order = Orders::where('order_id', $id)->get();
      if(sizeof($order) > 0) {
          Orders::where('order_id', $id)->update(['o_status'=> $request->status]);
          return response()->json(['status' => 'success','msg' => "Update order ".$id." successful"]);
      } else {
          return response()->json(['status' => 'fail','msg' => "No such order"]);
      }
    }

}
