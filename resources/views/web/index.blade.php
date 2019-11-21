<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <title>香狸文学</title>
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/animate.min.css">
    <link rel="stylesheet" href="/css/swiper.min.css">
</head>
<body>
    <div class="pc-home-wrapper">
        <div class="pc-home-header">
                <div class="header-main animated bounceInDown">
                    <div class="logo-img">
                        <a href="/qrocde"><img src="/images/pasted-image.png" style='height: 100%;'></a>
                    </div>
                    <div class="dowanload-android">
                        <a href="#"><img src="/images/android.png"></a>
                        <div class="download-img">
                            <img src="/images/qr-code.png">
                        </div>
                    </div>
                    <div class="dowanload-store">
                        <a href="#"><img src="/images/appstore.png"></a>
                        <div class="download-img">
                            <img src="/images/qr-code.png">
                        </div>
                    </div>
                </div>
            </div>
            <div class="pc-home-main">
                <div class="banner-list">
                    <div class="banner-left animated bounceInLeft">
                        <img src="/images/home-img1.png">
                    </div>
                    <div class="banner-right animated bounceInRight">
                        <img src="/images/home-img2.png">
                    </div>
                </div>
            </div>
            <div class="pc-home-footer animated bounceInUp">
                <div class="company-introduction"><span class="line"></span><span class="text">公司介绍</span><span class="line"></span></div>
                <div class="company-desc">
                        香狸文学app是一款有特色、正版书籍大量优惠免费、好友推荐分享热心服务的小说app</div>
            </div>
    </div>

    <div class="mobile-home-wrapper">
            <div class="mobile-home-header animated slideInDown">
                <div class="logo-img ">
                    <a href="/qrocde"><img src="/images/pasted-image.png" style='height: 100%;width:auto;'></a>
                </div>
                <div class="dowanload-android n">
                    <a href="#"><img src="/images/android.png"></a>
                </div>
                <div class="dowanload-store ">
                    <a href="#"><img src="/images/appstore.png"></a>
                </div>
                <!-- <div class="menu-btn"></div> -->
            </div>
        
            <div class="mobile-home-main">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide animated slideInDown"> <img src="/images/home-img1.png"></div>
                        <div class="swiper-slide animated slideInDown"> <img src="/images/home-img2.png"></div>
                    </div>
                        <!-- 如果需要导航按钮 -->
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
    </div>


    <script src="/js/common.js"></script>
    <script src="/js/swiper.min.js"></script>
    <script>        
        var mySwiper = new Swiper ('.swiper-container', {
            direction: 'vertical', // 垂直切换选项
            loop: true, // 循环模式选项
            
            // 如果需要分页器
            pagination: {
                el: '.swiper-pagination',
            },
            
            // 如果需要前进后退按钮
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            
            // 如果需要滚动条
            scrollbar: {
                el: '.swiper-scrollbar',
            },
        })        
    </script>
</body>
</html>