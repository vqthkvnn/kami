<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Account;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostContent;
use App\Models\PostTag;
use App\Models\PostVote;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $sizePage = 10;
        $pagination = null;
        if ($request->get('q') == null && $request->get('subject') == null) {
            $pagination = Post::with(['postTag', 'subject_short', 'postContent'])
                ->where('post_status', '=', 1)
                ->orderByDesc('post_id')
                ->paginate($sizePage);
        } else {
            if($request->get('q') != null && $request->get('subject') != null){
                $search = $request->get('q');
                $subject = $request->get('subject');
                $pagination = Post::with(['postTag', 'subject_short', 'postContent'])
                    ->where('post_status', '=', 1)
                    ->whereHas('subject_short', function ($query) use ($subject){
                        return $query->where('subject_id', $subject);
                    })
                    ->whereHas('postContent', function ($query) use ($search) {
                        return $query->where('post_content_title', 'LIKE', '%' . $search . '%');
                    })
                    ->orderByDesc('post_id')
                    ->paginate($sizePage);
            }
            else if ($request->get('q') != null){
                $search = $request->get('q');
                $pagination = Post::with(['postTag', 'subject_short', 'postContent'])
                    ->where('post_status', '=', 1)
                    ->whereHas('postContent', function ($query) use ($search) {
                        return $query->where('post_content_title', 'LIKE', '%' . $search . '%');
                    })
                    ->orderByDesc('post_id')
                    ->paginate($sizePage);
            }
            else{
                $subject = $request->get('subject');
                $pagination = Post::with(['postTag', 'subject_short', 'postContent'])
                    ->where('post_status', '=', 1)
                    ->whereHas('subject_short', function ($query) use ($subject) {
                        return $query->where('subject_id', '=', $subject);
                    })
                    ->orderByDesc('post_id')
                    ->paginate($sizePage);
            }

        }
        $pagination->setPath('');
        $data = $pagination->getCollection();
        $data->each(function ($item) {
            $item->setHidden(['post_date_delete', 'post_status', 'post_note']);
            $item['total_comment'] = Comment::where('post_id', $item['post_id'])->count();
            $item['total_like'] = PostVote::where('post_id', $item['post_id'])->count();
            $commentLast = Comment::where('post_id', $item['post_id'])
                ->orderByDesc('comment_id')->first();
            if ($commentLast == null) {
                $item['last_activity'] = (int)((time() - strtotime($item['post_date_create'])) / (60 * 60));
            } else {
                $item['last_activity'] = (int)((time() - strtotime($commentLast['comment_date'])) / (60 * 60));

            }

        });
        $pagination->setCollection($data);
        return response()->json([
            'success' => true,
            'data' => $pagination->items(),
            'paging' => [
                'current_page' => $pagination->currentPage(),
                'per_page' => $pagination->perPage(),
                'total' => ((int)($pagination->total() / $pagination->perPage())) + 1,
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostRequest $request
     * @return JsonResponse
     */
    public function store(PostRequest $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false
            ], 403);
        }
        DB::beginTransaction();
        try {
            $postModel = new Post();
            $postData = [];
            $postData['user_name'] = auth()->user()->user_name;
            $postData['post_status'] = 1;
            $postData['subject_id'] = $request->get('subject_id');
            $postModel->fill($postData);
            $postModel->save();
            $contentModel = new PostContent();
            $contentData = [];
            $contentData['post_id'] = $postModel->getAttribute('post_id');
            $contentData['post_content_id'] = 1;
            $contentData['post_content_title'] = $request->get('post_title');
            $contentData['post_content_main'] = $request->get('post_content');
            $contentData['user_name'] = auth()->user()->user_name;
            $contentModel->fill($contentData);
            $contentModel->save();
            // add tag
            $dataTag = $request->get('tag');
            foreach ($dataTag as $data){
                $tagModel = new PostTag();
                $tagData = [];
                $tagData['post_id'] = $postModel->getAttribute('post_id');
                $tagData['tag_id'] = $data['value'];
                $tagModel->fill($tagData);
                $tagModel->save();
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $postModel
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return response()->json(['error' => 'Server error',
                'request' => $request->all(),
                'log' => $e->getMessage()],
                500
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $data = Post::where('post_id', $id)->first();
        if ($data == null || $data['post_status'] != 1) {
            return response()->json([
                'success' => false
            ], 404);
        } else {
            $data = Post::with(['postContentFull', 'subject_short', 'postTag'])
                ->where('post_id', $id)
                ->first()
                ->setHidden(['post_date_delete', 'post_status', 'post_note']);
            $dataPermission = Account::where('user_name', $data['user_name'])->first();
            $data['user_avatar'] = $dataPermission['user_avatar'];
            $data['user_permission'] = $dataPermission['user_permission'];
            if (auth()->check() && strcmp($data['user_name'], auth()->user()->user_name) == 0){
                $data['owner'] = true;
            }
            else{
                $data['owner'] = false;
            }
            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
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
            $owned = Post::where('post_id', '=', $id);
            if ($owned == null) {
                return response()->json([
                    'success' => false
                ], 404);
            } else {
                if ($owned['post_status'] != 1) {
                    return response()->json([
                        'success' => false
                    ], 404);
                }
                if (strcmp($owned['user_name'], auth()->user()->user_name) == 0
                    || auth()->user()->user_permission) {
                    $contentData = [];
                    $contentModel = new PostContent();
                    $contentData['post_id'] = $id;
                    $dataLast = PostContent::where('post_id', $id)->orderByDes('post_content_id')->fist();
                    $contentData['post_content_id'] = $dataLast['post_content_id'] + 1;
                    if ($request->get('post_content_title') == null) {
                        $contentData['post_content_title'] = $dataLast['post_content_title'];
                    } else {
                        $contentData['post_content_title'] = $request->get('post_content_title');
                    }
                    if ($request->get('post_content_main') == null) {
                        $contentData['post_content_main'] = $dataLast['post_content_main'];
                    } else {
                        $contentData['post_content_main'] = $request->get('post_content_main');
                    }
                    $contentData['user_name'] = Auth::user()->user_name;
                    $contentModel->fill($contentData);
                    $contentModel->save();
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
                'success' => false
            ], 500);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false
            ], 403);
        }
        DB::beginTransaction();
        try {
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
                    if (strcmp($dataPost['user_name'], auth()->user()->user_name) == 0) {
                        Post::where('post_id', $id)->update(array('post_status' => -1));
                        DB::commit();
                        return response()->json([
                            'success' => true
                        ], 200);
                    } elseif (auth()->user->user_permission != 0) {
                        Post::where('post_id', $id)->update(array('post_status' => -2));
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
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'success' => false
            ], 500);
        }

    }
}
