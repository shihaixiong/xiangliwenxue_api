<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminModel;

class Cptianyuan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cp:tianyuan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $channelId = 4;
    protected $sid = 'BaiShiShiRead';
    protected $key = '9e37a485e3de93166f41b25370cd210e';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->addBook();
    }

    public function getSign() {
        return md5($this->sid.$this->key);
    }
    public function addBook() {
        $bookList = curl('http://www.zhangdu8.com/Interface/BaiShiShiRead/booklist/key/'.$this->getSign());
            $bookList = json_decode($bookList, true);
            foreach ($bookList['data'] as $key => $v) {
                $articleid = $v['id'];
                $isset = (new AdminModel('import_book'))->getOne(['channel_id'=>$this->channelId,'articleid'=>$articleid]);
                if(empty($isset)) {
                    tLog('暂无书籍内容  添加书籍'.$v['name']);
                    $bookInfo = curl('http://www.zhangdu8.com/Interface/BaiShiShiRead/books/key/'.$this->getSign().'/bookid/'.$v['id']);
                    $bookInfo = json_decode($bookInfo, true);
                    $bookInfo = $bookInfo['data'];
                    $img = time().rand(100,999).'.jpg';
                    $dir = __dir__.'/../../../public/book_img/';
                    file_put_contents($dir.$img,file_get_contents($bookInfo['cover']));
                    $sortid = $this->getSortid($bookInfo['category']);
                    $data = [
                        'title'      => $bookInfo['name'],
                        'author'     => $bookInfo['author'],
                        'keywords'   => implode(',', explode('|', $bookInfo['keywords'])),
                        'finish'     => ($bookInfo['serialStatus'] == 2) ? 1 : 0, 
                        'desc'       => $bookInfo['summary'], 
                        'image'      => $img, 
                        'word_count' => $bookInfo['words'],
                        'is_vip'      => $bookInfo['isvip'],
                        'sortid'     => $sortid,
                        'created_at' =>date( "Y-m-d H:i:s", time()),
                        'updated_at' =>date( "Y-m-d H:i:s", time()),
                        'channel'    => ($sortid == 1)?1:2
                    ];
                    $bookid = (new AdminModel('books'))->addGetId($data);
                    if($bookid) {
                        (new AdminModel('import_book'))->addArray(
                            [
                                'articleid'       =>$bookInfo['id'],
                                'local_articleid' => $bookid,
                                'created_at'      =>date( "Y-m-d H:i:s", time()),
                                'channel_id'      => $this->channelId,
                            ]
                        );
                    }

                    $this->addChapter($articleid, $bookid);
                    
                }else{
                    $this->updChapter($articleid, $isset['local_articleid']);
                }

            }
            tLog('完成');
    }

    public function addChapter($articleid, $bookid) {
        $chapter = curl('http://www.zhangdu8.com/Interface/BaiShiShiRead/showclist/key/'.$this->getSign().'/bookid/'.$articleid);
        $chapterList = json_decode($chapter,true);
        // $localChapter = (new AdminModel('import_chapters'))->getAll(['articleid'=>$articleid]);
        // $cids = [];
        // if(!empty($localChapter)) {
        //     foreach ($localChapter as $key => $value) {
        //         $cids[] = $value['chapterid'];
        //     }
        // }
        foreach ($chapterList['data'] as $key => $value) {
            $data = [
                'subhead' => $value['name'],
                'displayorder' => $value['order'],
                'word_count' => $value['words'],
                'price' => ceil($value['words']*6/1000),
                'created_at' => date( "Y-m-d H:i:s", time()),
                'updated_at' => date( "Y-m-d H:i:s", time()),
                'is_vip' => $value['isvip'],
                'book_id' => $bookid,
            ];
            $chapterid = (new AdminModel('chapters'))->addGetId($data);

            $content = curl('http://www.zhangdu8.com/Interface/BaiShiShiRead/content/key/'.$this->getSign().'/bookid/'.$articleid.'/chapterid/'.$value['cid']);
            $content = json_decode($content,true);

            $tableName = "chapter_".$chapterid%100;
            $res = (new AdminModel($tableName))->addArray([
                'content' => $content['data']['content'],
                'id' => $chapterid,
                'created_at' => date( "Y-m-d H:i:s", time()),
                'updated_at' => date( "Y-m-d H:i:s", time()),
            ]);
            if($res) {
                tLog($value['name']."添加成功");
            }
            else {
                (new AdminModel('chapters'))->delArray(['id'=>$chapterid]);
                tLog($value['name']."添加失败");
                break;
            }
        }

    }

    public function updChapter($articleid, $bookid) {
        $chapter = curl('http://www.zhangdu8.com/Interface/BaiShiShiRead/showclist/key/'.$this->getSign().'/bookid/'.$articleid);
        $chapterList = json_decode($chapter,true);
        $localChapter = (new AdminModel('chapters'))->getAll(['book_id'=>$bookid]);
        $cids = [];
        $num = count($localChapter);
        
        foreach ($chapterList['data'] as $key => $value) {
            if($key + 1 <= $num) continue;
            $data = [
                'subhead' => $value['name'],
                'displayorder' => $value['order'],
                'word_count' => $value['words'],
                'price' => ceil($value['words']*6/1000),
                'created_at' => date( "Y-m-d H:i:s", time()),
                'updated_at' => date( "Y-m-d H:i:s", time()),
                'is_vip' => $value['isvip'],
                'book_id' => $bookid,
            ];
            $chapterid = (new AdminModel('chapters'))->addGetId($data);

            $content = curl('http://www.zhangdu8.com/Interface/BaiShiShiRead/content/key/'.$this->getSign().'/bookid/'.$articleid.'/chapterid/'.$value['cid']);
            $content = json_decode($content,true);

            $tableName = "chapter_".$chapterid%100;
            $res = (new AdminModel($tableName))->addArray([
                'content' => $content['data']['content'],
                'id' => $chapterid,
                'created_at' => date( "Y-m-d H:i:s", time()),
                'updated_at' => date( "Y-m-d H:i:s", time()),
            ]);
            if($res) {
                tLog($value['name']."添加成功");
            }
            else {
                (new AdminModel('chapters'))->delArray(['id'=>$chapterid]);
                tLog($value['name']."添加失败");
                break;
            }
        }

    }

    public function getSortid($sort) {
        $data = [
            '5' => 2, 
            '8' => 1,
            '14' => 1,
            '10' => 9,
            '12' => 1,
            '1' => 8,
            '2' => 7,
            '3' => 9,
            '7' => 4,
            '13' => 4,
            '4' => 6,
            '11' => 9,
            '6' => 6,
            '12' => 1,
            '9' => 2,
            '18' => 1,
            '17' => 1,
        ];
        return $data[$sort] ?? 1;
    }

    
}
