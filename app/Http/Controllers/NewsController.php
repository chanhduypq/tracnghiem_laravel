<?php

namespace JP_COMMUNITY\Http\Controllers;

use Dawson\Youtube\Facades\Youtube;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use JP_COMMUNITY\Models\Comment;
use JP_COMMUNITY\Models\Like;
use JP_COMMUNITY\Models\User;
use JP_COMMUNITY\Models\News;

class NewsController extends BaseController
{
    protected $checkbox = ['draft'];
    protected $redirectTo = 'news';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id = null)
    {

        $isOwner = empty($user_id) ? true : ($user_id == Auth::id()) ? true : false;

        if (!empty($user_id)) {
            $news = News::where('user_id', $user_id)->with('user')->get();
        } else {
            $news = News::where('user_id', Auth::id())->with('user')->get();
        }

        return view('news.index', compact(['isOwner', 'news']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('news.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request = $this->prepareFormInput($request);

        $this->validate($request, [
            'category' => 'integer',
            'title' => 'required|string|min:10|max:500',
            'description' => 'required|string|min:50',
            'content' => 'required|string|min:100',
            'image' => 'image',
            'video_file' => 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4',
            'video' => 'string',
            'draft' => 'boolean'
        ]);

        $fileName = '';
        if ($request->hasFile('image')) {
            $uploadSetting = News::$uploadSetting;
            $folderUpload = News::$folderUpload;
            $fileName = ImageController::upload($request->file('image'), $uploadSetting, $folderUpload);
        }

        $draft = $request->get('draft');
        $res_news = News::create([
            'user_id' => Auth::id(),
            'category' => !empty($request->get('category')) ? $request->get('category') : null,
            'title' => !empty($request->get('title')) ? $request->get('title') : null,
            'description' => !empty($request->get('description')) ? $request->get('description') : null,
            'content' => !empty($request->get('content')) ? $request->get('content') : null,
            'image' => $fileName,
            'video' => !empty($request->get('video')) ? $request->get('video') : null,
            'active' => null,
            'draft' => isset($draft) ? $draft : null,
        ]);

        if (!empty($request->get('video'))) {
            DB::table('youtube')->where('youtube_id', $request->get('video'))->update(['target_id' => $res_news->news_id]);
        }

        return redirect($this->redirectTo);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $news_id = null)
    {

        // We has two route : /news/id or /user/{id}/news/{id_news}
        $news_id = !empty($news_id) ? $news_id : $id;

        $news = News::find($news_id);
        if (empty($news)) {
            return abort('404');
        }

//        $userFullNames = User::usersFullName();

        $isOwner = $news->user_id == Auth::id() ? true : false;
        $imagePath = News::$uploadSetting['large']['path'];

        $usersCommentedL1 = Comment::where('target_type', COMMENT_NEWS_TYPE)
            ->where('target_id', $news_id)
            ->whereNull('grand_parent_id')
            ->orderBy('created_at', 'DESC')->paginate(COMMENT_LIMIT);

        $usersCommentedBuff = collect($usersCommentedL1)->all();
        $usersCommented = $this->buildResponseComments(collect($usersCommentedL1)->get('data'), COMMENT_NEWS_TYPE);

        $usersCommentedBuff['data'] = $usersCommented;
        $usersCommented = $usersCommentedBuff;
        return view('news.show', compact(['news', 'isOwner', 'imagePath', 'usersCommented']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $news = News::activeOwner()->where('news_id', $id)->first();

        if (empty($news) || (!empty($news) && !User::isOwner($news->user_id))) {
            return abort('404');
        }

        return view('news.edit', compact(['news']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request = $this->prepareFormInput($request);

        $this->validate($request, [
            'category' => 'integer',
            'title' => 'required|string|min:10|max:500',
            'description' => 'required|string|min:50',
            'content' => 'required|string|min:100',
            'image' => 'image',
            'video' => 'string',
            'video_file' => 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4',
            'draft' => 'boolean',
        ]);
        $news = News::find($id);

        if (!User::isOwner($news->user_id)) {
            return redirect()->back()->with(['errors' => trans('validate.permission_denied')]);
        }
        $file_name = '';
        if ($request->hasFile('image')) {
            $uploadSetting = News::$uploadSetting;
            $folderUpload = News::$folderUpload;
            $file_name = ImageController::upload($request->file('image'), $uploadSetting, $folderUpload);
        }

        $news->update([
            'category' => $request->has('category') ? $request->get('category') : null,
            'title' => $request->has('title') ? $request->get('title') : null,
            'description' => $request->has('description') ? $request->get('description') : null,
            'content' => $request->has('content') ? $request->get('content') : null,
            'image' => !empty($file_name) ? $file_name : $news->image,
            'video' => $request->has('video') ? $request->get('video') : null,
            'draft' => $request->has('draft') ? $request->get('draft') : null,
        ]);

        return redirect()->route('news.show', ['id' => $news->news_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $news = News::find($id);

        if (!User::isOwner($news->user_id)) {
            return redirect()->back()->with(['errors' => trans('validate.permission_denied')]);
        }

        $news->delete();
        return redirect()->route('news.index');
    }
}
