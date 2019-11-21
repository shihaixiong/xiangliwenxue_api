<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminModel;

class UpdateBook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateBook';

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
        $newDir = $dir."/public/update_book/";
        $dir = $dir.'/public/export_book/';
        // echo $dir;die;
        $list = scandir($dir);

        unset($list[0]);
        unset($list[1]);

        foreach ($list as $key => $value) {
            $res = rename($dir.$value, $newDir.$value);
            $txtName = $value;
            $log = (new AdminModel('update_book_log'))->editArray(['title'=>$value,'status'=>0],[
                'log' => '正在更新',
                'status' => 1,
            ]);
            $bookName = str_replace('.txt','',$value);
            $bookUrl = $dir.$value;
            $content = file_get_contents($newDir.$value);
            // $con = mb_detect_encoding($content, ['cp936','GBK', 'gb2312', 'GB18030', 'ISO-8859-1', 'ASCII', 'UTF-8']);
            // var_dump($con);die;
            // $content = iconv('CP936', "utf-8", $content);
            $isset = (new AdminModel('books'))->getOne(['title'=>$bookName]);
            if(empty($isset)) {
                tLog('书籍不存在');

            }
            $articleid = $isset['id'];
            $data = explode("=====", $content);
            $chapters = (new AdminModel('chapters'))->getAll(['book_id'=>$articleid]);
            foreach ($chapters as $key => $value) {
                $have[] = [
                    'subhead' => $value['subhead'],
                ];
            }
            $num = 0;
            $count = count($chapters);
            $export_count = 0;
            $success_count = 0;
            $error_count = 0;
            $no_count = 0;
            foreach ($data as $key => $value) {
                if(empty($value)) continue;
                $chapter = explode("===",$value);
                $subhead = str_replace("\n",'',trim($chapter[0]));
                $content = $chapter[1];
                // $isHave = (new AdminModel('chapters'))->getOne(['book_id'=>$articleid,'subhead'=>$subhead]);
                // if($isHave) {
                $export_count++;
                //如果章节存在 进行比对 
                if(!empty($have[$num]['subhead']) && $have[$num]['subhead'] == trim($subhead)) {
                    //章节存在 暂不做处理 直接跳过
                    tLog("$subhead -- 暂不处理 \n");
                    $num++;
                    $no_count++;
                    continue;
                }else{
                    $chapterid = (new AdminModel('chapters'))->addGetId([
                        'subhead'        => trim($subhead,"\n"),
                        'book_id'        => $articleid,
                        'word_count'     => mb_strlen($content),
                        'displayorder'   => $num+1,
                        'created_at'     => date('Y-m-d h:i:s',time()),
                        'updated_at'     => date('Y-m-d h:i:s',time()),
                    ]);


                    $tableName = 'chapter_'.$chapterid%100;
                    $chap = (new AdminModel($tableName))->addArray([
                        'content'        => $content,
                        'id'             => $chapterid,
                        'created_at'     => date('Y-m-d h:i:s',time()),
                        'updated_at'     => date('Y-m-d h:i:s',time()),
                    ]);
                    if($chap) tLog("$subhead -- 添加成功 \n");
                    $success_count++;
                }

                // }
                $num++;
            }
            $log = (new AdminModel('update_book_log'))->editArray(['title'=>$txtName,'status'=>1],[
                'log' => "原先本书共计:" .$count. "章, 导入txt共计:". $export_count ."章, 无修改:".$no_count."章,新增:".$success_count."章",
                'status' => 2,
            ]);

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
