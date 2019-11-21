<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::middleware('checkToken')->group(function(){
    Route::get('book/shelf','BookShelfController@bookShelf');
    Route::post('book/sign','BookShelfController@sign');
    Route::get('fun/init','FunController@init');
    Route::get('fun/shareInfo','FunController@shareInfo');
    Route::get('fun/shareFin','FunController@shareFin');
    Route::post('user/login','UserController@login');
    Route::get('user/my','UserController@userInfo');
    Route::get('user/myInfo','UserController@userDesc');
    Route::get('user/sendcode','UserController@sendCode');
    Route::post('user/setPwd','UserController@setPwd');
    Route::post('user/updInfo','UserController@updUserInfo');
    Route::post('user/useInvite','UserController@useInvite'); //邀请码
    Route::get('user/getUserSaleLog','UserController@getUserSaleLog'); //获取分销记录
    Route::get('user/paid','UserController@myPaid');
    Route::get('user/lastRead','UserController@lastRead');
    Route::post('user/wechatLogin','UserController@wechatLogin');
    Route::get('user/getRecord','UserController@getRecord');
    Route::get('user/getRatio','UserController@getRatio');
    Route::post('user/exchange','UserController@exchange');
    
    Route::get('book/chapters','BookController@chapters');
    Route::get('book/content','BookController@chapter');
    Route::post('book/paid','BookController@paid');
    Route::get('book/info','BookController@getBookInfo');
    Route::get('book/cartoon','BookController@getCartoonContent');

    Route::post('book/shelf/add','BookShelfController@addShelf');
    Route::post('book/shelf/del','BookShelfController@delShelf');
    Route::get('book/detail','BookController@detail');
    Route::get('bestchoice/getChannel','BestChoiceController@getChannel');
    Route::post('book/comment/add','BookController@addComment');
    Route::post('book/comment/like','BookController@commentLike');
    Route::get('book/comment/commentList','BookController@commentList');
    Route::get('book/comment/info','BookController@commentInfo');
    Route::post('book/autoPaid','BookController@autoPaid');
    Route::get('bestchoice/list','BestChoiceController@list');
    Route::get('bestchoice/list/more','BestChoiceController@getList');


	########美图########
	Route::get('beautifulImg/list','BeautifulImgController@list');//列表
	Route::get('beautifulImg/details','BeautifulImgController@details');//详情
    Route::post('beautifulImg/praise','BeautifulImgController@praise');//点赞
    Route::get('beautifulImg/getSort','BeautifulImgController@getSort');//分类
	Route::get('beautifulImg/upload','BeautifulImgController@upload');//下载

    ########搜索########   
    Route::get('searchBook/search','SearchBookController@search');//搜索
    Route::get('searchBook/searchList','SearchBookController@searchList');//搜索记录

    ########书库########   
    Route::get('bookStack/list','BookStackController@list');//列表
    Route::get('bookStack/navigation','BookStackController@navigation');//导航
    Route::get('bookStack/rankingList','BookStackController@rankingList');//排行榜

    ########商城########   
    Route::get('shop/list','ShopController@list');//列表
    Route::get('shop/details','ShopController@details');//详情
    Route::post('shop/purchase','ShopController@purchase');//购买
    Route::get('shop/purchaseRecord','ShopController@purchaseRecord');//购买记录
    Route::post('shop/applePayCheck','ShopController@applePayCheck');//苹果支付回调

    Route::get('shop/pay','ShopController@pay');//列表
    Route::get('shop/payConfig','ShopController@payConfig');//列表
    Route::get('shop/wechatpay','ShopController@wechatpay');//列表
    Route::get('shop/googlePlayCheck', 'ShopController@googlePlayCheck');//google 支付
    Route::get('shop/withdrawal','ShopController@withdrawal');//提现
    Route::post('pay/makeOrder','ShopController@makeOrder');//apple支付 生成订单

    ########任务中心######
    Route::get('task/list','TaskController@getTaskList');//获取任务中心列表
    Route::get('task/finish','TaskController@finishTask');//完成任务

});

Route::post('pay/alipay/callback','ShopController@alipayCallback');//支付回调
Route::post('pay/wechat/callback','ShopController@wechatCallback');//微信回调
 //导书接口
Route::post("import/addBook", "ImportController@addBook");//添加书籍
Route::post("import/addChapter", "ImportController@addChapter");//添加章节
Route::get("import/getChapterList", "ImportController@getChapterList");//获取章节列表

Route::get('/user/invite', "UserController@myInvite");

Route::get('agreement',"WebController@agreement");
Route::get('agreement2',"WebController@agreement2");
// Route::get();
