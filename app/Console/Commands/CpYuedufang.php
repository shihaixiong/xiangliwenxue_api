<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminModel;

class CpYuedufang extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cp:yuedufang';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $channelId = 2;

    protected $sid = 49;
    protected $key = 'h49s734bca43htoc62gd';
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

    public function getUrl($type, $data = []) {
        $url = 'http://www.yuedufang.com/apis/jieqi/';
        $data['sid'] = $this->sid;
        ksort($data);
        $str = '';
        foreach ($data as $key => $value) {
            $str .= $key.'='.$value.'&';
        }
        $sign = md5($str.'key='.$this->key);
        switch ($type) {
            case '1':
                $method = 'articlelist.php?';
                break;
            case '2':
                $method = 'articleinfo.php?';
                break;
            case '3':
                $method = 'articlechapter.php?';
                break;
            case '4':
                $method = 'chaptercontent.php?';
                break;
        }
        $res = $url.$method.$str.'sign='.$sign;
        return $res;
    }

    public function addBook() {
        $url = $this->getUrl(1);
        $bookList = curl($url);
            $bookList = json_decode($bookList, true);
            foreach ($bookList as $key => $v) {
                $articleid = $v['articleid'];
                $isset = (new AdminModel('import_book'))->getOne(['channel_id'=>$this->channelId,'articleid'=>$articleid]);
                if(empty($isset)) {
                    tLog('暂无书籍内容  添加书籍'.$v['articlename']);
                    $aurl = $this->getUrl(2,['aid'=>$v['articleid']]);
                    $bookInfo = curl($aurl);
                    $bookInfo = json_decode($bookInfo, true);
                    $img = time().rand(100,999).'.jpg';
                    $dir = __dir__.'/../../../public/book_img/';
                    if(empty($bookInfo['cover'])) break;
                    file_put_contents($dir.$img,file_get_contents($bookInfo['cover']));
                    $sortid = $this->getSortid($bookInfo['sort']);
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
                        'channel'    => ($sortid < 7)?1:2
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
        $aurl = $this->getUrl(3,['aid'=>$articleid]);
        $chapter = curl($aurl);
        $chapterList = json_decode($chapter,true);
        // $localChapter = (new AdminModel('import_chapters'))->getAll(['articleid'=>$articleid]);
        // $cids = [];
        // if(!empty($localChapter)) {
        //     foreach ($localChapter as $key => $value) {
        //         $cids[] = $value['chapterid'];
        //     }
        // }
        $displayorder = 1;
        foreach ($chapterList as $key => $value) {
            if($value['chaptertype'] == 1) continue;
            $data = [
                'subhead' => $value['chaptername'],
                'displayorder' => $displayorder,
                'word_count' => $value['words'],
                'price' => $value['saleprice'],
                'created_at' => date( "Y-m-d H:i:s", time()),
                'updated_at' => date( "Y-m-d H:i:s", time()),
                'is_vip' => $value['isvip'],
                'book_id' => $bookid,
            ];
            $chapterid = (new AdminModel('chapters'))->addGetId($data);
            $curl = $this->getUrl(4,['aid'=>$articleid,'cid'=>$value['chapterid']]);

            $content = curl($curl);
            $content = json_decode($content,true);
            $tableName = "chapter_".$chapterid%100;
            if(empty($content['content'])) {
                $res = (new AdminModel('chapters'))->delArray(['id'=>$chapterid]);
                break;
            }
            $res = (new AdminModel($tableName))->addArray([
                'content' => $content['content'],
                'id' => $chapterid,
                'created_at' => date( "Y-m-d H:i:s", time()),
                'updated_at' => date( "Y-m-d H:i:s", time()),
            ]);
            if($res) {
                $displayorder++;
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
        $aurl = $this->getUrl(3,['aid'=>$articleid]);

        $chapter = curl($aurl);
        $chapterList = json_decode($chapter,true);
        $localChapter = (new AdminModel('chapters'))->getAll(['book_id'=>$bookid]);
        $cids = [];
        $num = count($localChapter);
        $displayorder = 1;
        foreach ($chapterList as $key => $value) {
            if($value['chaptertype'] == 1) continue;
            if($key + 1 <= $num) {
                $displayorder++; 
                continue;
            }
            $data = [
                'subhead' => $value['chaptername'],
                'displayorder' => $displayorder,
                'word_count' => $value['words'],
                'price' => $value['saleprice'],
                'created_at' => date( "Y-m-d H:i:s", time()),
                'updated_at' => date( "Y-m-d H:i:s", time()),
                'is_vip' => $value['isvip'],
                'book_id' => $bookid,
            ];
            $chapterid = (new AdminModel('chapters'))->addGetId($data);

            $curl = $this->getUrl(4,['aid'=>$articleid,'cid'=>$value['chapterid']]);
            $content = curl($curl);
            $content = json_decode($content,true);
             if(empty($content['content'])) {
                $res = (new AdminModel('chapters'))->delArray(['id'=>$chapterid]);
                break;
            }
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
            '现代都市' => 1,
            '玄幻仙侠' => 2,
            '职场商战' => 1,
            '豪门总裁' => 11,
            '穿越重生' => 9,
            '古代言情' => 7,
            '恐怖灵异' => 4,
            '出版影视' => 1,
            '悬疑推理' => 4,
            '游戏竞技' => 6,
            '幻想言情' => 11,
            '青春校园' => 6,
            '青春文学' => 10,
            '经典名著' => 3,
            '励志成功' => 3,
            '侦探推理' => 4,
            '人物传记' => 3,
            '外国名著' => 3 
        ];
        return $data[$sort] ?? 1;
    }

    
}
