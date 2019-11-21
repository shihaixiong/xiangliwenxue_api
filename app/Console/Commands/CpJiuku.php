<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminModel;

class CpJiuku extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cp:jiuku';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $channelId = 3;
    protected $sid = 152;
    protected $key = '856382d5624e48463f0caff3770ca8f3';
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
        return md5('pid='.$this->sid.'&key='.$this->key);
    }
    public function addBook() {
        $bookList = curl('http://Inf.9kus.com/communalData/bookList/returnType/JSON/pid/'.$this->sid.'/sn/'.$this->getSign());
            $bookList = json_decode($bookList, true);

            foreach ($bookList['data'] as $key => $v) {
                $articleid = $v['id'];
                $isset = (new AdminModel('import_book'))->getOne(['channel_id'=>$this->channelId,'articleid'=>$articleid]);
                if(empty($isset)) {
                    tLog('暂无书籍内容  添加书籍'.$v['booktitle']);
                    $bookInfo = curl('http://Inf.9kus.com/communalData/bookInfo/returnType/JSON/pid/'.$this->sid.'/sn/'.$this->getSign().'/bookId/'.$v['id']);
                    $bookInfo = json_decode($bookInfo, true);
                    $bookInfo = $bookInfo['data'];
                    $img = time().rand(100,999).'.jpg';
                    $dir = __dir__.'/../../../public/book_img/';
                    file_put_contents($dir.$img,file_get_contents($bookInfo['cover']));
                    $sortid = $this->getSortid($bookInfo['category_id']);
                    $data = [
                        'title'      => $bookInfo['title'],
                        'author'     => $bookInfo['author'],
                        'keywords'   => implode(',', explode(',', $bookInfo['tag'])),
                        'finish'     => $bookInfo['isFull'], 
                        'desc'       => $bookInfo['summary'], 
                        'image'      => $img, 
                        'word_count' => $bookInfo['word_count'],
                        'is_vip'      => $bookInfo['isVip'],
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
        $chapter = curl('http://Inf.9kus.com/communalData/chapters/returnType/JSON/pid/'.$this->sid.'/sn/'.$this->getSign().'/bookId/'.$articleid);
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
                'subhead' => $value['title'],
                'displayorder' => $value['chapterOrder'],
                'word_count' => $value['chapterLength'],
                'price' => ceil($value['chapterLength']*6/1000),
                'created_at' => date( "Y-m-d H:i:s", time()),
                'updated_at' => date( "Y-m-d H:i:s", time()),
                'is_vip' => $value['isVip'],
                'book_id' => $bookid,
            ];
            $chapterid = (new AdminModel('chapters'))->addGetId($data);

            $content = curl('http://Inf.9kus.com/communalData/content/returnType/JSON/pid/'.$this->sid.'/sn/'.$this->getSign().'/bookId/'.$articleid.'/id/'.$value['id']);
            $content = json_decode($content,true);

            $tableName = "chapter_".$chapterid%100;
            $res = (new AdminModel($tableName))->addArray([
                'content' => $content['data'],
                'id' => $chapterid,
                'created_at' => date( "Y-m-d H:i:s", time()),
                'updated_at' => date( "Y-m-d H:i:s", time()),
            ]);
            if($res) {
                tLog($value['title']."添加成功");
            }
            else {
                (new AdminModel('chapters'))->delArray(['id'=>$chapterid]);
                tLog($value['title']."添加失败");
                break;
            }
        }

    }

    public function updChapter($articleid, $bookid) {
        $chapter = curl('http://Inf.9kus.com/communalData/chapters/returnType/JSON/pid/'.$this->sid.'/sn/'.$this->getSign().'/bookId/'.$articleid);
        $chapterList = json_decode($chapter,true);
        $localChapter = (new AdminModel('chapters'))->getAll(['book_id'=>$bookid]);
        $cids = [];
        $num = count($localChapter);
        
        foreach ($chapterList['data'] as $key => $value) {
            if($key + 1 <= $num) continue;
            $data = [
                'subhead' => $value['title'],
                'displayorder' => $value['chapterOrder'],
                'word_count' => $value['chapterLength'],
                'price' => ceil($value['chapterLength']*6/1000),
                'created_at' => date( "Y-m-d H:i:s", time()),
                'updated_at' => date( "Y-m-d H:i:s", time()),
                'is_vip' => $value['isVip'],
                'book_id' => $bookid,
            ];
            $chapterid = (new AdminModel('chapters'))->addGetId($data);

            $content = curl('http://Inf.9kus.com/communalData/content/returnType/JSON/pid/'.$this->sid.'/sn/'.$this->getSign().'/bookId/'.$articleid.'/id/'.$value['id']);
            $content = json_decode($content,true);

            $tableName = "chapter_".$chapterid%100;
            $res = (new AdminModel($tableName))->addArray([
                'content' => $content['data'],
                'id' => $chapterid,
                'created_at' => date( "Y-m-d H:i:s", time()),
                'updated_at' => date( "Y-m-d H:i:s", time()),
            ]);
            if($res) {
                tLog($value['title']."添加成功");
            }
            else {
                (new AdminModel('chapters'))->delArray(['id'=>$chapterid]);
                tLog($value['title']."添加失败");
                break;
            }
        }

    }

    public function getSortid($sort) {
        $data = [
            '玄幻奇幻' => 2, 
            '都市生活' => 1,
            '科幻同人' => 1,
            '历史军事' => 9,
            '仙侠武侠' => 9,
            '游戏竞技' => 1,
            '现代言情' => 8,
            '古代言情' => 7,
            '穿越架空' => 9,
            '悬疑灵异' => 4,
            '青春校园' => 6,
            '仙侠情结' => 9,
            '纯爱频道' => 8,
            '校园生活' => 6,
            '宅男同人' => 1,
            '原生幻想' => 2,
            '轻松搞笑' => 1,
        ];
        return $data[$sort] ?? 1;
    }

    
}
