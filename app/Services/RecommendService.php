<?php

namespace App\Services;

use App\Models\CommonModel;
/**
 * Class EmailService
 *
 * @package \App\Services
 */
/**
 * Class EmailService
 *
 * @package App\Services
 */
class RecommendService
{
    public function getRecommend($id, $limit = 0, $offset = 0) {
        $res = (new CommonModel('book_recommend'))->getOffset( ['rec_id'=>$id], '', $limit, 'displayorder', 'asc', $offset);
        return $res;
    }

    public function getRecType($channel,$position = 1) {
        $res = (new CommonModel("recommend_type"))->getDb()->where(['channel'=> $channel,'position'=>$position])->orderBy('displayorder','asc')->get();
        return json_decode(json_encode($res),true);
    }
}
