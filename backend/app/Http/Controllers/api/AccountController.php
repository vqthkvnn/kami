<?php


namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Models\Account;
use App\Models\Comment;
use App\Models\CommentVote;
use App\Models\Post;
use App\Models\PostVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Psr7\str;

class AccountController extends Controller
{
    public function getBase()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false
            ], 403);
        }
        return response()->json([
            'success' => true,
            'user_avatar' => auth()->user()->user_avatar,
            'user_name' => auth()->user()->user_name,
            'user_full_name' => auth()->user()->user_full_name,
            'user_permission' => auth()->user()->user_permission
        ], 200);
    }

    public function delete()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false
            ], 403);
        }
        DB::beginTransaction();
        try {
            Account::where('user_name', auth()->user()->user_name)->delete();
            DB::commit();
            auth()->logout();
            return response()->json(['success' => true], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }

    }

    public function postOfUser()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false
            ], 403);
        }
        return response()->json([
            'success' => true,
            'data' => Post::with('postContent')->where('user_name', auth()->user()->user_name)->get()
        ], 200);
    }

    public function summary()
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false
            ], 403);
        }
        $data = [];
        $data['post_created'] = Post::where('user_name', auth()->user()->user_name)->count();
        $data['comment_created'] = Comment::where('user_name', auth()->user()->user_name)->count();
        $data['total_like_post'] = 0;
        $dataPost = Post::where('user_name', auth()->user()->user_name)->get();
        foreach ($dataPost as $item) {
            $data['total_like_post'] += PostVote::where('post_id', $item['post_id'])->count();
        }
        $data['total_like_comment'] = 0;
        $dataComment = Comment::where('user_name', auth()->user()->user_name)->get();
        foreach ($dataComment as $item) {
            $data['total_like_comment'] += CommentVote::where('comment_id', $item['comment_id'])->count();
        }
        $data['like_given'] = PostVote::where('user_name', auth()->user()->user_name)->count() +
            CommentVote::where('user_name', auth()->user()->user_name)->count();
        $data['top_comment'] = Comment::with('commentContent')
            ->where('user_name', auth()->user()->user_name)
            ->orderByDesc('comment_date')
            ->take(5)->get();
        $data['top_post'] = Post::with('postContent')
            ->where('user_name', auth()->user()->user_name)
            ->orderByDesc('post_date_create')
            ->take(5)->get();
        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

    public function activity(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false
            ], 403);
        }
        if (strcmp($request->get('key'), '1') == 0) {
            $dataTopics = Post::with('postTag', 'subject_short', 'postContent')
                ->where('user_name', auth()->user()->user_name)
                ->orderByDesc('post_date_create')->get();
//                ->paginate(10);
            return response()->json([
                'success' => true,
                'data_post' => $dataTopics,
                'user_avatar' => auth()->user()->user_avatar
            ], 200);
        } else if (strcmp($request->get('key'), '2') == 0) {
            $dataComments = Comment::with('commentContent')
                ->where('user_name', auth()->user()->user_name)
                ->orderByDesc('comment_date')->get();
//                ->paginate(10);
            return response()->json([
                'success' => true,
                'data_comment' => $dataComments,
                'user_avatar' => auth()->user()->user_avatar
            ], 200);
        } else if (strcmp($request->get('key'), '3') == 0) {
            $dataPost = PostVote::where('user_name', auth()->user()->user_name)
                ->get();
            $dataComment = CommentVote::where('user_name', auth()->user()->user_name)
                ->get();
            return response()->json([
                'success' => true,
                'data_post' => $dataPost,
                'data_comment' => $dataComment,
                'user_avatar' => auth()->user()->user_avatar
            ], 200);
        } else {
            $dataTopics = Post::with('postTag', 'subject_short', 'postContent')
                ->where('user_name', auth()->user()->user_name)
                ->orderByDesc('post_date_create')->get();
//                ->paginate(10);
            $dataComments = Comment::with('commentContent')
                ->where('user_name', auth()->user()->user_name)
                ->orderByDesc('comment_date')->get();
            return response()->json([
                'success' => true,
                'data_post' => $dataTopics,
                'data_comment' => $dataComments,
                'user_avatar' => auth()->user()->user_avatar
            ], 200);
        }
    }

    public function update(AccountRequest $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false
            ], 403);
        }
        $d = strtotime($request->get('user_birth'));
        Account::where('user_name', auth()->user()->user_name)
            ->update(['user_full_name' => $request->get('user_full_name'),
                'user_birth' => date('Y-m-d', $d)]);
        return response()->json([
            'success' => true,
        ], 200);
    }

    public function changePassword()
    {

    }
}
