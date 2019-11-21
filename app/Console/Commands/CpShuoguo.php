<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminModel;

class cpShuoguo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cp:shuoguo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $channelId = 1;
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

    public function addBook() {
        $bookList = curl('http://www.shuoguobook.com/open/index.php/api/in/AppID=cFOz5m/ApiID=1560900958');
            $bookList = json_decode($bookList, true);

            foreach ($bookList as $key => $v) {
                $articleid = $v['articleid'];
                $isset = (new AdminModel('import_book'))->getOne(['channel_id'=>$this->channelId,'articleid'=>$articleid]);
                if(empty($isset)) {
                    tLog('暂无书籍内容  添加书籍'.$v['articlename']);
                    $bookInfo = curl('http://www.shuoguobook.com/open/index.php/api/in/AppID=cFOz5m/ApiID=1560901074/BookId='.$v['articleid']);
                    $bookInfo = json_decode($bookInfo, true);
                    $img = time().rand(100,999).'.jpg';
                    $dir = __dir__.'/../../../public/book_img';
                    file_put_contents($dir.$img,file_get_contents($bookInfo['cover']));
                    $sortid = $this->getSortid($bookInfo['category']);
                    $data = [
                        'title'      => $bookInfo['articlename'],
                        'author'     => $bookInfo['author'],
                        'keywords'   => implode(',', explode(' ', $bookInfo['keywords'])),
                        'finish'     => $bookInfo['fullflag'], 
                        'desc'       => $bookInfo['intro'], 
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
                                'articleid'       =>$bookInfo['articleid'],
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
        $chapter = curl('http://www.shuoguobook.com/open/index.php/api/in/AppID=cFOz5m/ApiID=1560901139/BookId='.$articleid);
        $chapterList = json_decode($chapter,true);
        // $localChapter = (new AdminModel('import_chapters'))->getAll(['articleid'=>$articleid]);
        // $cids = [];
        // if(!empty($localChapter)) {
        //     foreach ($localChapter as $key => $value) {
        //         $cids[] = $value['chapterid'];
        //     }
        // }
        foreach ($chapterList as $key => $value) {
            $data = [
                'subhead' => $value['chaptername'],
                'displayorder' => $value['chapterorder'],
                'word_count' => $value['words'],
                'price' => $value['saleprice'],
                'created_at' => date( "Y-m-d H:i:s", time()),
                'updated_at' => date( "Y-m-d H:i:s", time()),
                'is_vip' => $value['isvip'],
                'book_id' => $value['bookid'],
            ];
            $chapterid = (new AdminModel('chapters'))->addGetId($data);

            $content = curl('http://www.shuoguobook.com/open/index.php/api/in/AppID=cFOz5m/ApiID=1560901191/BookId='.$articleid.'/ChapterId='.$value['chapterid']);
            $content = json_decode($content,true);

            $tableName = "chapter_".$chapterid%100;
            $res = (new AdminModel($tableName))->addArray([
                'content' => $content['content'],
                'id' => $chapterid,
                'created_at' => date( "Y-m-d H:i:s", time()),
                'updated_at' => date( "Y-m-d H:i:s", time()),
            ]);
            if($res) {
                tLog($value['chaptername']."添加成功");
            }
            else {
                (new AdminModel('chapters'))->delArray(['id'=>$chapterid]);
                tLog($value['chaptername']."添加失败");
                break;
            }
        }

    }

    public function updChapter($articleid, $bookid) {
        $chapter = curl('http://www.shuoguobook.com/open/index.php/api/in/AppID=cFOz5m/ApiID=1560901139/BookId='.$articleid);
        $chapterList = json_decode($chapter,true);
        $localChapter = (new AdminModel('chapters'))->getAll(['book_id'=>$bookid]);
        $cids = [];
        $num = count($localChapter);
        
        foreach ($chapterList as $key => $value) {
            if($key + 1 <= $num) continue;
            $data = [
                'subhead' => $value['chaptername'],
                'displayorder' => $value['chapterorder'],
                'word_count' => $value['words'],
                'price' => $value['saleprice'],
                'created_at' => date( "Y-m-d H:i:s", time()),
                'updated_at' => date( "Y-m-d H:i:s", time()),
                'is_vip' => $value['isvip'],
                'book_id' => $value['bookid'],
            ];
            $chapterid = (new AdminModel('chapters'))->addGetId($data);

            $content = curl('http://www.shuoguobook.com/open/index.php/api/in/AppID=cFOz5m/ApiID=1560901191/BookId='.$articleid.'/ChapterId='.$value['chapterid']);
            $content = json_decode($content,true);

            $tableName = "chapter_".$chapterid%100;
            $res = (new AdminModel($tableName))->addArray([
                'content' => $content['content'],
                'id' => $chapterid,
                'created_at' => date( "Y-m-d H:i:s", time()),
                'updated_at' => date( "Y-m-d H:i:s", time()),
            ]);
            if($res) {
                tLog($value['chaptername']."添加成功");
            }
            else {
                (new AdminModel('chapters'))->delArray(['id'=>$chapterid]);
                tLog($value['chaptername']."添加失败");
                break;
            }
        }

    }

    public function getSortid($sort) {
        $data = [
            '玄幻' => 3,
            '武侠' => 9,
            '都市' => 10,
            '历史' => 11,
            '悬疑' => 5,
            '科幻' => 3,
            '同人' => 6,
            '现言' => 1,
            '古言' => 1
        ];
        return $data[$sort] ?? 1;
    }

    
}
