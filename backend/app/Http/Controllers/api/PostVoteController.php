<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostVoteRequest;
use App\Models\Post;
use App\Models\PostVote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PostVoteController extends Controller
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
    public function create(Request $request)
    {

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
                'message' => 'Error Permission',
            ], 403);
        }
        $dataPost = Post::where('post_id', $id)->first();
        if ($dataPost == null) {
            return response()->json([
                'success' => false
            ], 404);
        } else {
            if ($dataPost['post_status'] != 1) {
                return response()->json([
                    'success' => false
                ], 404);
            } else {
                $data = PostVote::where('user_name', auth()->user()->user_name)->where('post_id',
                    $id)->first();
                if ($data == null) {
                    DB::beginTransaction();
                    try {
                        $data['user_name'] = auth()->user()->user_name;
                        $data['post_id'] = $id;
                        $model = new PostVote();
                        $model->fill($data);
                        $model->save();
                        DB::commit();
                        return response()->json([
                            'success' => true
                        ], 200);
                    } catch (\Exception $exception) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => $exception->getMessage()
                        ], 500);
                    }
                } else {
                    return response()->json([
                        'success' => true
                    ], 200);
                }

            }
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
        $data = Post::where('post_id', $id)->first();
        if ($data == null || $data['post_status'] != 1) {
            return response()->json(['success' => false],
                404);
        } else {
            $data = PostVote::where('post_id', $id)->count();
            return response()->json([
                'success' => true,
                'total' => $data
            ], 200);
        }
    }
    public function isLike($id){
        if (!auth()->check()){
            return response()->json([
                'message' => 'Error Permission',
            ], 403);
        }
        $data = Post::where('post_id', $id)->first();
        if ($data == null || $data['post_status'] != 1) {
            return response()->json(['success' => false],
                404);
        } else {
            $data = PostVote::where('post_id', $id)->where('user_name', auth()->user()->user_name)->first();
            if ($data != null){
                return response()->json([
                    'success' => true,
                    'isLike' => true
                ], 200);
            }
            return response()->json([
                'success' => true,
                'isLike' => false,
            ], 200);
        }
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
                'message' => 'Error Permission',
            ], 403);
        }
        $dataPost = Post::where('post_id', $id)->first();
        if ($dataPost == null || $dataPost['post_status']!=1) {
            return response()->json([
                'success' => false
            ], 404);
        } else {
            $data = PostVote::where('post_id', $id)->
            where('user_name', auth()->user()->user_name)->first();
            if ($data == null) {
                return response()->json([
                    'success' => false
                ], 400);
            } else {
                DB::beginTransaction();
                try {
                    PostVote::where('post_id', $id)->
                    where('user_name', auth()->user()->user_name)->delete();
                    DB::commit();
                    return response()->json([
                        'success' => true,
                    ], 200);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                    ], 500);
                }
            }
        }
    }
}
