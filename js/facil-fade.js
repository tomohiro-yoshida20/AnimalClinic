$(document).ready(function() {
            //PC環境（スマホ以外）の場合はフェードイン適用
            if (window.matchMedia( '(min-width: 481px)' ).matches) {　//切り替える画面サイズ
                
                //最初の項目のフェードイン設定(ロード完了時)
                var fig = document.getElementById('fadein_first_fig');
                let p = document.getElementById('fadein_first_p');
//                var care_fig =document.querySelector('.slide1 imgwrap');
//                let care_p =document.querySelector('#care .slide1+p');
                    fig.classList.add('fadein_first');
                    p.classList.add('fadein_first');
//                    care_fig.classList.add('fadein_first');
//                    care_p.classList.add('fadein_first');
    
        //画面スクロールによるフェードイン設定
            $(window).on('scroll', function () {
                    var Pheight = $(document).innerHeight(); //ページ全体の高さ
                    var Wheight = $(window).innerHeight(); //ウィンドウの高さ
                    var bottom = Pheight - Wheight; //ページ全体の高さ - ウィンドウの高さ = ページの最下部位置

        //            変化前のcss定義-テキスト部分
                    $('.fadein_txt1').css({
                        opacity:0,
        //                transform: 'translateY(50px)'
                    });
                    $('.fadein_txt2').css({
                        opacity:0,
        //                transform: 'translateY(50px)'
                    });
                    $('.fadein_txt3').css({
                        opacity:0,
        //                transform: 'translateY(50px)'
                    });
                    $('.fadein_txt4').css({
                        opacity:0,
        //                transform: 'translateY(50px)'
                    });
                    $('.fadein_txt5').css({
                        opacity:0,
        //                transform: 'translateY(50px)'
                    });
                    $('.fadein_txt6').css({
                        opacity:0,
        //                transform: 'translateY(50px)'
                    });

        //            変化前のcss定義-画像部分
                     $('.fig2').css({
                        opacity:0,
                        transform: 'translateX(50px)'
                    });
                    $('.fig3').css({
                        opacity:0,
                        transform: 'translateX(-50px)'
                    });
                    $('.fig4').css({
                        opacity:0,
                        transform: 'translateX(50px)'
                    });

                //1割下までスクロールした時に実行
                    if (bottom　* .1 <= $(window).scrollTop()) {        
                        $('.fadein_txt1').addClass('fadein_text_odd');
        //                $('.fig2').addClass('fadein_fig')
                    }

                //3割下までスクロールした時に実行
                    if (bottom　* .3 <= $(window).scrollTop()) {        
                        $('.fadein_txt2').addClass('fadein_text_even');
                        $('.fig2').addClass('fadein_fig');
                    }

                //4割下までスクロールした時に実行
                    if (bottom * .4 <= $(window).scrollTop()) {        
                        $('.fadein_txt3').addClass('fadein_text_odd');
                        $('.fig3').addClass('fadein_fig');
                    }

                //5割下までスクロールした時に実行
                    if (bottom　* .5 <= $(window).scrollTop()) {        
                        $('.fadein_txt4').addClass('fadein_text_even');
        //                $('.fig2').addClass('fadein_fig')
                    }

                //8割下までスクロールした時に実行
                    if (bottom　* .8 <= $(window).scrollTop()) {        
                        $('.fadein_txt5').addClass('fadein_text_odd');
        //                $('.fig3').addClass('fadein_fig')
                    }

                //一番下までスクロールした時に実行
                    if (bottom  * .9 <= $(window).scrollTop()) {        
                        $('.fadein_txt6').addClass('fadein_text_even');
                        $('.fig4').addClass('fadein_fig');
                    }
                });
        } 
});