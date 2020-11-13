<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\PostContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $sizePage = 10;
        $pagination = Post::with(['postTag', 'subject_short','postContent'])
            ->paginate($sizePage);
        $pagination->setPath('');
        $data = $pagination->getCollection();
        $data->each(function ($item) {
            $item->setHidden(['post_date_delete','post_status','post_note']);
        });
        $pagination->setCollection($data);
        return response()->json([
            'success' => true,
            'data' => $pagination->items(),
            'paging' => [
                'current_page' => $pagination->currentPage(),
                'per_page' => $pagination->perPage(),
                'total' => $pagination->total(),
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
        DB::beginTransaction();

        try {
            $postModel = new Post();
            $postData = [];
            $postData['user_name'] = 'admin';
            $postData['post_status'] = 1;
            $postData['subject_id'] = $request->get('subject_id');
            $postModel->fill($postData);
            $postModel->save();
            $contentModel = new PostContent();
            $contentData = [];
            $contentData['post_id'] = $postModel->getAttribute('id');
            $contentData['post_content_id'] = 1;
            $contentData['post_content_title'] = $request->get('post_content_title');
            $contentData['post_content_main'] = $request->get('post_content_main');
            $contentData['user_name'] = 'admin';
            $contentModel->fill($contentData);
            $contentModel->save();
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
        if($data == null || $data['post_status'] !=1){
            return response()->json([
                'success' => false
            ], 404);
        }
        else{
            $data = Post::with('postContent')
                ->where('post_id', $id)
                ->first()
                ->setHidden(['post_date_delete','post_status','post_note']);

            return response()->json([
                'success' => true,
                'data'=>$data
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
        DB::beginTransaction();
        try{
            $owned = Post::where('post_id','=', $id);
            if ($owned == null){
                return response()->json([
                    'success'=>false
                ], 404);
            }
            // quyền cho phép tài khoản sở hữu bài viết
            if ($owned['user_name'] == 'admin'){
                $contentData = [];
                $contentModel = new PostContent();
                $contentData['post_id'] = $id;
                $dataLast = PostContent::where('post_id', $id)->orderByDes('post_content_id')->fist();
                $contentData['post_content_id'] = $dataLast['post_content_id']+1;
                if ($request->get('post_content_title') == null){
                       $contentData['post_content_title'] = $dataLast['post_content_title'];
                }
                else{
                    $contentData['post_content_title'] = $request->get('post_content_title');
                }
                if ($request->get('post_content_main') == null){
                    $contentData['post_content_main'] = $dataLast['post_content_main'];
                }else{
                    $contentData['post_content_main'] = $request->get('post_content_main');
                }
                $contentModel->fill($contentData);
                $contentModel->save();
                return response()->json([
                    'success'=>true
                ], 200);
            }
            else{
                return response()->json([
                    'success'=>false
                ],403);
            }
            $contentData = [];
        }catch (\Exception $exception){
            return response()->json([
                'success'=>false
            ],500);
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
        DB::beginTransaction();
        try {
            PostContent::where('post_id', $id)->delete();
            Post::where('post_id', $id)->delete();
            DB::commit();
        }
        catch (\Exception $exception){
            DB::rollBack();
            return response()->json([
                'success'=>false
            ],500);
        }

    }
}
