<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAll(){
        try 
        {
          $users = User::get();
            return response()->json([
                'data' => $users
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function delete($id){
        try 
        {
          
            $user = User::findOrFail($id);

            $user->delete();
            $users = User::get();
            return response()->json([
                'data' => $users
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        } 
       
    }

}
