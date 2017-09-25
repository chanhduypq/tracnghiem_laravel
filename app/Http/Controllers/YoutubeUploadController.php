<?php
namespace JP_COMMUNITY\Http\Controllers;

use Carbon\Carbon;
use Google_Client;
use Google_Service_YouTube;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use JP_COMMUNITY\Http\Controllers\Contracts\Youtube as YoutubeContract;
use Illuminate\Http\Request;

define('OAUTH_CLIENT_ID', Config::get('youtube.client_id'));
define('OAUTH_CLIENT_SECRET', Config::get('youtube.client_secret'));
define('REDIRECT_URI', Config::get('youtube.client_id'));

class YoutubeUploadController extends BaseController implements YoutubeContract {

    protected $client;

    protected $youtube;

    private $videoId;

    private $thumbnailUrl;

    public function __construct()
    {
        parent::__construct();

        $this->client = new Google_Client();
        $this->youtube = new Google_Service_YouTube($this->client);
    }

    /**
     * Save youtube uploaded info
     * @param Request $request
     */
    public function ajax_save(Request $request) {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => trans('message.login_required'),
                'error' => [
                    'code' => 404,
                    'message' => trans('message.login_required'),
                ]
            ]);
        }
        $this->validate($request, [
            'target_type' => 'required|string',
            'target_id' => 'integer',
            'title' => 'string',
            'description' => 'string',
            'youtube_id' => 'required|string'
        ]);

        $resId = DB::table('youtube')->insertGetId([
            'youtube_id' => $request->get('youtube_id'),
            'target_type' => $request->get('target_type'),
            'target_id' => !empty($request->get('target_id')) ? $request->get('target_id') : null,
            'user_id' => Auth::id(),
            'title' => !empty($request->get('title')) ? $request->get('title') : null,
            'description' => !empty($request->has('description')) ? $request->get('description') : null
        ]);

        return response()->json([
           'success' => true,
            'data' => [
                'id' => $resId,
                'youtube_id' => $request->get('youtube_id'),
                'target_type' => $request->get('target_type'),
                'title' => $request->has('title') ? $request->get('title') : null,
                'description' => $request->has('description') ? $request->get('description') : null
            ]
        ]);
    }
    public function upload($file, $data = [], $privacyStatus = 'public') {

        session_start();

        // OAUTH Configuration
        $oauthClientID = config('youtube.client_id');
        $oauthClientSecret = config('youtube.client_secret');
        $baseUri =  'http://localhost:8000';
//        $redirectUri = route('news.update', [31]);
        $redirectUri = url('youtube/uploads');

//        $client = $this->client;

        $client = $this->client;

        $client->setClientId($oauthClientID);
        $client->setClientSecret($oauthClientSecret);
        $client->setScopes('https://www.googleapis.com/auth/youtube');
        $client->setRedirectUri($redirectUri);
        // Define an object that will be used to make all API requests.
        $youtube = new Google_Service_YouTube($client);

//        var_dump($_SESSION['token']);

        /*if (!empty($request->get('code'))) {
            if (strval($_SESSION['state']) !== strval($request->get('state'))) {
                die('The session state did not match.');
            }

            $client->authenticate($request->get('code'));
            $_SESSION['token'] = $client->getAccessToken();

            header('Location: ' . $redirectUri);
        }*/

        if (isset($_SESSION['token'])) {
            $client->setAccessToken($_SESSION['token']);
        }

        $htmlBody = '';
        if ($client->getAccessToken()) {
            try{
                // REPLACE this value with the path to the file you are uploading.

                $targetDirTmp = base_path('htdocs') .'/uploads/videos/tmp/';

                $extension = $file->getClientOriginalExtension();


                $name = $file->getClientOriginalName();

                $fileName = strtolower(preg_replace("/[^\w]+/", "-",str_replace('.'.$extension,'',$name))). '_'.uniqid();
                $fileFullName = $fileName.'.'.$extension;

                $uploadStatus = $file->move($targetDirTmp, $fileFullName);

                $videoPath = $uploadStatus->getRealPath();
//                $videoPath = 'C:\wamp64\www\upload-video-to-youtube-using-php\videos\attaimiadnyanyn-aaa.mp4';
//    var_dump($videoPath);exit;

                // Create a snippet with title, description, tags and category ID
                // Create an asset resource and set its snippet metadata and type.
                // This example sets the video's title, description, keyword tags, and
                // video category.
                $snippet = new \Google_Service_YouTube_VideoSnippet();
                $snippet->setTitle('111111');
                $snippet->setDescription('2222222');
                $snippet->setTags('3333333');

                // Numeric video category. See
                // https://developers.google.com/youtube/v3/docs/videoCategories/list
                $snippet->setCategoryId("22");

                // Set the video's status to "public". Valid statuses are "public",
                // "private" and "unlisted".
                $status = new \Google_Service_YouTube_VideoStatus();
                $status->privacyStatus = "public";

                // Associate the snippet and status objects with a new video resource.
                $video = new \Google_Service_YouTube_Video();
                $video->setSnippet($snippet);
                $video->setStatus($status);

                // Specify the size of each chunk of data, in bytes. Set a higher value for
                // reliable connection as fewer chunks lead to faster uploads. Set a lower
                // value for better recovery on less reliable connections.
                $chunkSizeBytes = 1 * 1024 * 1024;

                // Setting the defer flag to true tells the client to return a request which can be called
                // with ->execute(); instead of making the API call immediately.
                $client->setDefer(true);

                // Create a request for the API's videos.insert method to create and upload the video.
                $insertRequest = $youtube->videos->insert("status,snippet", $video);

                // Create a MediaFileUpload object for resumable uploads.
                $media = new \Google_Http_MediaFileUpload(
                    $client,
                    $insertRequest,
                    'video/*',
                    null,
                    true,
                    $chunkSizeBytes
                );
                $media->setFileSize(filesize($videoPath));

                // Read the media file and upload it.
                $status = false;
                $handle = fopen($videoPath, "rb");
                while (!$status && !feof($handle)) {
                    $chunk = fread($handle, $chunkSizeBytes);
                    $status = $media->nextChunk($chunk);
                }
                fclose($handle);

                // If you want to make other calls after the file upload, set setDefer back to false
                $client->setDefer(false);

                // delete video file from local folder
//                @unlink($result['video_path']);

                /*$htmlBody .= "<p class='succ-msg'>Video have been uploaded successfully.</p><ul>";
                $htmlBody .= '<embed width="400" height="315" src="https://www.youtube.com/embed/'.$status['id'].'"></embed>';
                $htmlBody .= '<li><b>Title: </b>'.$status['snippet']['title'].'</li>';
                $htmlBody .= '<li><b>Description: </b>'.$status['snippet']['description'].'</li>';
                $htmlBody .= '</ul>';
                $htmlBody .= '<a href="logout.php">Logout</a>';*/
                return $status['id'];

            } catch (\Google_ServiceException $e) {
                $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
                    htmlspecialchars($e->getMessage()));
            } catch (\Google_Exception $e) {
                $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
                $htmlBody .= 'Please reset session <a href="logout.php">Logout</a>';
            }

            $_SESSION['token'] = $client->getAccessToken();
        } else {
            // If the user hasn't authorized the app, initiate the OAuth flow
            $state = mt_rand();
            $client->setState($state);
            $_SESSION['state'] = $state;

            $authUrl = $client->createAuthUrl();

            $htmlBody= view('youtube.login', compact('authUrl'));
            echo $htmlBody;
            exit;
        }
    }

    public function handleGetAccessToken($request = []) {

        session_start();
        $client = new Google_Client();

        $client->setApplicationName(config('youtube.application_name'));
        $client->setClientId(config('youtube.client_id'));
        $client->setClientSecret(config('youtube.client_secret'));
        $client->setScopes(config('youtube.scopes'));
        $client->setAccessType(config('youtube.access_type'));
        $client->setApprovalPrompt(config('youtube.approval_prompt'));
        $client->setClassConfig('Google_Http_Request', 'disable_gzip', true);
        $client->setRedirectUri(url(
            config('youtube.routes.prefix') . '/' . config('youtube.routes.redirect_uri')
        ));

        $this->youtube = new \Google_Service_YouTube($client);

        /**/
        if (!empty($request['code'])) {
            if (strval($_SESSION['state']) !== strval($request['state'])) {
                die('The session state did not match.');
            }

            $client->authenticate($request['code']);
            $_SESSION['token'] = $client->getAccessToken();
            $this->saveAccessTokenToDB($client->getAccessToken());
            header('Location: ' . config('youtube.route.redirect_uri'));
        }

        if (isset($_SESSION['token'])) {
            $client->setAccessToken($_SESSION['token']);
        }
        /**/

        if ($accessToken = $this->getLatestAccessTokenFromDB()) {
            $client->setAccessToken($accessToken);
        }
    }

    /**
     * Saves the access token to the database.
     *
     * @param  string $accessToken
     */
    public function saveAccessTokenToDB($accessToken)
    {
        $data = [
            'access_token' => $accessToken,
            'created_at'   => Carbon::now(),
        ];
        $res = \DB::table('youtube_access_tokens')->insert($data);
    }

    /**
     * Returns the last saved access token, if there is one, or null
     *
     * @return mixed
     */
    public function getLatestAccessTokenFromDB()
    {
        // TODO: Implement getLatestAccessTokenFromDB() method.
    }

    /**
     * Set a Custom Thumbnail for the Upload
     *
     * @param  string $imagePath
     *
     * @return self
     */
    public function withThumbnail($imagePath)
    {
        // TODO: Implement withThumbnail() method.
    }

    /**
     * Return the Video ID
     *
     * @return string
     */
    public function getVideoId()
    {
        // TODO: Implement getVideoId() method.
    }

    /**
     * Return the URL for the Custom Thumbnail
     *
     * @return string
     */
    public function getThumbnailUrl()
    {
        // TODO: Implement getThumbnailUrl() method.
    }

    /**
     * Delete a YouTube video by it's ID.
     *
     * @param  int $id
     *
     * @return bool
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * Check if a YouTube video exists by it's ID.
     *
     * @param  int $id
     *
     * @return bool
     */
    public function exists($id)
    {
        // TODO: Implement exists() method.
    }
}