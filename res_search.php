<?php

  // 検索結果アカウントから アカウントの 'id' をキーに予約情報を取得する ⇒ reservations テーブル
  // 予約は最新のものを 診察 ペットホテル それぞれで取得し、降順で並べる ０：診察 １：ペットホテル
  $sql = "select * from reservations A
                  where id = ?
                    and A.saishin_kbn in (select MAX(B.saishin_kbn)
                                            from reservations B
                                        group by B.res_kbn
                                        )
                  order by A.res_kbn";
  $stmt = $pdo->prepare($sql);
  
  // 検索結果のユーザーID
  $user_id = (int)$_SESSION['user']['id'];
  $stmt->bindValue(1, $user_id , PDO::PARAM_INT);
  $stmt->execute();

  // 予約情報の検索結果を $reservation に格納
  $reservation = $stmt->fetchAll();