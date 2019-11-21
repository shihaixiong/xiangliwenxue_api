<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminModel;

class BooksUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BooksUpload';

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
        $dir = $dir.'/public/text_tmp/';
        // echo $dir;die;
        $list = scandir($dir);

        unset($list[0]);
        unset($list[1]);

        foreach ($list as $key => $value) {
            $bookName = str_replace('.txt','',$value);
            $bookUrl = $dir.$value;
            $content = file_get_contents($dir.$value);
            // $con = mb_detect_encoding($content, ['cp936','GBK', 'gb2312', 'GB18030', 'ISO-8859-1', 'ASCII', 'UTF-8']);
            // var_dump($con);die;
            // $content = iconv('CP936', "utf-8", $content);
            preg_match_all("(第+[0-9零一二三四五六七八九十两百千万]+(章|卷).*\n)",
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
                    // echo $nextSub;
                }
                // echo strlen($content) - strpos($content, $nextSub)*(-1);die;
                // $patt = "/".$value."(.*)".$out[0][$key+1]."/";
                // preg_match("/$value(.*)/",$content,$cont);
                // var_dump( $cont);die;
                if(!empty($nextSub)) $text = substr($content, strlen($value)+strpos($content, $value),(strlen($content) - strpos($content, $nextSub))*(-1));
                else $text = substr($content, strlen($value)+strpos($content, $value));
                if(!$books_id) return false;

                $chapterid = (new AdminModel('chapters'))->addGetId([
                    'subhead'        => trim($value,"\n"),
                    'book_id'        => $books_id,
                    'word_count'     => mb_strlen($text),
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
            unlink($bookUrl);

        }
        die;

        // $txt_name = "将夜";
        $txt_name = $this->argument('data');

        $content = file_get_contents("/Users/wangxin/Desktop/".$txt_name.".txt");
        if(empty($content)) return false;


        $content_arr  = explode("\n\n\n", $content);

        if(count($content_arr) == 1) {
            $content_arr  = explode("\n\n\n", $content);
        }

        //添加书籍获取id
        $books_id = (new AdminModel('books'))->addGetId([
            'title'        => $txt_name,
            'word_count'   => mb_strlen($content),
            'created_at'   => date('Y-m-d h:i:s',time()),
            'updated_at'   => date('Y-m-d h:i:s',time()),
        ]);
        ////////////   计算字数mb_strlen

        if(!$books_id) return false;

        //print_r($books_id);
        foreach($content_arr as $key=>$val){
            //章节名称
            $pos1 = strpos($val,"\n");
            $one  = substr($val, 0, $pos1);

            //内容
            $con  = substr($val, $pos1+1);

            //添加章节获取id
            $chapters_id = (new AdminModel('chapters'))->addGetId([
                'subhead'        => $one,
                'book_id'        => $books_id,
                'word_count'     => mb_strlen($con),
                'displayorder'   => $key+1,
                'created_at'     => date('Y-m-d h:i:s',time()),
                'updated_at'     => date('Y-m-d h:i:s',time()),
            ]);

            

            $tableName = 'chapter_'.$chapters_id%100;
            $chap = (new AdminModel($tableName))->addArray([
                'content'        => $con,
                'id'             => $chapters_id,
                'created_at'     => date('Y-m-d h:i:s',time()),
                'updated_at'     => date('Y-m-d h:i:s',time()),
            ]);
            
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
