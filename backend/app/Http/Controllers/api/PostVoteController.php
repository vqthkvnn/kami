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
    public function create(PostVoteRequest $request)
    {
        $data = PostVote::where('user_name', 'admin')->first();
        if ($data == null){
            $data['user_name']= 'admin';
            $data['post_id'] = $request->get('post_id');
            $model = new PostVote();
            $model->fill($data);
            $model->save();
            return response()->json([], 200);
        }
        else{
            return response()->json([], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $data = Post::where('post_id', $id)->first();
        if($data == null || $data['post_status'] != 1){
            return response()->json(['success' => false],404);
        }
        else{
            $data = PostVote::where('post_id', $id)->count();
            return response()->json([
                'success' => false,
                'total'=>$data
            ],200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $data = PostVote::where('post_id', $id)->where('user_name', 'admin')->first();
        if ($data == null){
            $data['post_id'] = $id;
            $data['user_name'] = 'admin';
            $model = new PostVote();
            $model->fill($data);
            $model->save();
            return response()->json([
                'data'=>$data,
                'success' => true
            ], 200);
        }
        else{
            DB::beginTransaction();
            $model = new PostVote();
            $model->fill($data);
            try {
                $model->delete();
                DB::commit();
                return response()->json([
                    'success' => true,
                    'data'=>$data
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'data'=>$data
                ], 400);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        //
    }
}
