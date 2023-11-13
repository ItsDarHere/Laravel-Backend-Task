<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\FeedBack;
use App\Models\User;
use Illuminate\Http\Request;

class FeedBackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function read()
    {
        try {

            $feedback = Feedback::with('user')->get();
            $json_data["data"] = $feedback;
            return json_encode($json_data);

            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Find the user based on the provided email
        $user = User::where('email', $request['user']['email'])->first();
    
        try {
            // Check if the user has already provided feedback for the same item
            $existingFeedback = Feedback::where('user_id', $user->id)
                ->where('items', $request->item)
                ->first();
    
            if ($existingFeedback) {
                // If feedback for the same item exists. Return an error response
                return response()->json([
                    'status' => false,
                    'message' => 'Feedback for this item already exists for the user.'
                ], 400);
            }
    
            // If no existing feedback, proceed to save the new feedback
            $feedback = new Feedback;
            $feedback->title = $request->title;
            $feedback->description = $request->description;
            $feedback->category = $request->category;
            $feedback->vote_count = $request->vote;
            $feedback->user_id = $user->id;
            $feedback->items = $request->item;
            $feedback->save();
    
            return response()->json(["status" => "200"]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(FeedBack $feedBack)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeedBack $feedBack)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeedBack $feedBack)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id){
        try 
        {
          
            $feedBack = FeedBack::findOrFail($id);
            $feedBack->delete();
            $comments = Comment::where('feedback_id', $id)->get();

            foreach ($comments as $comment) {
                $comment->delete();
            }
            $feedBack = FeedBack::get();
            return response()->json([
                'data' => $feedBack
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        } 
       
    }
}
