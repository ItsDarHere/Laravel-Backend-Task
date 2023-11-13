<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\FeedBack;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $comment = Comment::with('feedback', 'user')->get();
            $json_data["data"] = $comment;
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

        $user = User::where('email', $request['user']['email'])->first();
        $feedbackId = $request->selectedFeedback['id'];

        try {

            $comment = new Comment;
            $comment->comment = $request->comments;
            $comment->user_id = $user->id;
            $comment->feedback_id = $feedbackId;
            $comment->save();
            return response()->json(["status" => "200"]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function readById(Comment $feedback, $id)
    {
        
        try {
            $data = Comment::with('feedback','user')->where(['feedback_id' => $id])->get();
            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
