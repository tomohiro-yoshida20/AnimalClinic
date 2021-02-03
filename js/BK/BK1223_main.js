'use strict'
{
    /* 足跡の実装 */

    /* 最初の初期y座標ポジション */
    let nowPosition = 0;
    /* スクロール時の次のy座標ポジション */
    let nextPosition;
    /* y座標の移動 */
    let diff;
    /* 左右の足判定　要素 */
    let side = 0;
    
    //足跡削除用関数
    let deleteFoot = function(){
//        console.log('delete!');
        let objBody = document.getElementsByTagName("body")[0];
      //足跡追加の連番。実行回数と連動して追加された足跡ごとに削除を行う
      let elements = document.querySelectorAll('body > div.walk')[0];
      if (elements !== null){
        objBody.removeChild(elements);
        }
    }
    
    window.addEventListener('scroll', function() {
        
        nextPosition = window.pageYOffset;
        diff = nextPosition - nowPosition;
        
        /* 左右に散らす */
        let sidePosition = Math.floor(Math.random() * 20);
        
        /* 歩幅をランダム生成 */
        let walk_span = 10 - Math.floor(Math.random() * 5);
        
        /* 足跡の角度をランダム生成(-30～30deg)    */
        let trait_deg = Math.floor(Math.random() * 30) - 30;

        if(diff % walk_span == 0) {
            

            /* ターゲットを取得・生成 */
            let objBody = document.querySelector('body');
            let foot = document.createElement('div');
            foot.classList.add('walk');
            
            /* 足跡の角度を調整 */
            foot.style.transform = `rotate(${trait_deg}deg)`;

            
            /* 左右の判定 */
            if(side % 2 == 1) {
                foot.classList.add('even');
            } else {
                foot.classList.add('odd');              
            }
            foot.style.marginRight = sidePosition + 'px';


            /* スクロール方向の判定 */
            /* diffが正のとき下スクロール */
            if(diff > 0) {
            foot.classList.add('down');
            /* 出現位置の設定 */
            foot.style.top = 400 + nowPosition + 'px';
            /* diffが負のとき上スクロール */
            } else {
            foot.classList.add('up');
            /* 出現位置の設定 */
            foot.style.top = 150 + nowPosition + 'px';
            }
            

            /* body内に描画 */
            objBody.appendChild(foot);
            
            /* 一つ処理が終わるごとに現在位置を更新 */
            nowPosition = nextPosition;
            /* 次の左右の足を変更 */
            side++;
            
            /* 足跡を削除 */
            setTimeout(deleteFoot, 2000);
        }
    });
    
    /* logoの回転 */
    window.addEventListener('DOMContentLoaded', function() {
        const logo = document.querySelector('header .inner #logo a');
        logo.classList.add('rotateLogo');
    });
    
    /* jQuery */
    /* Hamberger Menu */
    $(function () {
                $('.btn-trigger').on('click', function () {
                    $(this).toggleClass('active');
                    $('body').toggleClass('modal');
//                    return false;
                });
    });
    /*  */
    
    
}
