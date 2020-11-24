<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentVote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\String\u;

class CommentVoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function store($id)
    {
        if (!auth()->check()){
            return response()->json([
                'message' => 'Error Permission'
            ], 403);
        }
        DB::beginTransaction();
        try {
            $model = new CommentVote();
            $dataComment = Comment::where('comment_id', $id)->first();
            if ($dataComment == null || $dataComment['comment_status'] != 1) {
                return response()->json([
                    'success' => false
                ], 404);
            } else {
                $dataCheck = CommentVote::where('comment_id', $id)
                    ->where('user_name', auth()->user()->user_name)->first();
                if ($dataCheck != null){
                    return response()->json([
                        'success' => true
                    ], 200);
                }
                $data = [];
                $data['user_name'] = auth()->user()->user_name;
                $data['comment_id'] = $id;
                $model->fill($data);
                $model->save();
                DB::commit();
                return response()->json([
                    'success' => true
                ], 200);
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $data = CommentVote::where('comment_id', $id)->count();
        return response()->json([
            'success' => true,
            'total' =>$data
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if (!auth()->check()){
            return response()->json([
                'message' => 'Error Permission'
            ], 403);
        }
        DB::beginTransaction();
        try {
            $dataComment = Comment::where('comment_id', $id)->first();
            if ($dataComment == null || $dataComment['comment_status'] != 1) {
                return response()->json([
                    'success' => false,
                ], 404);
            }
            $dataCommentVote = CommentVote::where('comment_id', $id)
                ->where('user_name', auth()->user()->user_name);
            if ($dataCommentVote == null) {
                return response()->json([
                    'success' => true,
                ], 200);
            } else {
                CommentVote::where('comment_id', $id)
                    ->where('user_name', auth()->user()->user_name)->delete();
                DB::commit();
                return response()->json([
                    'success' => true,
                ], 200);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
    public function isLike($id){
        if (!auth()->check()){
            return response()->json([
                'message' => 'Error Permission'
            ], 403);
        }else{
            $data = Comment::where('comment_id', $id)
                ->first();
            if ($data == null || $data['comment_status'] != 1){
                return response()->json([
                    'message' => 'Not found comment'
                ], 404);
            }else{
                $dataVote = CommentVote::where('comment_id', $id)->where('user_name', auth()->user()->user_name)
                    ->first();
                if ($dataVote == null){
                    return response()->json([
                        'success' => true,
                        'isLike'=>false
                    ], 200);
                }
                else{
                    return response()->json([
                        'success' => true,
                        'isLike'=>true
                    ], 200);
                }
            }
        }
    }
}
