<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Account;
use App\Models\Comment;
use App\Models\CommentContent;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $sizePage = 10;
        $data = Comment::with('commentContent')
            ->where('post_id', $request->get('postId'))->where('comment_status', '=',1)->paginate($sizePage);
        $data->setPath('');
        $pagination = $data->getCollection();
        $pagination->each(function ($item) {
            $account = Account::where('user_name', $item['user_name'])->first();
            if ($account == null){
                $item['user_avatar'] = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT752i55xyNzb2OW5UPeHHEK92OgYtdm-Ykeg&usqp=CAU';
                $item['user_permission'] = 0;
                $item['owner'] = false;
            }
            else{
                $item['user_avatar'] = $account['user_avatar'];
                $item['user_permission'] = $account['user_permission'];
                if (auth()->check() && strcmp($item['user_name'], auth()->user()->user_name) == 0){
                    $item['owner'] = true;
                }
                else{
                    $item['owner'] = false;
                }
            }
            if ($account)
            $item->setHidden(['comment_date_delete', 'comment_note', 'comment_status']);
        });
        $data->setCollection($pagination);
        return response()->json([
            'success' => true,
            'data' => $data->items(),
            'paging' => [
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'total' => ((int)($data->total() / $data->perPage())) + 1,
            ]
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create(CommentRequest $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CommentRequest $request
     * @return JsonResponse
     */
    public function store(CommentRequest $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false
            ], 403);
        }
        DB::beginTransaction();
        $commentModel = new Comment();
        $commentData = [];
        $contentModel = new CommentContent();
        $contentData = [];
        try {
            $dataPost = Post::where('post_id', $request->get('post_id'))->first();
            if ($dataPost == null || $dataPost['post_status'] != 1){
                return response()->json([
                    'success' => false
                ], 404);
            }
            $commentData['post_id'] = $request->get('post_id');
            $commentData['user_name'] = auth()->user()->user_name;
            if ($request->get('comment_quote_post') != null && $request->get('comment_quote_post') != null){
                return response()->json([
                    'success' => false
                ], 501);
            }
            else if ($request->get('comment_quote_post') != null || $request->get('comment_quote_post') != null){
                $commentData['comment_quote_post'] = ($request->get('comment_quote_post'));
                $commentData['comment_quote_comment'] = ($request->get('comment_quote_comment'));
            }
            $commentData['comment_status'] = 1;
            $commentModel->fill($commentData);
            $commentModel->save();
            $contentData['comment_id'] = $commentModel->getAttribute('comment_id');
            $contentData['comment_content_id'] = 1;
            $contentData['comment_content_main'] = $request->get('comment_content');
            $contentModel->fill($contentData);
            $contentModel->save();
            if(strcmp(auth()->user()->user_name,$dataPost['user_name'])!=0){
                $model = new Notification();
                $dataNotification = [];
                $dataNotification['notification_content'] = auth()->user()->user_name .
                    " has comment to post has id " . $commentModel->getAttribute('user_name');
                $dataNotification['notification_type'] = 1;
                $dataNotification['user_name'] = auth()->user()->user_name;
                $model->fill($dataNotification);
                $model->save();
            }
            DB::commit();
            return response()->json(['success' => true,'data'=>$contentModel], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message'=>$exception->getMessage(),

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
        $sizePage = 10;
        $data = Comment::with('commentContent')
            ->where('post_id', $id)->paginate($sizePage);
        return response()->json([
            'success' => true,
            'data' => $data
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
        if (!auth()->check()) {
            return response()->json([
                'success' => false
            ], 403);
        }
        DB::beginTransaction();
        try {
            $dataComment = Comment::where('comment_id', $id)->first();
            if ($dataComment == null || $dataComment['comment_status']!= 1){
                return response()->json([
                    'success' => false
                ], 404);
            }
            else{
                if (strcmp($dataComment['user_name'], auth()->user()->user_name)==0 ||
                    auth()->user()->user_permission !=0){
                    $dataOld = CommentContent::where('comment_id', $id)
                        ->orderByDesc('comment_content_id')->first();
                    $dataNew = [];
                    $dataNew['comment_id'] = $id;
                    $dataNew['comment_content_main'] = $request->get('comment_content');
                    $dataNew['comment_content_id'] = $dataOld['comment_content_id'] + 1;
                    $model = new CommentContent();
                    $model->fill($dataNew);
                    $model->save($dataNew);
                    DB::commit();
                    return response()->json([
                        'success' => true
                    ], 200);
                }
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message'=>$exception->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $data = Comment::where('comment_id', $id)->first();
            if ($data == null) {
                return response()->json([
                    'success' => false,
                    'message' => "Not Found Comment"
                ], 404);
            } else {
                if (strcmp($data['user_name'], auth()->user()->user_name) == 0) {
                    Comment::where('comment_id', $id)->update(array('comment_status' => -1));
                    DB::commit();
                    return response()->json([
                        'success' => true
                    ], 200);
                } elseif (auth()->user()->user_permission != 0) {
                    Comment::where('comment_id', $id)->update(array('comment_status' => -2));
                    DB::commit();
                    return response()->json([
                        'success' => true
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false
                    ], 403);
                }
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    public function checkHave($id)
    {
        $data = Comment::where('comment_id', $id)->first();
        if (!Auth::check() || $data == null || strcmp($data['user_name'], Auth::user()->user_name) != 0) {
            return false;
        }
        if (strcmp($data['user_name'], Auth::user()->user_name) == 0 ||
            Auth::user()->user_permission == 1 || Auth::user()->user_permission == 2) {
            return true;
        }
    }
}
