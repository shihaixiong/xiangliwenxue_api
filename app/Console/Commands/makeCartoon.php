<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminModel;

class makeCartoon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'makeCartoon';

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
        $dir = dirname( dirname( dirname(dirname(__FILE__)))); 
        $dir = $dir."/public/cartoon/";
        // $dir = $dir.'/public/export_book/';
        // echo $dir;die;
        $list = scandir($dir);

        unset($list[0]);
        unset($list[1]);

        foreach ($list as $key => $value) {
            if(strpos($value,".txt") === false) continue;
            tLog($value);

            $txtName = $value;
            $bookName = str_replace('.txt','',$value);
            $bookUrl = $dir.$value;
            $content = file_get_contents($dir.$value);
            // $con = mb_detect_encoding($content, ['cp936','GBK', 'gb2312', 'GB18030', 'ISO-8859-1', 'ASCII', 'UTF-8']);
            // var_dump($con);die;
            // $content = iconv('CP936', "utf-8", $content);
            $isset = (new AdminModel('books'))->getOne(['title'=>$bookName]);
            if(empty($isset)) {
                tLog('书籍不存在, 添加');
                $isset['id'] = (new AdminModel('books'))->addGetId(['title'=>$bookName,'created_at'=>date( "Y-m-d H:i:s", time()),'type'=>2]);
            }
            $articleid = $isset['id'];
            preg_match_all("(第+[0-9零一二三四五六七八九十两百千万]+(话|章).*\n)",$content,$out);

            if(empty($out[0])) continue;
            foreach ($out[0] as $key => $value) {
                $nextSub = $out[0][($key+1)] ?? '';
                // $num = 1;
                // while(mb_strlen(trim($nextSub,"\n")) > 20) {
                //     unset($out[0][($key+$num)]);
                //     $num++;
                //     $nextSub = $out[0][($key+$num)] ?? '';
                // }
                
                if(!empty($nextSub)) $text = substr($content, strlen($value)+strpos($content, $value),(strlen($content) - strpos($content, $nextSub))*(-1));
                else $text = substr($content, strlen($value)+strpos($content, $value));
                if(!$articleid) return false;
                $wordCount = mb_strlen($text);
                $chapterid = (new AdminModel('chapters'))->addGetId([
                    'subhead'        => trim($value,"\n"),
                    'book_id'        => $articleid,
                    'word_count'     => 0,
                    'price'          => 49,
                    'displayorder'   => $key+1,
                    'created_at'     => date('Y-m-d h:i:s',time()),
                    'updated_at'     => date('Y-m-d h:i:s',time()),
                ]);


                $tableName = 'chapter_'.$chapterid%100;
                $chap = (new AdminModel($tableName))->addArray([
                    'content'        => json_encode(explode("\n", $text)),
                    'id'             => $chapterid,
                    'created_at'     => date('Y-m-d h:i:s',time()),
                    'updated_at'     => date('Y-m-d h:i:s',time()),
                ]);
                if($chap) echo "$value -- 添加成功 \n";
            }

            // unlink($bookUrl);

        }
        die;

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
