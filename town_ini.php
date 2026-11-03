<?php
// このファイルは town_ini.cgi (Perl) から自動変換されたものです。
// 町CGI  Copyright (c) 2003-2004 brassiere  http://brassiere.jp/

// ------------------以下、初期設定項目-------------------
// 街の名前
$title = 'ブラジャータウン';
// 管理者名（ここで設定した管理者名とパスワードで新規登録することで、家を無料で作成することができます）
$admin_name = '';
// 管理者パスワード
// ※初期値「1234」や空欄のままでは管理機能は使えません（セキュリティ対策）。必ず変更してください。
// ※平文のほか、password_hash() で作ったハッシュ値（$2y$...）も指定できます。
// 例: php -r "echo password_hash('新しいパスワード', PASSWORD_DEFAULT);"
$admin_pass = '1234';
// データの保存方式（'file'＝テキストファイル、'sqlite'＝SQLiteデータベース）
// ※運用途中で切り替える場合は tools/migrate.php でデータを移行してください。
$storage_type = 'file';
// データ保存フォルダ（プログラムからの相対パス）。dat_dir / log_dir / member が入ります。
// ※Web から直接見えない場所に置くことを推奨します（同梱の .htaccess でもアクセスを禁止しています）。
$data_dir = './data';
// SQLite のデータベースファイル（$storage_type = 'sqlite' の場合のみ使用）
$sqlite_file = './data/town.sqlite';
// ホスト名の逆引きをするか（1＝する、0＝しない。しない場合は IP アドレスで多重登録判定・アクセス拒否を行います）
$host_lookup = '0';
// メンテ中フラグ（通常は0。1でメンテ中となりゲームを中断させることができます）
$mente_flag = '0';
// メンテナンス時のメッセージ
$mente_message = 'ただいまメンテナンス中。。。5分くらいお待ちください';
// 画像フォルダー（img）の指定。プログラムからの相対パス or http://～から始まる絶対パス　※最後の「/」は不要です。
$img_dir = './img';
// 戻り先ホームページ
$homepage = '';
// ---- OGP（SNS などで共有されたときの表示） ----
// サイトの URL（例: https://example.com/town/ ）。空欄の場合はアクセスされた URL から自動で判定します。
$ogp_url = '';
// 説明文
$ogp_description = '街の住民になって、仕事・買い物・家づくり・恋愛などを楽しむコミュニティゲームです。';
// 画像（プログラムからの相対パス or https://～の絶対 URL。推奨サイズ 1200×630）
$ogp_image = './img/ogp.png';
// ---- Twitter（X）カード ----
// カードの種類（'summary_large_image'＝大きい画像、'summary'＝小さい画像、''＝出力しない）
$twitter_card = 'summary_large_image';
// サイトのアカウント（例: '@example'。空欄なら出力しない）
$twitter_site = '';
// 作成者のアカウント（例: '@example'。空欄なら出力しない）
$twitter_creator = '';
// カード用の画像（空欄なら $ogp_image を使用。'summary' の場合は正方形の画像がおすすめ）
$twitter_image = '';
// カード用の画像の説明（代替テキスト）
$twitter_image_alt = '街並みのイラスト';
// 管理者メールアドレス（問い合わせ用）
$master_ad = '*****@****.jp';
// トップ画面でのお知らせ内容（タグ可）
$osirase = <<<EOM
<!-- ここから-->
<span style="color:#666666">あなたもこの街の住民になってみませんか？　街では掲示板やゲームなどを通して色々な方と知り合う事ができます。さまざまな職業についたり、豪華な家を建てたり、どんな人生にするかはあなた次第！初めての方は下の「新規登録」より登録してください。<br></span>
<span style="color:#ff6600">※二重登録は禁止です。発覚した時点でゲームへの参加をお断りしますのでご注意ください。<br>
※登録後、名前の変更はできません。</span>
<!-- ここまで-->
EOM;
// 作成する街の名前（一番左の街が最初に表示される街となります。例として４つを設定してありますが、１つだけでも、４つ以上でも構いません。※重要：ゲーム途中で街を追加する場合は、必ず最後に追加してください）
$town_hairetu = ['メインストリート', 'シー・リゾート', 'カントリータウン', 'ダウンタウン'];
// 街の地価（上で指定した街の地価。左から順に上と対応させる。単位は万円）
$town_tika_hairetu = ['4600', '3200', '600', '300'];
// 街の背景スタイル（上で指定した街の背景スタイル設定。左から順に上と対応させる。スタイルで画像指定可）
$page_back = ['background-color:#ffffcc', 'background-image : url( img/umi.gif)', 'background-color:#99cc66', 'background-color:#cccc99'];
// 参加者が建てられる家の画像と価格（'画像名','価格'の形で設定してください。価格の単位は（万円）です。家の画像はimgフォルダに入れておく必要があります。）
$ie_hash = ['house1.gif' => '150', 'house2.gif' => '150', 'house3.gif' => '150', 'house4.gif' => '800', 'house5.gif' => '800', 'house6.gif' => '800', 'house7.gif' => '1800', 'house8.gif' => '1800', 'house9.gif' => '1800', 'house10.gif' => '3200', 'house11.gif' => '3200', 'house12.gif' => '3200', 'house13.gif' => '4400', 'house14.gif' => '4400', 'house15.gif' => '4400', 'house16.gif' => '3600', 'house17.gif' => '3800', 'house18.gif' => '3600', 'house19.gif' => '3200', 'kamakura.gif' => '150', 'bil2.gif' => '4000', 'bil3.gif' => '4400', 'bil4.gif' => '4600', 'bil5.gif' => '4600'];
// 内装費用（左からA～Dランク。Aランクは４つのコンテンツを表示可能、Dランクは１つのみのコンテンツを表示可能。単位は万円）
$housu_nedan = ['1200', '800', '400', '100'];
// 役場でのランキング表示数
$rankMax = '50';
// 何日間食事しないと死んでしまうか（ユーザー削除期間）
$deleteUser = '30';
// 食事は何分ごとにとることができるか
$syokuzi_kankaku = '30';
// 身体パワーの回復率（何秒で１ポイント回復するか。数が少ないほど回復が早い）
$sintai_kaihuku = '30';
// 頭脳パワーの回復率（何秒で１ポイント回復するか。数が少ないほど回復が早い）
$zunou_kaihuku = '30';
// 卸問屋に並べる商品の数
$orosi_sinakazu = '120';
// 卸問屋の在庫調整（「syouhin.dat」で指定の在庫の何倍の数を置くか。お店が増えてきて問屋の在庫数が足らないと思ったらこの数字を増やしてください。1.5などの指定も可）
$ton_zaiko_tyousei = '2';
// セントラル食堂に並べる食品の種類数
$syokudou_sinakazu = '30';
// デパートに並べる商品の種類数
$depart_sinakazu = '80';
// 所有物の限度数
$syoyuu_gendosuu = '10';
// 食堂やデパートでの在庫調節値（基準の在庫数をこの数字で割った数が店頭に並びますので、この数字を大きくするほど在庫が少なく、小さくするほど在庫が多くなります）
$zaiko_tyousetuti = '5';
// お店の種別（このゲーム内のお店に出回る商品のデータは、「dat_dir」内にある「shouhin.dat」に記録されています。「shouhin.dat」ファイルの一番左にあるのが商品種別で、ここの「お店の種別」と対応している必要があります。※ただし食堂を表す「食」は「shouhin.dat」のみにある種別となります）
$global_syouhin_syubetu = ['スーパー', '書籍', '食料品', '薬', 'スポーツ用品', '電化製品', '美容', 'アダルト', 'DVD', 'ファーストフード', '日用品', 'お花', 'デザート', 'ギフト', 'アルコール', '乗り物', 'ゲーム', 'ドリンク'];
// 挨拶の種類
$aisatu_keyword = ['あいさつ', '今日の出来事', '今の気分'];
// 挨拶ログの保存件数
$aisatu_max = '10';
// 最大登録者数
$saidai_ninzuu = '300';
// 自動ファイル生成フォルダのパーミッション（777＝1、755＝2）※1でエラーが出るようなら2を試してみてください。
$zidouseisei = '1';
// メッセージ窓のスタイル設定
$message_window = 'border: #ff9933; border-style: dotted; border-width: 1px; background-color:#ffffaa; color:336699';
// ステータス窓の枠色
$st_win_wak = '#ff9966';
// ステータス窓の背景色
$st_win_back = '#ffffff';
// 設置する掲示板（'掲示板の名前'を「,」でつないだ形で設定してください。ここでの名前はマウスをのせた時にウインドウに表示させる名前です。ページ内のタイトルや各種デザイン・設定は管理メニューの「管理者作成BBSの設定」で行います。実際に街の任意の位置へ掲示板を配置するのは、管理者メニューの「街のレイアウト作成」で行ってください。）
$admin_bbs_syurui = ['みんなの広場。総合掲示板です。', '疑問解決BBS。普段疑問に思っている事を聞いてしまおう。', 'オススメ情報BBS。あなたのお薦め教えてください。', 'ハッピー掲示板。あなたのシアワセを感じる瞬間を投稿してください。', 'メインストリート住民専用掲示板', 'シー・リゾート住民専用掲示板', 'カントリータウン住民専用掲示板', 'ダウンタウン住民専用掲示板'];
// 上記掲示板の画像（左から上と対応させる）
$admin_bbs_gazou = ['bbs1.gif', 'bbs2.gif', 'bbs3.gif', 'bbs4.gif', 'bbs5.gif', 'bbs5.gif', 'bbs5.gif', 'bbs5.gif', 'bbs6.gif', 'bbs6.gif', 'bbs6.gif', 'bbs6.gif'];
// BBSの保存記事数（親記事、レス記事合わせた件数です）
$bbs_kizi_max = '100';
// アクセス拒否するホスト名
$deny = ['*.host.xx.jp', 'xxx.xxx.xx.'];
// 多重登録禁止（1で禁止、0で禁止しない）
$tajuukinsi_flag = '1';
// 多重登録者をログインできなくする（1でする、0でしない）
$tajuukinsi_deny = '0';
// Ｃリーグ１大会の日数
$c_nissuu = '14';
// Ｃリーグの試合数
$c_siaisuu = '100';
// Ｃリーグの試合間隔（単位は分）
$c_siai_kankaku = '30';
// キャラクターの画像サイズ指定（指定しない場合、自由サイズになります）
$chara_x_size = '80';
// 横サイズ
$chara_y_size = '80';
// 縦サイズ
// 週間街コンテストの賞金額（単位は万円）
$mati_con_syoukin = '300';
// 街コンテストの日数
$mati_con_nissuu = '7';
// プロフィールの１ページ表示数
$hyouzi_max_grobal = '10';
// #以下、プロフィールでの選択肢
// 性別のselect
$sex_array = ['', '男', '女', '中間'];
// 年齢のselect
$age_array = ['', '～14歳', '15～18歳', '19～22歳', '23～26歳', '27～30歳', '31～34歳', '35～38歳', '39～42歳', '43～46歳', '47～50歳', '51歳～'];
// 住所のselect
$address_array = ['', '北海道', '青森', '岩手', '宮城', '秋田', '山形', '福島', '群馬', '栃木', '茨城', '埼玉', '千葉', '東京', '神奈川', '新潟', '富山', '石川', '福井', '山梨', '長野', '岐阜', '静岡', '愛知', '三重', '滋賀', '京都', '大阪', '兵庫', '奈良', '和歌山', '鳥取', '島根', '岡山', '広島', '山口', '徳島', '香川', '愛媛', '高知', '福岡', '佐賀', '長崎', '熊本', '大分', '宮崎', '鹿児島', '沖縄', '海外'];
// 選択式プロフィール項目1
$prof_name1 = 'アピールポイント';
$prof_array1 = ['', 'かっこいい', '頭がいい', '背が高い', 'ガッシリ体格', 'マッチョ', '優しい', 'お金持ち', '車が自慢', '一人暮らし', '心意気', '素直', 'マメ', '誠実', '面白い', 'カワイイ', 'キレイ', '胸が自慢', '脚が自慢', 'ナイスバディ', '家庭的', 'スポーツ得意', '歌がうまい', '料理が得意', 'エッチ', 'ダメ人間'];
// 選択式プロフィール項目2
$prof_name2 = 'ただいま';
$prof_array2 = ['', '恋人募集中', '友だち募集中', 'メル友募集中', '趣味友募集中', '飲み友募集中', '合コン仲間募集中', 'ＨＰ宣伝中', '不倫相手募集中', '愛人募集中', '仕事一筋中', '新婚中', '別居中', '不倫中', '熱愛中', '同棲中', '勘当中', '家出中', '平凡生活中', '片思い中'];
// 選択式プロフィール項目3
$prof_name3 = '身長';
$prof_array3 = ['', '秘密', '低い', 'やや低い', '普通', 'やや高い', '高い'];
// 選択式プロフィール項目4
$prof_name4 = '体重';
$prof_array4 = ['', '秘密', 'やせてる', '少しやせてる', '普通', 'ややぽちゃ', 'ぽちゃ'];
// 選択式プロフィール項目5
$prof_name5 = '職業';
$prof_array5 = ['', 'フリーター', '学生', '無職', 'ＯＬ', 'サラリーマン', '公務員', '主婦', '営業マン', '技術職', '商社マン', '銀行マン', 'SE・プログラマー', 'パイロット', 'スチュワーデス', '警察', '消防士', '僧侶', 'ファッション関係', 'プランナー', '編集者', 'クリエイター', '販売員', '美容師', '大工', 'マスコミ', '社長', '会社役員', '事務職', 'コンピュータ', '飲食業', '教師', '医師', 'アーティスト', 'デザイナー', 'タレント', '看護婦', '保母', 'コンサルタント', '自営業', '水商売', '音楽関係', '芸人', 'スポーツ選手', 'その他'];
// 記述式プロフィール項目
$kijutu_prof = ['似ている有名人', '趣味', 'ホームページ', '弱点', '好きな有名人', '好きなスポーツ', '好きな映画', '好きなＴＶ番組', '好きな音楽', '好きな異性のタイプ', '将来の夢', '嫌いなもの', '今一番行きたいところ', '今一番したいこと', '一言コメント'];
// #以下各種デザインのスタイル設定
// メッセージ窓のスタイル設定
$message_win = 'border: #000000; border-style: dotted; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; background-color:#ffffff; color:000000';
$town_stylesheet = <<<EOM
.dai {  font-size: 14px; font-weight: bold;color: #000000}	/*大きな文字*/
.tyuu {  font-size: 12px; color: #ff6600}	/*ステータス中見出し*/
.honbun2 {  font-size: 11px; line-height: 16px; color: #006699}	/*ステータス項目部分*/
.honbun3 {  font-size: 11px; line-height: 11px; color: #006699}	/*パラメータ項目部分*/
.honbun5 {  font-size: 11px; line-height: 11px; color: #339900}	/*キャラのパラメータ項目部分*/
.honbun4 {  font-size: 12px; line-height: 22px; color: #006699}	/*中くらいの文字*/
.job_messe {  font-size: 11px; line-height: 22px; color: #000000}	/*パラメータメッセージ*/
.small {  font-size: 9px; color: #444444}	/*小さい文字*/
.midasi {  font-size: 16px; font-weight: bold; text-align: center;color: #666666}	/*街の名前*/
.gym_style { background-color:#ffcc33; background-image:url({$img_dir}/shop_bak.gif)}	/*ジムの背景*/
.syokudou_style { background-color:#ccff66; background-image:url({$img_dir}/shop_bak.gif)}	/*食堂の背景*/
.orosi_style { background-color:#996633; background-image:url({$img_dir}/shop_bak.gif)}	/*卸問屋の背景*/
.job_style { background-color:#669966; background-image:url({$img_dir}/shop_bak.gif)}	/*ハローワークの背景*/
.school_style { background-color:#339999; background-image:url({$img_dir}/shop_bak.gif)}	/*学校の背景*/
.ginkou_style { background-color:#999999; background-image:url({$img_dir}/shop_bak.gif)}	/*銀行の背景*/
.yakuba_style { background-color:#336699; background-image:url({$img_dir}/shop_bak.gif)}	/*役場の背景*/
.item_style { background-color:#ffcc66; background-image:url({$img_dir}/command_bak.gif)}	/*アイテム使用画面の背景*/
.kentiku_style { background-color:#996666; background-image:url({$img_dir}/command_bak.gif)}	/*建築会社の背景*/
.omise_list_style { background-color:#b293ad; background-image:url({$img_dir}/shop_bak.gif)}	/*個人お店の商品リスト背景*/
.prof_style { background-color:#ccff99; background-image:url({$img_dir}/shop_bak.gif)}	/*プロフィール画面背景*/
.keiba_style { background-color:#99cc66; }	/*競馬背景*/

.yosumi {  border: #666666; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; background-color:#ffffff}	/*街ステータス窓*/

.sumi {  border: #000000; border-style: solid; border-top-width: 0px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 0px}
.migi {  border: #000000; border-style: solid; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px}
.sita {  border: #000000; border-style: solid; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px}
.sita2 {  border: #666666; border-style: solid; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 1px; border-left-width: 0px}
.jouge {  border: #000000; border-style: solid; border-top-width: 1px; border-right-width: 0px; border-bottom-width: 1px; border-left-width: 0px}
.message {  font-size: 12px; line-height: 16px; color: #000000;}


.purasu {color: #009900;}
.mainasu { color: #ff3300;}
.kuro {  font-size: 13px; text-align: left;color: #000000}
.honbun {  font-size: 11px; line-height: 16px; color: #000000}
.loto {  font-size: 28px; color: #000000;line-height:180%;}
.naka {  border: #000000; border-style: solid; border-top-width: 1px; border-right-width: 0px; border-bottom-width: 1px; border-left-width: 0px}
a {color:#333333;text-decoration: none}
a:hover {text-decoration: underline}
body {font-size:11px;color:#000000 }
table {font-size:11px;color:#000000;}
EOM;
// #########################ver.1.1追加
// ギフト所有限度数（贈られたギフト）
$gift_gendo = '10';
// ギフト購入限度数
$kounyu_gift_gendo = '5';
// 自分のお店に置ける商品アイテム数
$mise_zaiko_gendo = '15';
// アイテム数が限度以上なら同じアイテムでも在庫を増やせなくする（増やせない＝1、増やせる＝0）
$douitem_ok = '1';
// 温泉入浴時に通常の何倍の早さでパワーが回復するか
$onsen_times = '10';
// 温泉で使っている画像の数（温泉の画像を追加する場合、img/onsenフォルダー内に○.jpg（○は1から始まる連番の数字）というファイル名で入れてください）
$on_gazou_suu = '10';
// 温泉入浴料（単位は円）
$nyuuyokuryou = '500';
// gzipのパス（サーバーがgzip圧縮に対応していない場合空欄にしてください（※画面が真っ白になる場合などは対応してない可能性が高いです）。ただし、gzip圧縮が使えるとテキストの転送量をかなり小さくできます）
$gzip = '';
// 徒歩で街の移動にかかる時間（秒指定）
$matiidou_time = '20';
// 各乗り物ごとで移動にかかる時間（'乗り物','かかる秒数'のフォーマットで。乗り物はsyouhin.datにある商品でないと意味がありません。ただし商品種別が「乗り物」である必要はありません。コメントに「※移動手段です」と入れてあげた方が親切かと思います。また速いものから先に並べてください）
$idou_syudan = ['ミグ25' => '0', 'フェラーリ' => '2', 'ポルシェ' => '3', 'ロータスヨーロッパ' => '4', 'アルファロメオ' => '4', 'ジャガー' => '4', 'BMW' => '4', 'スカイラインGTR' => '4', 'ロールスロイス' => '5', 'ベンツ' => '5', 'フェアレディZ' => '5', 'キャデラック' => '6', 'ボルボ' => '6', 'カローラ' => '7', 'ナナハン' => '7', 'ドゥカティ' => '7', 'スーパーカブ' => '10', 'ベスパ' => '10', '自転車' => '15'];
// 街移動時に事故を起こす確率（何分の一の確率かで指定。デフォルトでは10分の１という意味）
$ziko_kakuritu = '10';
// 行動制限時間（秒指定。制限を付けない場合＝0）
$koudou_seigen = '16';
// カードゲームができる間隔（単位：分）
$crad_game_time = '30';
// #########################ver.1.2追加
// 同一アイテムの所有個数の限度
$item_kosuuseigen = '3';
// 仕事ができる間隔（単位：分。制限無しの場合は0を指定）
$work_seigen_time = '10';
// 競馬の購入限度枚数（単位：枚）
$keiba_gendomaisuu = '200';
// #########################ver.1.21追加
// メンバーログのファイルロック方式（0でリネームロック、0でうまく稼働しない場合1にしてください）
$mem_lock_num = '0';
// #########################ver.1.3追加
// 恋人斡旋プロフィール記録ファイル
$as_profile_file = './log_dir/as_pfofilelog.cgi';
// カップル記録ファイル
$couple_file = './log_dir/couplelog.cgi';
// 子供記録ファイル
$kodomo_file = './log_dir/kodomolog.cgi';
// 街ニュース記録ファイル
$news_file = './log_dir/mati_news.cgi';
// 役場のニュース表示件数
$news_kensuu = '100';
// お店に置いておける在庫の上限数
$mise_zaiko_limit = '30';
// メールの保存件数
$mail_hozon_gandosuu = '50';
// 特別風呂は何倍の速度で回復するか
$tokubetu_times = '20';
// 特別風呂でかかる費用（円）
$tokubetuburo_hiyou = '50000';
// 恋愛システムを使うか（1＝使う、0＝使わない　※0にすることでハート＆子供アイコンが出現されなくなります。また、0の場合「恋人斡旋所」も街に設置しないでください）
$renai_system_on = '1';
// 恋愛に必要なLOVEパラメータの数値
$need_love = '200';
// 同性の恋愛を許可する（許可しない＝0、許可する＝1）
$douseiai_per = '0';
// 同時に何人とつきあえるか（配偶者＋恋人）
$koibito_seigen = '5';
// 結婚に必要なラブラブ度（思い出数値の合計）
$aijou_kijun = '500';
// 結婚に必要な思い出数値（それぞれの思い出の最低値）
$omoide_kijun = '80';
// 恋人と何日間デートをしないと別れてしまうか
$wakare_limit_koibito = '7';
// 配偶者と何日間デートをしないと別れてしまうか
$wakare_limit_haiguu = '14';
// 相手が配偶者の場合の子供ができる確率（10なら10分の１の確率）
$kodomo_kakuritu1 = '10';
// 相手が恋人の場合の子供ができる確率（80なら80分の１の確率）
$kodomo_kakuritu2 = '80';
// 子育てできる間隔（単位：時間）
$kosodate_kankaku = '3';
// 子供のパラメータを１あげるのにかかる費用（円）
$youikuhiyou = '100000';
// 何日間子供に食事を与えないと死んでしまうか
$kodomo_sibou_time = '7';
// 子供は何歳まで生きるか（仕送りが送られてくる期間）
$kodomo_sibou_time2 = '40';
// 以下、結婚斡旋所での変更可能なプロフィール項目
// 年齢のselect
$as_age_array = ['', '～14歳', '15～18歳', '19～22歳', '23～26歳', '27～30歳', '31～34歳', '35～38歳', '39～42歳', '43～46歳', '47～50歳', '51歳～'];
// 住所のselect
$as_address_array = ['', '北海道', '青森', '岩手', '宮城', '秋田', '山形', '福島', '群馬', '栃木', '茨城', '埼玉', '千葉', '東京', '神奈川', '新潟', '富山', '石川', '福井', '山梨', '長野', '岐阜', '静岡', '愛知', '三重', '滋賀', '京都', '大阪', '兵庫', '奈良', '和歌山', '鳥取', '島根', '岡山', '広島', '山口', '徳島', '香川', '愛媛', '高知', '福岡', '佐賀', '長崎', '熊本', '大分', '宮崎', '鹿児島', '沖縄', '海外'];
// 選択式プロフィール項目1
$as_prof_name2 = 'アピールポイント';
$as_prof_array2 = ['', 'かっこいい', '頭がいい', '背が高い', 'ガッシリ体格', 'マッチョ', '優しい', 'お金持ち', '車が自慢', '一人暮らし', '心意気', '素直', 'マメ', '誠実', '面白い', 'カワイイ', 'キレイ', '胸が自慢', '脚が自慢', 'ナイスバディ', '家庭的', 'スポーツ得意', '歌がうまい', '料理が得意', 'エッチ', 'ダメ人間'];
// 選択式プロフィール項目2
$as_prof_name3 = '住んでいる街';
$as_prof_array3 = ['', '家を持っていない', 'メインストリート', 'シーリゾート', 'カントリータウン', 'ダウンタウン'];
// 選択式プロフィール項目3
$as_prof_name4 = '欲しい子供の数';
$as_prof_array4 = ['', '子供はいらない', '1人でいい', '2人くらい', '3人くらい', '4、5人は欲しい', '6人以上'];
// 選択式プロフィール項目4
$as_prof_name5 = '相手の年齢は';
$as_prof_array5 = ['', '年齢は気にしない', '同じくらいがいい', '年上がいい', '年下がいい', 'すごく年上がいい', 'すごく年下がいい'];
// 選択式プロフィール項目5
$as_prof_name6 = '相手に望むこと';
$as_prof_array6 = ['', 'かっこよさ', '頭のよさ', '背の高さ', 'ガッシリ体格', 'マッチョ', '優しさ', '裕福度', '一人暮らし', '心意気', '素直さ', 'マメさ', '誠実さ', '面白さ', 'カワイさ', 'キレイさ', '胸の大きさ', '脚のキレイさ', 'ナイスバディ', '家庭的', 'スポーツマン', '歌のうまさ', '料理の上手さ', 'エッチさ'];
// 街の下に挨拶を表示するか（表示する＝1、表示しない＝0）
$top_aisatu_hyouzi = '1';
// 上で表示するにした場合の表示件数
$top_aisatu_hyouzikensuu = '6';
// 上で表示するにした場合の名前の色
$top_aisatu_hyouzi_iro1 = '#333399';
// 上で表示するにした場合の記事の色
$top_aisatu_hyouzi_iro2 = '#333333';
// トップページで街の下に自由表示するするhtml（EOM～EOMの間にhtmlで記述）
$top_information = '';
// #########################ver.1.30追加
// 参加者を表示する（1＝表示、0＝表示しない）
$sanka_hyouzi_kinou = '1';
// 上で「表示」にした場合の表示位置（1＝上、0＝下）
$sanka_hyouzi_iti = '0';
// 同時にログインできる人数
$douzi_login_ninzuu = 25;
// #########################ver.1.40追加
// 参加者ファイル（ver.1.30で"./log_dir/guestlog.cgi"だった指定を変更）
$guestfile = './guestlog.cgi';
// ゲームしないでログアウトするまでの時間（秒）
$logout_time = '1200';
// 新規登録の受け付け（1＝うけつけない、0＝うけつける）
$new_touroku_per = '0';
// 名前＆パスワード記録ファイル
$pass_logfile = './log_dir/passlog.cgi';
// 自分や配偶者のお店で商品を買えなくする（1＝買えない、0＝買える）
$kaenai_seigen = '1';
// ------------------設定変更ここまで（以下は必要があれば変更してください）
// スクリプトの名前
$script = './town_maker.php';
// 個人ログデータファイル
$logfile = './log_dir/memberlog.cgi';
// Ｃリーグログデータファイル
$doukyo_logfile = './log_dir/doukyo_log.cgi';
// オリジナル家リストファイル
$ori_ie_list = './log_dir/ori_ie_log.cgi';
// メイン街パラメータ記録ファイル
$maintown_logfile = './log_dir/maintownlog.cgi';
// 卸商品記録ファイル
$orosi_logfile = './log_dir/orosilog.cgi';
// 今日の食堂メニュー記録ファイル
$syokudou_logfile = './log_dir/syokudoulog.cgi';
// 今日のデパートの品揃え記録ファイル
$depart_logfile = './log_dir/departlog.cgi';
// 挨拶ログ記録ファイル
$aisatu_logfile = './log_dir/aisatulog.cgi';
// 街コンテストログファイル
$maticon_logfile = './log_dir/maticonlog.cgi';
// 街コンテスト功労者ログファイル
$kourousya_logfile = './log_dir/kourousyalog.cgi';
// ドーナツゲームログファイル
$donuts_logfile = './log_dir/donutslog.cgi';
// 競馬ログファイル
$keiba_logfile = './log_dir/keibalog.cgi';
// 競馬ランキングログファイル
$keibarank_logfile = './log_dir/keibaranklog.cgi';
// ロックファイル名
$lockfile = './lock/town.lock';
// 競馬ロックファイル名
$keibalockfile = './lock/keiba.lock';
// プロフィール記録ファイル
$profile_file = './log_dir/pfofilelog.cgi';


// 設定の上書き用ファイル（任意）。存在すれば読み込みます。
if (is_file(__DIR__ . '/town_ini_local.php')) {
    require __DIR__ . '/town_ini_local.php';
}
