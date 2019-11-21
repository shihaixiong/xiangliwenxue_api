<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\FavoriteModel;
use App\Models\CommonModel;
use App\Providers\RouteServiceProvider;
use App\Services\BookService;
use App\Services\TaskService;
use App\Services\UserService;

class FunController extends BaseController
{
    protected $book;
    protected $task;
    protected $user;

    public function __construct(BookService $book, TaskService $task, UserService $user)
    {
        $this->book = $book;
        $this->task = $task;
        $this->user = $user;
    }
    public function init(Request $r) {
        $userid = $r->input('userid');

        
        $data = [
            'app' => [
            ],
        ];

        $downConfig = (new CommonModel('down_config'))->getDb()->orderBy('id','desc')->first();
        $version  = $r->input('version');
        $flag = 1;
        if(version_compare($version, '1.1.2','=')) {
 //           $flag = 0;
        }

        if(version_compare($version,$downConfig->version,'<')) {
            $data['app']['android_version'] = $downConfig->version;
            $data['app']['flag'] = $flag;
            $data['app']['note'] = $downConfig->note;
            $data['app']['update_type'] = $downConfig->type;
            $data['app']['link'] = env("WEB_URL")."apk/".$downConfig->file;
        } else {
            $data['app']['android_version'] = '';
            $data['app']['flag'] = $flag;
            $data['app']['note'] = '';
            $data['app']['link'] = '';
            $data['app']['update_type'] = 0;
        }

        apiReturn(1,'ok',$data);
    }

    public function shareInfo(Request $r) {
        $type = $r->input('type') ?? 1;
        $language = 0;
        if($r->header('language') == 1) {
            $language = 1;
        }
        if($type == 1) {
            $articleid = $r->input('articleid');
            $bookInfo = $this->book->getBookInfo($articleid);
            $data = [
                'title' => $bookInfo['title'],
                'image' => $bookInfo['image'],
                'content' => $bookInfo['desc'],
                'link'  => env('WEB_URL').'book/share?articleid='.$articleid.'&language='.$language
            ];
            apiReturn(1, 'success', $data);
        }elseif( $type == 2 ){
            $userid = getUserId($r->header('token'));
            $code = $this->user->inviteCode($userid);
            $data = [
                'title' => '邀请好友 赚福利',
                'image' => '',
                'content' => '绑定邀请码,与好友一起玩“赚”香狸文学',
                'link'  => env('WEB_URL').'api/user/invite?token='.$r->header('token')
            ];
            apiReturn(1, 'success', $data);
        }
    }

    public function shareFin(Request $r) {
        if(!empty($r->header('token'))) {
            $userid = getUserId($r->header('token'));
            $this->task->addTaskLog($userid, 3);

        }
        apiReturn(1, 'success', []);

    }

}
