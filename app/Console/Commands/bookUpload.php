<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminModel;

class BookUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->uploadBook();
    }

    function uploadBook() {
        $dir = dirname( dirname( dirname(dirname(__FILE__)))); 
        $newDir = $dir."/public/finish_book/";
        $dir = $dir.'/public/upload_book/';
        $list = scandir($dir);
        unset($list[0]);
        unset($list[1]);
        if(empty($list)) {
            tLog('没有内容');
            return false;
        }
        foreach ($list as $key => $value) {
            $res = rename($dir.$value, $newDir.$value);

            $bookName = str_replace('.txt','',$value);
            $bookUrl = $dir.$value;
            $content = file_get_contents($newDir.$value);
            if(empty($content)) {
                tLog('找不到txt');
                return false;
            }
            // $con = mb_detect_encoding($content, ['cp936','GBK', 'gb2312', 'GB18030', 'ISO-8859-1', 'ASCII', 'UTF-8']);
            // var_dump($con);die;
            // $content = iconv('CP936', "utf-8", $content);
            preg_match_all("(第+[0-9零一二三四五六七八九十两百千万]+(章).*\n)",
                $content,
    $out);
            //添加书籍获取id
            $books_id = (new AdminModel('books'))->addGetId([
                'title'        => $bookName,
                'word_count'   => mb_strlen($content),
                'created_at'   => date('Y-m-d h:i:s',time()),
                'updated_at'   => date('Y-m-d h:i:s',time()),
            ]);
            if(empty($out[0])) continue;
            foreach ($out[0] as $key => $value) {
                if(mb_strlen(trim($value,"\n")) > 50) continue;
                ////////////   计算字数mb_strlen
                $nextSub = $out[0][($key+1)] ?? '';
                $num = 1;
                while(mb_strlen(trim($nextSub,"\n")) > 50) {
                    unset($out[0][($key+$num)]);
                    $num++;
                    $nextSub = $out[0][($key+$num)] ?? '';
                }
                
                if(!empty($nextSub)) $text = substr($content, strlen($value)+strpos($content, $value),(strlen($content) - strpos($content, $nextSub))*(-1));
                else $text = substr($content, strlen($value)+strpos($content, $value));
                if(!$books_id) return false;
                $wordCount = mb_strlen($text);
                $chapterid = (new AdminModel('chapters'))->addGetId([
                    'subhead'        => trim($value,"\n"),
                    'book_id'        => $books_id,
                    'word_count'     => $wordCount,
                    'price'          => ceil($wordCount*6/1000),
                    'displayorder'   => $key+1,
                    'created_at'     => date('Y-m-d h:i:s',time()),
                    'updated_at'     => date('Y-m-d h:i:s',time()),
                ]);


                $tableName = 'chapter_'.$chapterid%100;
                $chap = (new AdminModel($tableName))->addArray([
                    'content'        => $text,
                    'id'             => $chapterid,
                    'created_at'     => date('Y-m-d h:i:s',time()),
                    'updated_at'     => date('Y-m-d h:i:s',time()),
                ]);
                if($chap) echo "$value -- 添加成功 \n";
            }
            // unlink($bookUrl);
            exit();

        }
    }

    function detect_encoding($content) { 
        $list = array('gb2312','cp936',"gb18030",'GBK', 'UTF-8', 'UTF-16LE', 'UTF-16BE', 'ISO-8859-1'); 
        foreach ($list as $item) { 
            $tmp = mb_convert_encoding($content, $item, $item); 
            if (md5($tmp) == md5($content)) return $item; 
        } 
        return false;
    }

}
