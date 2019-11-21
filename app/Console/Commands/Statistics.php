<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminModel;

class Statistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Statistics';

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
        // $this->addAll();
        $this->addYesterday();
    }

    public function addAll() {
        (new AdminModel('statistics'))->delArray(['month'=>date('Ym')]);
        $starttime = date('Y-m-01');
        $j = date("t",strtotime($starttime));         
        for($i=0;$i<$j;$i++){
            $date[] = date('Y-m-d',strtotime($starttime)+$i*86400); //每隔一天赋值给数组
        }

        foreach ($date as $key => $value) {
            $res = $this->addsta($value);
            if($res) tLog($value."添加成功");
        }

    }

    public function addYesterday() {
        $today = date('Y-m-d', time()-86400);
        $res = $this->addsta($today);
        if($res) tLog($today."添加成功");
    }

    public function addSta($today) {
        $isHave = (new AdminModel('statistics'))->getOne(['date'=>$today]);
        if($isHave) return;
        $return['sign_num'] = (new AdminModel('user_sign'))->getCount(['date'=>$today]) ?? 0;
        $return['share_num'] = (new AdminModel('task_log'))->getCount([['type',3],['created_at','>',$today],['created_at','<', date('Y-m-d',strtotime($today)+86400)]]) ?? 0;
        $return['comment_num'] = (new AdminModel('comment'))->getCount([['created_at','>',$today],['created_at','<', date('Y-m-d',strtotime($today)+86400)]]) ?? 0;
        $return['pay_num'] = (new AdminModel('order'))->getSum('money',[['status',1],['created_at','>',$today],['created_at','<', date('Y-m-d',strtotime($today)+86400)]]) ?? 0;
        $return['paid_num'] = (new AdminModel('paid'))->getCount([['created_at','>',$today],['created_at','<', date('Y-m-d',strtotime($today)+86400)]]) ?? 0;
        $return['reg_num'] = (new AdminModel('user'))->getCount([['created_at','>',$today],['created_at','<', date('Y-m-d',strtotime($today)+86400)]]) ?? 0;
        $return['integral_num'] = (new AdminModel('task_log'))->getSum('reward_integral',[['status',1],['created_at','>',$today],['created_at','<', date('Y-m-d',strtotime($today)+86400)]]) ?? 0;
        $return['date'] = $today;
        $return['month'] = date("Ym");
        $res = (new AdminModel('statistics'))->addArray($return);
        if($res) return true;
    }
}
