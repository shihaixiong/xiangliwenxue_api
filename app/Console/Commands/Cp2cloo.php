<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminModel;

class Cp2cloo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cp:2cloo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $channelId = 5;
    protected $sid = 1000099;
    protected $key = 'dd3bef145b2deaa4';
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

    public function getUrl($type, $data = []) {
        $url = 'http://open.2cloo.com/mcpgennew/';
        $data['identity'] = $this->sid;
        $data['lastUpdateTime'] = 1;
        ksort($data);
        $str = '';
        foreach ($data as $key => $value) {
            $str .= $key.'='.$value.'&';
        }
        $sign = md5($str.'secretKey='.$this->key);
        switch ($type) {
            case '1':
                $method = 'book/list?';
                break;
            case '2':
                $method = 'book/info?';
                break;
            case '3':
                $method = 'book/chapter/list?';
                break;
            case '4':
                $method = 'book/chapter/content?';
                break;
        }
        $res = $url.$method.$str.'sign='.$sign;
        return $res;
    }
    public function addBook() {
        $bookList = curl($this->getUrl(1));
            $bookList = json_decode($bookList, true);
            foreach ($bookList['data']['books'] as $key => $v) {
                $articleid = $v['id'];
                $isset = (new AdminModel('import_book'))->getOne(['channel_id'=>$this->channelId,'articleid'=>$articleid]);
                if(empty($isset)) {
                    tLog('暂无书籍内容  添加书籍'.$v['id']);
                    $bookInfo = curl($this->getUrl(2,['bookId'=>$articleid]));
                    $bookInfo = json_decode($bookInfo, true);
                    $bookInfo = $bookInfo['data'];
                    $img = time().rand(100,999).'.jpg';
                    $dir = __dir__.'/../../../public/book_img/';
                    file_put_contents($dir.$img,file_get_contents($bookInfo['coverImg']));
                    $sortid = $this->getSortid($bookInfo['category']);
                    $data = [
                        'title'      => $bookInfo['name'],
                        'author'     => $bookInfo['author'],
                        'keywords'   => implode(',', explode(',', $bookInfo['keywords'])),
                        'finish'     => $bookInfo['finishFlag'], 
                        'desc'       => $bookInfo['description'], 
                        'image'      => $img, 
                        'word_count' => $bookInfo['totalWordCount'],
                        'is_vip'      => 1,
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
        $chapter = curl($this->getUrl(3,['bookId'=>$articleid]));
        $chapterList = json_decode($chapter,true);
        // $localChapter = (new AdminModel('import_chapters'))->getAll(['articleid'=>$articleid]);
        // $cids = [];
        // if(!empty($localChapter)) {
        //     foreach ($localChapter as $key => $value) {
        //         $cids[] = $value['chapterid'];
        //     }
        // }
        foreach ($chapterList['data']['chapters'] as $key => $value) {
            $data = [
                'subhead' => $value['title'],
                'displayorder' => $value['displayOrder'],
                'word_count' => $value['wordNum'],
                'price' => ceil($value['wordNum']*6/1000),
                'created_at' => date( "Y-m-d H:i:s", time()),
                'updated_at' => date( "Y-m-d H:i:s", time()),
                'is_vip' => $value['isVip'],
                'book_id' => $bookid,
            ];
            $chapterid = (new AdminModel('chapters'))->addGetId($data);

            $content = curl($this->getUrl(4,['chapterId'=>$value['chapterId'],'bookId'=>$articleid]));
            $content = json_decode($content,true);

            $tableName = "chapter_".$chapterid%100;
            $res = (new AdminModel($tableName))->addArray([
                'content' => $content['data']['content'],
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
        $chapter = curl($this->getUrl(3,['bookId'=>$articleid]));
        $chapterList = json_decode($chapter,true);
        $localChapter = (new AdminModel('chapters'))->getAll(['book_id'=>$bookid]);
        $cids = [];
        $num = count($localChapter);
        
        foreach ($chapterList['data']['chapters'] as $key => $value) {
            if($key + 1 < $num) continue;
            $data = [

                'subhead' => $value['title'],
                'displayorder' => $value['displayOrder'],
                'word_count' => $value['wordNum'],
                'price' => ceil($value['wordNum']*6/1000),
                'created_at' => date( "Y-m-d H:i:s", time()),
                'updated_at' => date( "Y-m-d H:i:s", time()),
                'is_vip' => $value['isVip'],
                'book_id' => $bookid,
            ];
            $chapterid = (new AdminModel('chapters'))->addGetId($data);

            $content = curl($this->getUrl(4,['chapterId'=>$value['chapterId'],'bookId'=>$articleid]));
            $content = json_decode($content,true);

            $tableName = "chapter_".$chapterid%100;
            $res = (new AdminModel($tableName))->addArray([
                'content' => $content['data']['content'],
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
            '现代言情' => 7,
            '古代言情' => 8,
            '幻想言情' => 7,
            '玄幻女强' => 2,
            '仙侠魔幻' => 5,
            '青春校园' => 6,
            '校园都市' => 6,
            '武侠仙侠' => 5,
            '军史强国' => 1,
            '灵异悬疑' => 4,
            '网游竞技' => 10,
            '文史传记' => 1,
            '侦探推理' => 4,
            '婚姻职场' => 1,
            '童话故事' => 7,
            '剧本故事' => 6,
            '短篇小说' => 1,
            '耽美小说' => 7,
            '玄奇幻想' => 2,
            '古今同人' => 7,
            '总裁豪门' => 7,
            '网游言情' => 7,
            '高干言情' => 7,
            '都市爽文' => 1,
            '都市宠文' => 1,
            '都市虐文' => 1,
            '宝宝文' => 8,
            '高干文' => 1,
            '宫斗宅斗' => 8,
            '嫡女重生' => 8,
            '古代宠文' => 8,
            '古代虐文' => 8,
            '种田文' => 8,
            '狼性王爷' => 1,
            '穿越古代' => 8,
            '架空穿越' => 8,
            '穿越重生' => 8,
            '穿越种田' => 9,
            '女主重生' => 1,
            '女配文' => 1,
            '女尊王朝' => 1,
            '妖王文' => 1,
            '仙侠言情' => 5,
            '女主修仙' => 11,
            '魔幻言情' => 7,
            '青春纯爱' => 1,
            '黑道校园' => 6,
            '贵族校园' => 6,
            '校园侦探' => 6,
            '校园励志' => 6,
            '都市异能' => 1,
            '都市修真' => 1,
            '都市暧昧' => 1,
            '都市官场' => 1,
            '乡村小说' => 11,
            '奇幻魔幻' => 11,
            '东方玄幻' => 2,
            '异世大陆' => 2,
            '修真小说' => 2,
            '武侠小说' => 5,
            '洪荒小说' => 2,
            '现代军事' => 1,
            '历史军事' => 1,
            '抗日小说' => 1,
            '穿越军事' => 1,
            '盗墓小说' => 4,
            '恐怖灵异' => 4,
            '校园灵异' => 4,
            '搞笑灵异' => 4,
            '灵异文' => 4,
            '历史小说' => 1,
            '历史文献' => 1,
            '人物传记' => 1,
            '历史新说' => 1,
            '悬疑推理' => 4,
            '惊悚小说' => 4,
            '侦探小说' => 4,
            '探险小说' => 4,
            '职场励志' => 4,
            '传奇探险' => 4,
            '王子公主' => 4,
            '英雄传说' => 4,
            '妖魔鬼怪' => 4,
            '科普故事' => 4,
            '古代剧' => 8,
            '现代剧' => 1,
            '小说' => 1,
            '散文' => 1,
            '杂谈' => 1,
            '日记' => 1,
            '诗歌' => 1,
            '歌词' => 1,
            '台湾小言' => 1,
            '近代现代' => 1,
            '古色古香' => 1,
            '架空历史' => 1,
            '幻想未来' => 1,
            '国外科幻' => 1,
            '末世危机' => 1,
            '短篇小说-灵异悬疑' => 4,
            '短篇小说-总裁豪门' => 1,
            '短篇小说-婚恋职场' => 1,
            '短篇小说-宫斗宅斗' => 1,
        ];
        return $data[$sort] ?? 1;
    }

    
}
