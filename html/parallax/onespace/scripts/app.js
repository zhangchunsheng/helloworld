// @charset "UTF-8";

$(function(){

// draw line
    var c_1 = document.getElementById("line_1");
    var cxt_1 = c_1.getContext("2d");
    cxt_1.beginPath();
    cxt_1.moveTo(50, 0);
    cxt_1.lineTo(0,125);
    cxt_1.lineWidth = 2;
    cxt_1.strokeStyle = '#0bab43';
    cxt_1.lineCap = 'round';
    cxt_1.stroke();

    var c_2 = document.getElementById("line_2");
    var cxt_2 = c_2.getContext("2d");
    cxt_2.beginPath();
    cxt_2.moveTo(462, 0);
    cxt_2.lineTo(0, 488);
    cxt_2.lineWidth = 2;
    cxt_2.strokeStyle = '#0bab43';
    cxt_2.lineCap = 'round';
    cxt_2.stroke();

    var c_5 = document.getElementById("line_5");
    var cxt_5 = c_5.getContext("2d");
    cxt_5.beginPath();
    cxt_5.moveTo(0,180);
    cxt_5.lineTo(475,5);
    cxt_5.lineWidth = 2;
    cxt_5.strokeStyle = '#0bab43';
    cxt_5.lineCap = 'round';
    cxt_5.stroke();



    var c_3 = document.getElementById("line_3");
    var cxt_3 = c_3.getContext("2d");
    cxt_3.beginPath();
    cxt_3.moveTo(41,27);
    cxt_3.lineTo(141,169);
    cxt_3.lineWidth = 2;
    cxt_3.strokeStyle = '#fff';
    cxt_3.lineCap = 'round';
    cxt_3.stroke();

    var c_4 = document.getElementById("line_4");
    var cxt_4 = c_4.getContext("2d");
    cxt_4.beginPath();
    cxt_4.moveTo(32, 32);
    cxt_4.lineTo(151,61);
    cxt_4.lineWidth = 2;
    cxt_4.strokeStyle = '#fff';
    cxt_4.lineCap = 'round';
    cxt_4.stroke();


    // BEGIN Navigation
    var $root = $('html, body');
    $('a.nav').click(function() {
        var href = $.attr(this, 'href');
        $root.animate({
            scrollTop: $(href).offset().top
        }, 1500, function () {
            window.location.hash = href;
        });
        return false;
    });
    // END Navigation

    // BEGIN FancyBox
    $(".fancybox").fancybox();

    $('.fancybox-media').attr('rel', 'media-gallery').fancybox({
        openEffect : 'elastic',
        closeEffect : 'elastic',
        openSpeed: 250,
        prevEffect : 'none',
        nextEffect : 'none',
        padding: 0,
        arrows : false,
        helpers : {
            media : {},
            buttons : {},
            overlay : {
                closeClick : false,
                speedOut   : 200,
                showEarly  : true,
                css        : {
                    'background-image':'none',
                    'background-color':'rgba(255,255,255,.9)',
                },
                locked     : true
            }
        }
    });
    // END FancyBox


    // BEGIN Video Controls
    // $("#section_2").hover(
    //     function(e){
    //         this.getElementsByTagName('video')[0].play();
    //         this.getElementsByTagName('video')[1].play();
    //     },
    //     function(e){
    //         this.getElementsByTagName('video')[0].pause();
    //         this.getElementsByTagName('video')[1].pause();
    //     }
    // );
    // END Video Controls

    $('#section_7_pic .ripple').click(function(){
        if($(this).siblings('i').css('visibility') == 'hidden'){
            $(this).siblings('i, canvas, dl').animate({
                'opacity': 1
            },200).css("visibility", 'visible');
        } else {
            $(this).siblings('i, canvas, dl').animate({
                'opacity': 0
            },200).css("visibility", 'hidden');
        }
    })



// overlay
    $('.overlay_btn').click(function(){
        if($('body').hasClass('form_overlay_enabled')){
            $('body').removeClass('form_overlay_enabled');
        } else {
            $('body').addClass('form_overlay_enabled');
        }

    })
    $('.form_overlay_close').click(function(){
        $('body').removeClass('form_overlay_enabled');
    })


//share btn toggle
    var share_opend = false;
    $('#share_toggle').click(function(){
        var $t = $(this),
            share = $('#share');

        if(share_opend === false){
            $t.animate({
                'top': 40,
                'height':20
            }, 200).html('<i class="fa fa-angle-up"></i>').css({
                'line-height' : '20px'
            });
            share.css({
                'background-color':'rgba(0,0,0,0.8)',
                'border-radius':'0 0 0 5px',
            }).animate({
                'top': -5
            }, 110)
            share_opend = true;
        } else {
            $t.animate({
                'top': 0,
                'height' : 40
            }, 100).html('分享').css({
                'line-height' : '40px'
            });
            share.animate({
                'top': -50
            }, 200, function(){
                share.css({
                    'background-color':'transparent',
                    'border-radius':0,
                });
            })
            share_opend = false;
        }

    })

//share to SNS
    var n = window.document;
    var m = "穿在身上的手机娱乐搜索",// title
        g = "Boom!!! @豌豆荚 One-Space 发布——完美集成「手机娱乐搜索」及「增强现实」的智能服装，彻底改变局限在小屏幕上的娱乐体验，通过全息投影创造「沉浸式空间」呈现丰富内容，还可以将远方好友投影到身边实时互动！猛戳右边申请试用，仅限今天！http://www.wandoujia.com/onespace", //description
        text_for_twitter = "Boom!!! 豌豆荚 One-Space 发布——完美集成「手机娱乐搜索」及「增强现实」的智能服装，彻底改变局限在小屏幕上的娱乐体验，通过全息投影创造「沉浸式空间」呈现丰富内容，还可以将远方好友投影到身边实时互动！猛戳右边申请试用，仅限今天！", // text weibo

        // pic
        w = "http://img.wdjimg.com/mms/banner/9/37/b6fe7d5b9c93f914d64003cda0db5379.jpeg",
        // static URL
        url = "http%3A%2F%2Fwww.wandoujia.com%2Fonespace",

        // douban
        D = "http://www.douban.com/share/service?image="+ w +"&href="+ url +"&name="+ m +"&text=" + g,

        // weibo
        E = "http://service.weibo.com/share/share.php?appkey=1483181040&relateUid=1727978503&title=" + g + "&url=&pic=" + w,

        // facebook
        S =  "https://www.facebook.com/sharer/sharer.php?s=100&p[title]="+ encodeURIComponent(m) +"&p[summary]="+ g +"&p[url]="+ url +"&p[images]="+ w,

        // google+
        x = "https://plus.google.com/share?url=" + url,

        // twitter
        T = "https://twitter.com/intent/tweet?related=wandoujia&text=" + text_for_twitter + "&url=" + url,

        // renren
        N = "http://widget.renren.com/dialog/share?title=" + encodeURIComponent(m) + "&resourceUrl="+ url +"&pic=" + w + "&description=" + g,

        // QQ-zone
        Q = "http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url="+ url +"&title="+ m +"&pics="+ w +"&summary=" + g,

        // baidu tieba
        B = "http://tieba.baidu.com/f/commit/share/openShareApi?title="+ m +"&desc="+ g +"&comment="+ g +"&pic="+ w +"&url=" + url;



    $(n).on("click", "#share li", function() {
        var t = $(this),
            n = $("<a>");
        n.attr({
            target: "_blank"
        });
        switch (t[0].id) {
            case "s_douban":
                n.attr({
                    href: D
                });
                break;
            case "s_weibo":
                n.attr({
                    href: E
                });
                break;
            case "s_renren":
                n.attr({
                    href: N
                });
                break;
            case "s_q-zone":
                n.attr({
                    href: Q
                });
                break;
            case "s_baidutieba":
                n.attr({
                    href: B
                });
                break;
            case "s_twitter":
                n.attr({
                    href: T
                });
                break;
            case "s_facebook":
                n.attr({
                    href: S
                });
                break;
            case "s_googleplus":
                n.attr({
                    href: x
                })
        }!$.browser.msie && !$.browser.mozilla ? n[0].click() : e.open(n.attr("href"));
        // _gaq.push(["_trackEvent", "onespace", "share", t[0].id])
    });


// scroll
    var section_2 = document.getElementById('section_2');
    var section_3 = document.getElementById('section_3');
    var section_5 = document.getElementById('section_5');
    var section_7 = document.getElementById('section_7');

    window.onscroll = function(){
        var scrollTop = $(document).scrollTop();

        var section_2_offset_y =  section_2.offsetTop;
        var section_3_offset_y =  section_3.offsetTop;
        var section_7_offset_y =  section_7.offsetTop;

        var gap_2 =  section_2_offset_y - scrollTop;
        var gap_3 =  section_3_offset_y - scrollTop;
        var gap_7 =  section_7_offset_y - scrollTop;


        if( 500 > gap_2 && gap_2 > -200 && window.innerWidth > 768){
            addBili(2);
        } else {
            removeBili(2);
        }

        if( 200 > gap_3 && gap_3 > -500 && window.innerWidth > 768){
            $('#shape').addClass('spin');
            $('#section_3 .ripple').addClass('bili');
        } else {
            $('#shape').removeClass('spin');
            removeBili(3);
        }

        if( 400 > gap_7 && gap_7 > -500 && window.innerWidth > 768){
            addBili(7);
        } else {
            removeBili(7);
        }
    }

    function removeBili(elem){
        $('#section_'+ elem +' .ripple').removeClass('bili');
    }

    function addBili(elem){
        $('#section_'+ elem +' .ripple').addClass('bili');
        $('#line_3, #line_4, #section_7_pic .feature_icons .one_icon, #section_7_pic .feature_icons dl').css({
            'opacity': 1,
            'visibility': 'visible'
        })
    }



// preview
    $('#preview_slider li a').click(function(){
        var $t = $(this),
            $curr_li = $t.parents('li').eq(0),
            order = $curr_li.data('order');

        $('#preview_slider li').removeClass('active');
        $curr_li.addClass('active');


        $('#curr_preview').attr('src', '/onespace/images/clothe/model_detail_'+ order +'_big.jpg')
    })


    $('#size_table > a').click(function(){
        $('table.tg').toggle();
        $(this).find('i').toggleClass('fa-caret-up')
    })

    $('input[name=gender]').change(function(){
        if($(this).val() == 'men'){
            $('#women_size').hide();
            $('#men_size').show();
        }
        if($(this).val() == 'women'){
            $('#women_size').show();
            $('#men_size').hide();
        }
    })

    $('form#apply_form').submit(function(){
        var form = $(this),
            btn = form.find('button');

        btn.attr('disabled','disabled').addClass('loading').html('<i class="fa fa-spinner fa-spin"></i> 请稍候...');

        setTimeout(function(){

            // reset
            $('body').removeClass('form_overlay_enabled');
            document.getElementById("apply_form").reset();
            btn.removeAttr('disabled').removeClass('loading').html('填好了，提交申请');

            window.location.href = './april_fool.html';

        }, 1200);

        return false;
    })

})



