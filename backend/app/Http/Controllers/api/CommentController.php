<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\CommentContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = Comment::with('commentContent')
            ->where('post_id', $request->get('postId'))
            ->where('comment_status', 1)->get()
            ->makeHidden(['comment_date_delete','comment_note','comment_status', 'comment_id']);
        return response()->json([
            'success'=>true,
            'data'=>$data
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create(CommentRequest $request)
    {
        DB::beginTransaction();
        $commentModel = new Comment();
        $commentData = [];
        $contentModel =  new CommentContent();
        $contentData = [];
        try {

            $commentData['post_id'] = $request->get('post_id');
            $commentData['user_name'] = 'admin';
            $commentData['comment_quote_post'] = $request->get('comment_quote_post');
            $commentData['comment_quote_comment'] = $request->get('comment_quote_comment');
            $commentData['comment_status'] = 1;
            $commentModel->fill($commentData);
            $commentModel->save();
            $contentData['comment_id'] = $commentModel->getAttribute('id');
            $contentData['comment_content_id'] = 1;
            $commentData['comment_content_main'] = $request->get('comment_content_main');
            $contentModel->fill($contentData);
            $contentModel->save();
            DB::commit();
            return response()->json(['success'=>true],200);
        }
        catch (\Exception $exception){
            DB::rollBack();
            return response()->json([
                'success'=>false,
                'data'=>$contentModel,
                'data2'=>$commentModel
            ],500);
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
        $data = Comment::with('commentContent')
            ->where('post_id', $id)->get();
        return response()->json([
            'success'=>true,
            'data'=>$data
        ],200);
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
        DB::beginTransaction();
        try {
            $dataOld = CommentContent::where('comment_id', $id)
                ->orderBy('comment_content_id')->first();
            $dataNew = [];
            $dataNew['comment_id'] = $id;
            $dataNew['comment_content_main'] = $request->get('comment_content_main');
            $dataNew['comment_content_id'] = $dataOld['comment_content_id']+1;
            $model = new CommentContent();
            $model->fill($dataNew);
            $model->save($dataNew);
            DB::commit();
            return response()->json([
                'success'=>true
            ], 200);
        }
        catch (\Exception $exception){
            DB::rollBack();
            return response()->json([
                'success'=>false
            ], 400);
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
        DB::beginTransaction();
        try {
            $data  = CommentContent::where('comment_id', $id)->delete();
            if ($data == null){

            }
            else{

            }
            DB::commit();
        }
        catch (\Exception $exception){
            DB::rollBack();
        }
    }
}
