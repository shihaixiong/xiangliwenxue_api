<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
   


    /**
     * 书籍
     *
     */
    // $router->resource('book', BookController::class);
    // //章节
    // $router->resource('chapter', ChapterController::class);

    //上传txt
    $router->any('/bookUpload/index', 'BookUploadController@index');
    $router->any('/bookUpload/updateBook', 'BookUploadController@updateBook');
    //书籍列表
    $router->get('/book/bookIndex', 'BookController@bookIndex');
    $router->get('/book/bookGetIndex', 'BookController@bookGetIndex');
    //添加书籍
    $router->get('/book/createBook', 'BookController@createBook');
    $router->post('/book/addBook', 'BookController@addBook');
    //编辑书籍
    $router->get('/book/editBook', 'BookController@editBook');
    $router->post('/book/updateBook', 'BookController@updateBook');
    //删除书籍
    $router->get('/book/delBook', 'BookController@delBook');
    //章节列表
    $router->get('/book/chapterIndex', 'BookController@chapterIndex');
    $router->get('/book/chapterGetIndex', 'BookController@chapterGetIndex');
    //章节添加
    $router->get('/book/createChapter', 'BookController@createChapter');
    $router->post('/book/addChapter', 'BookController@addChapter');
    //章节编辑
    $router->get('/book/editChapter', 'BookController@editChapter');
    $router->post('/book/updateChapter', 'BookController@updateChapter');
    //更新书籍的状态表
    $router->get('/book/updateBookLog', 'BookController@updateBookLog');
    //删除章节
    $router->get('/book/delChapter', 'BookController@delChapter');
    //书籍类型
    $router->resource('bookSort', BookSortController::class);
    //美图类型
    $router->resource('imgSort', beautifulImgSortController::class);
    //获取书籍类型select
    $router->get('/book/bookSort', 'BookController@bookSort');
    //获取全部分类
    $router->get('/book/getBookSort', 'BookController@bookSort');
    //书籍频道
    $router->resource('bookChannel', ChannelController::class);
    //书籍频道select
    $router->get('/book/bookChannel', 'BookController@bookChannel');
    
    $router->get('/book/export', 'BookController@bookExport');
    $router->get('/recommend/getBookId', 'BookController@getBookId');
    
    $router->get('/config/index', 'ConfigController@index');
    $router->post('/config/update', 'ConfigController@update');
    // $router->get('/recommend/getBookId', 'BookController@getBookId');



    /**
     * 推荐位
     *
     */
    //推荐位-列表
    $router->get('/recommend/recommendIndex', 'RecommendController@recommendIndex');
    $router->get('/recommend/recommendGetIndex', 'RecommendController@recommendGetIndex');
    //推荐位-添加
    $router->get('/recommend/createRecommend', 'RecommendController@createRecommend');
    $router->post('/recommend/addRecommend', 'RecommendController@addRecommend');

    //推荐位-编辑
    $router->get('/recommend/editRecommend', 'RecommendController@editRecommend');
    $router->post('/recommend/updateRecommend_', 'RecommendController@updateRecommend_');
    //推荐位-删除
    $router->any('/recommend/delRecommend', 'RecommendController@delRecommend');

    //推荐位详情列表
    $router->any('/recommend/recommendInfoIndex', 'RecommendController@recommendInfoIndex');
    $router->any('/recommend/recommendInfoGetIndex', 'RecommendController@recommendInfoGetIndex');
    //推荐位详情内容-添加
    $router->get('/recommend/createRecommendInfo', 'RecommendController@createRecommendInfo');
    $router->post('/recommend/addRecommendInfo', 'RecommendController@addRecommendInfo');
    //推荐位详情内容-编辑
    $router->get('/recommend/editRecommendInfo', 'RecommendController@editRecommendInfo');
    $router->post('/recommend/updateRecommendInfo', 'RecommendController@updateRecommendInfo');
    //推荐位详情内容-删除
    $router->get('/recommend/delRecommendInfo', 'RecommendController@delRecommendInfo');

    //推荐位添加书籍时 查看书籍id 是否存在
    $router->get('/recommend/isBook', 'RecommendController@isBook');

    //书架推荐位
    $router->get('/recommend/shelfRec', 'RecommendController@shelfRec');
    $router->post('/recommend/updShelfRec', 'RecommendController@updShelfRec');

    /**
     * 用户管理
     *
     */
    //用户列表
    $router->get('/user/userIndex', 'UserController@userIndex');
    $router->get('/user/userGetIndex', 'UserController@userGetIndex');
    $router->get('/user/upd', 'UserController@upd');
    $router->post('/user/updDo', 'UserController@updDo');



    /**
     * 美图管理
     *
     */
    $router->resource('beautifulImg', BeautifulImgController::class);



    /**
     * 积分商城
     *
     */
    //商品分类
    $router->resource('goodsType', GoodsTypeController::class);
    //商品
    $router->resource('goods', GoodsController::class);
    //订单列表
    $router->get('/order/orderIndex', 'IntegralOrderController@orderIndex');
    $router->get('/order/orderGetIndex', 'IntegralOrderController@orderGetIndex');
    //修改物流信息
    $router->get('/order/updateExpress', 'IntegralOrderController@updateExpress');


    /**
     * 提现
     *
     */
    $router->get('/withdraw/withdrawIndex', 'WithdrawController@withdrawIndex');
    $router->get('/withdraw/withdrawGetIndex', 'WithdrawController@withdrawGetIndex');
    $router->get('/withdraw/updateStatus', 'WithdrawController@updateStatus');
    $router->get('/withdraw/updateStatusNo', 'WithdrawController@updateStatusNo');


    //统计
    $router->get("statistics/composite", "StatisticsController@statisticsIndex");
    $router->get("statistics/pay", "StatisticsController@pay");
    $router->get("statistics/getTodayPay", "StatisticsController@getTodayPay");
    $router->get("statistics/bookExpend", "StatisticsController@bookExpend");
    $router->get("statistics/info", "StatisticsController@bookInfo");
    $router->get("statistics/user", "StatisticsController@getUser");
    $router->get("statistics/userInfo", "StatisticsController@userInfo");

    //推广
    $router->get("share/index", 'ShareController@index');
    $router->get("share/add", 'ShareController@add');
    $router->get("share/del", 'ShareController@del');
    $router->get("share/upd", 'ShareController@upd');
    $router->post("share/update", 'ShareController@update');
    $router->post("share/addspread", 'ShareController@addspread');
    $router->get("share/count", 'ShareController@count');
   

     //漫画列表
    $router->get('/cartoon/bookIndex', 'CartoonController@bookIndex');
    $router->get('/cartoon/bookGetIndex', 'CartoonController@bookGetIndex');
    //添加漫画
    $router->get('/cartoon/createBook', 'CartoonController@createBook');
    $router->post('/cartoon/addBook', 'CartoonController@addBook');
    //编辑漫画
    $router->get('/cartoon/editBook', 'CartoonController@editBook');
    $router->post('/cartoon/updateBook', 'CartoonController@updateBook');
    //删除漫画
    $router->get('/cartoon/delBook', 'CartoonController@delBook');
    //章节列表
    $router->get('/cartoon/chapterIndex', 'CartoonController@chapterIndex');
    $router->get('/cartoon/chapterGetIndex', 'CartoonController@chapterGetIndex');
    //章节添加
    $router->get('/cartoon/createChapter', 'CartoonController@createChapter');
    $router->post('/cartoon/addChapter', 'CartoonController@addChapter');
    //章节编辑
    $router->get('/cartoon/editChapter', 'CartoonController@editChapter');
    $router->post('/cartoon/updateChapter', 'CartoonController@updateChapter');
    //删除章节
    $router->get('/cartoon/delChapter', 'CartoonController@delChapter');
    //获取漫画类型select
    $router->get('/cartoon/bookSort', 'CartoonController@bookSort');
    //获取全部分类
    $router->get('/cartoon/getBookSort', 'CartoonController@bookSort');
    //漫画频道select
    $router->get('/cartoon/bookChannel', 'CartoonController@bookChannel');
    $router->get('/down/list', 'DownController@getDownList');
    $router->get('/down/add', 'DownController@add');
    $router->post('/down/addDown', 'DownController@addDown');
    $router->get('/down/del', 'DownController@del');
});
