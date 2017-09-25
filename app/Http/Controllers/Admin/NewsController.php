<?php namespace JP_COMMUNITY\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JP_COMMUNITY\Http\Controllers\BaseController;
use JP_COMMUNITY\Http\Controllers\ImageController;
use JP_COMMUNITY\Models\News;
use JP_COMMUNITY\Models\User;

class NewsController extends BaseController
{
    protected $checkbox = ['active', 'draft'];
    protected $arrayToJsonField = ['target_page'];
    protected $redirectTo = 'admin/news';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::pluck('email', 'id');
        $news = News::withoutGlobalScope('active');
        if (!empty($request->all())) {
            preg_match_all("(\d{4}\-\d{2}-\d{2})", $request->get('created_at'), $createdAt);

            if (!empty($createdAt[0])) {
                $news = $news->whereBetween('created_at', $createdAt[0]);
            }
            /*
            if (!empty($user_ids = $request->get('user_ids'))) {
                $news = $news->whereIn('user_id', $user_ids);
            }*/
            if (!empty($pages = $request->get('pages'))) {
                foreach ($pages as $k => $page) {
                    if ($k == 0) {
                        $news = $news->where('target_page', 'like', "%$page%");
                    } else {
                        $news = $news->orWhere('target_page', 'like', "%$page%");
                    }
                }
            }
        }

        $news = $news->with('user')->paginate(10);
        $usersFullName = User::usersFullName();
        $pages = News::$targetPage;

        return view('admins.news.index', compact(['news', 'users', 'usersFullName', 'pages']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $news = new News();
        $targetPage = $news::$targetPage;

        return view('admins.news.create', compact('targetPage'));
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
            'user_id' => 'required|integer|min:1',
            'target_page' => 'required|string',
            'title' => 'required|string|min:10|max:500',
            'description' => 'required|string|min:50',
            'content' => 'required|string|min:100',
            'image' => 'image',
            'video' => 'string',
            'active' => 'boolean',
            'draft' => 'boolean',
            'link_catalog' =>'string',
            'email_customer' => 'email',
        ]);

        $file_name = '';
        if ($request->hasFile('image')) {
            $uploadSetting = News::$uploadSetting;
            $folderUpload = News::$folderUpload;
            $file_name = ImageController::upload($request->file('image'), $uploadSetting, $folderUpload);
        }


        $active = $request->get('active');
        $draft = $request->get('draft');
        $res_news = News::create([
            'user_id' => !empty($request->get('user_id')) ? $request->get('user_id') : null,
            'target_page' => !empty($request->get('target_page')) ? $request->get('target_page') : null,
            'title' => !empty($request->get('title')) ? $request->get('title') : null,
            'description' => !empty($request->get('description')) ? $request->get('description') : null,
            'content' => !empty($request->get('content')) ? $request->get('content') : null,
            'link_catalog' => !empty($request->get('link_catalog')) ? $request->get('link_catalog') : null,
            'email_customer' => !empty($request->get('email_customer')) ? $request->get('email_customer') : null,
            'image' => $file_name,
            'video' => !empty($request->get('video')) ? $request->get('video') : null,
            'active' => isset($active) ? $active : null,
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $news = News::find($id);
        $targetPage = News::$targetPage;
        $imagePath = null;
        if (!empty($news) && !empty($news->image)) {
            $imagePath = News::$folderUpload.$news->image;
        }
        return view('admins.news.edit', compact('news', 'targetPage', 'imagePath'));
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
            'target_page' => 'required|string',
            'title' => 'required|string|min:10|max:500',
            'description' => 'required|string|min:50',
            'content' => 'required|string|min:100',
            'image' => 'image',
            'video' => 'string',
            'active' => 'boolean',
            'draft' => 'boolean',
            'link_catalog' =>'string',
            'email_customer' => 'email',
        ]);

        $file_name = '';
        if ($request->hasFile('image')) {
            $uploadSetting = News::$uploadSetting;
            $folderUpload = News::$folderUpload;
            $file_name = ImageController::upload($request->file('image'), $uploadSetting, $folderUpload);
        }


        $active = $request->get('active');
        $draft = $request->get('draft');

        $res_news = News::find($id)->update([
            'target_page' => !empty($request->get('target_page')) ? $request->get('target_page') : null,
            'title' => !empty($request->get('title')) ? $request->get('title') : null,
            'description' => !empty($request->get('description')) ? $request->get('description') : null,
            'content' => !empty($request->get('content')) ? $request->get('content') : null,
            'image' => $file_name,
            'video' => !empty($request->get('video')) ? $request->get('video') : null,
            'link_catalog' => !empty($request->get('link_catalog')) ? $request->get('link_catalog') : null,
            'email_customer' => !empty($request->get('email_customer')) ? $request->get('email_customer') : null,
            'active' => isset($active) ? $active : null,
            'draft' => isset($draft) ? $draft : null,
        ]);

        return redirect($this->redirectTo)->with('message-success',
            trans('message.success_update_news', [
                'url'=> route('admin.news.edit', ['news_id' => $id ]),
                'news_id' => $id
            ])
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $ids = $request->get('ids');
            if (!empty($ids)) {
                if(!is_array($ids)) {
                    $ids = (array)$ids;
                }
            } else {
                return ;
            }

            DB::beginTransaction();
            try{
//                DB::table('news')->whereIn('news_id', $ids)->update(['deleted_at' => Carbon::now()]);
                News::whereIn('news_id', $ids)->delete();
            } catch (Exeption $e) {
                DB::rollBack();
            }

            DB::commit();
        }
    }
}
