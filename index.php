<!-- wp:html -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/core.css?v=20260701-15">
<link rel="stylesheet" href="css/components.css?v=20260701-1">
<link rel="stylesheet" href="css/pages.css?v=20260706-02">

<?php
require __DIR__ . '/improvement-lib.php';
$ckHomeUpdates = array_slice(ck_log_public_items(ck_log_load_all()), 0, 3);
?>

<header class="ck-site-header">
  <div class="ck-header-bar">
    <a class="ck-header-logo" href="index.php" aria-label="厨房君 トップページ">
      <img src="image/logo/header_logo.png" alt="厨房君">
    </a>
    <nav class="ck-header-nav" id="ck-header-nav" aria-label="主要ナビゲーション">
      <a href="first.php">はじめての方へ</a>
      <a href="esl-solution.php">電子棚札のメリット</a>
      <a href="business-support.php">経営サポート</a>
      <a href="service.php">機能とプラン</a>
      <a href="future.php">業界の未来</a>
      <a href="company.php">COMPANY</a>
    </nav>
    <a class="ck-header-cta" href="https://rise-up.net/franchise/" target="_blank" rel="noopener noreferrer">FC募集</a>
    <button class="ck-header-menu" type="button" aria-label="メニュー" aria-controls="ck-header-nav" aria-expanded="false">
      <span></span>
      <span></span>
    </button>
  </div>
</header>

<main class="ck-home">
  <section class="ck-home-hero">
    <video autoplay muted loop playsinline>
      　　　
      <source src="video/bigdata2-2.mp4" type="video/mp4">
    </video>
    <div class="ck-home-hero__inner">
      <div class="ck-home-hero__copy">
        <p class="ck-home-eyebrow">中古厨房機器ビジネスのためのDXプラットフォーム</p>
        <h1>情報がつながる<br>業務がまわる<br>働き方がかわる</h1>
        <p>
          顧客、買取、再生、在庫、見積、売上、プロジェクト管理まで。<br class="pc-only">
          厨房君は、分断されがちな業務データをひとつにつなげる実務特化型のクラウド業務システムです。
        </p>
        <div class="ck-home-actions ck-home-actions--copy">
          <a href="#problem" class="ck-home-primary">課題を見る</a>
          <a href="#features" class="ck-home-secondary">機能を見る</a>
        </div>
        <span class="ck-home-award">2025年 IT賞＜経営・業務改革＞受賞</span>
      </div>
      <div class="ck-home-hero__visual">
        <img src="image/fv/chubo-kun.png" alt="厨房君の管理画面イメージ">
      </div>
      <div class="ck-home-actions ck-home-actions--tablet">
        <a href="#problem" class="ck-home-primary">課題を見る</a>
        <a href="#features" class="ck-home-secondary">機能を見る</a>
      </div>
    </div>
  </section>

  <section class="ck-home-section ck-home-trust">
    <div class="ck-home-container">
      <div class="ck-home-section-head ck-center">
        <span>TRUST</span>
        <h2>改善の積み重ねが、<br>信頼になっています。</h2>
        <p>
          厨房君は、机の上だけで設計したシステムではありません。<br class="pc-only">毎日の現場で見つかった課題を改善し、
          その積み重ねを機能へ反映し続けてきました。
        </p>
      </div>
      <div class="ck-proof-grid">
        <article>
          <span class="ck-proof-step">01</span>
          <span class="ck-proof-mark">改善の積み重ね
          </span>
          <h3>現場起点の改善</h3>
          <p>中古厨房機器の実務で起こる困りごとを一つひとつ拾い上げ、現場の声をもとに改善を積み重ねてきました。
            その積み重ねが、厨房君の機能や使いやすさを進化させ続けています。</p>
        </article>
        <article>
          <span class="ck-proof-step">02</span>
          <span class="ck-proof-mark">独自性の証明</span>
          <h3>特許取得</h3>
          <p>入荷から売約・再生・出荷まで、ESL（電子棚札）と在庫情報をリアルタイムに連携。現場で起こりやすい
            情報ズレを防ぐ独自の仕組みが評価され、特許を取得しました。</p>
        </article>
        <article class="ck-proof-award">
          <span class="ck-proof-step">03</span>
          <span class="ck-proof-mark">外部評価</span>
          <h3>IT賞 受賞</h3>
          <p>現場の課題を解決するために積み重ねてきた改善が評価され、2025年「IT賞＜経営・業務改革＞」を受賞。
            現場起点のDXへの取り組みが高く評価されました。<br>
            <a href="https://jiit.or.jp/itaw2025-2/" class="ck-home-link" target="_blank">詳しくは...</a>
          </p>
        </article>
        <article>
          <span class="ck-proof-step">04</span>
          <span class="ck-proof-mark">プラットフォームへ</span>
          <h3>仕組みを共有する</h3>
          <p>現場で磨き続けた仕組みは、自社だけでなく加盟店でも活用されています。
            改善の積み重ねを共有し、ともに成長できる業界のDXプラットフォームへ育てています。</p>
        </article>
      </div>
    </div>
  </section>

  <section class="ck-home-section ck-home-dark" id="problem">
    <div class="ck-home-container ck-problem-layout">
      <div class="ck-home-section-head ck-center">
        <span>PROBLEM</span>
        <h2>アナログ管理の<br class="sp-only">現場では、<br>情報が分断<br class="sp-only">されやすい。</h2>
        <p>
          中古厨房機器の業務は、仕入れ、再生、在庫、販売、売上まで情報量が多い。<br class="pc-only">
          紙、Excel、担当者の記憶に分かれるほど、ムダ、ミス、手戻りが発生します。
        </p>
      </div>
      <div class="ck-problem-list">
        <article>
          <span>01</span>
          <h3>情報が各所に散在している</h3>
          <p>担当者ごとに情報が管理され、共有できない。過去の履歴も見つからず、提案までに時間がかかる。</p>
        </article>
        <article>
          <span>02</span>
          <h3>仕入業務が属人化</h3>
          <p>仕入ノウハウが個人に依存し、担当者不在時に判断や作業が滞りやすい。</p>
        </article>
        <article>
          <span>03</span>
          <h3>再生や整備の進捗が見えない</h3>
          <p>進捗状況が共有できず、納期遅れや作業の抜け、漏れ、手戻りが起こる。</p>
        </article>
        <article>
          <span>04</span>
          <h3>在庫情報があいまい</h3>
          <p>在庫状況を正確に把握できず、販売機会を逃す。在庫過多や二重売約で現場が混乱する。</p>
        </article>
        <article>
          <span>05</span>
          <h3>販売・案件情報が分散</h3>
          <p>見積、案件、契約の情報がバラバラで、納品後の売上計上やフォローが漏れやすい。</p>
        </article>
        <article>
          <span>06</span>
          <h3>売上や利益の把握が遅い</h3>
          <p>売上や利益の集計に時間がかかり、経営判断が遅れ、改善のタイミングを逃す。</p>
        </article>
      </div>
      <div class="ck-problem-impact">
        <span>その結果として</span>
        <h3>アナログ管理では、<br class="sp-only">会社の成長に<br class="sp-only">限界がある。</h3>
        <ul>
          <li>会社の成長が頭打ちになる</li>
          <li>事業の持続性が弱くなる</li>
          <li>若手に敬遠されやすい</li>
          <li>業務が属人化する</li>
          <li>二度手間が増える</li>
          <li>経営数値を把握しにくい</li>
        </ul>
      </div>
    </div>
  </section>

  <section class="ck-home-section ck-home-solution">
    <div class="ck-home-container ck-home-solution-kicker">SOLUTION</div>
    <div class="ck-home-container ck-split">
      <div class="ck-home-section-head">
        <h2>分断された業務を、<br>ひとつの流れに。</h2>
        <p class="ck-solution-subtitle">6つの主要業務を、<br>ひとつのシステムでつなぐ。</p>
        <p class="ck-solution-lead">
          厨房君は、中古厨房機器ビジネスの情報を現場の流れに沿って連携し、
          業務のスピード、精度、改善力を高めます。<br>すべての情報がつながる
          ことで、現場の判断を速くし、無駄なく効率的な業務を行うことが可能です。
        </p>
      </div>
      <figure class="ck-solution-visual">
        <img src="image/6functions.png" alt="厨房君がつなぐ6つの主要業務">
      </figure>
    </div>
    <div class="ck-home-container">
      <div class="ck-flow-grid">
        <article><span>01</span>
          <strong class="ck-flow-name">顧客管理</strong>
          <h3>顧客情報を資産に変える</h3>
          <p><span class="ck-flow-text-full">顧客情報・店舗情報・見積・請求・対応履歴を一元管理。過去のやり取りを蓄積し、担当者が変わっても引き継ぎや提案をスムーズに進められる状態をつくります。</span><span class="ck-flow-text-short">顧客・商談・対応履歴を一元管理し、引き継ぎや提案に活かせる資産に変える。</span></p>
        </article>
        <article><span>02</span>
          <strong class="ck-flow-name">買取管理</strong>
          <h3>利益を生み出す仕入れを支える</h3>
          <p><span class="ck-flow-text-full">買取案件の進捗や状況を組織全体で共有し、対応漏れを防止。入荷後は在庫・電子棚札との連携まで見据え、仕入れから商品化までの動きを速くします。</span><span class="ck-flow-text-short">買取案件の進捗を共有し、利益を生む仕入れ判断と成約を速くする。</span></p>
        </article>
        <article><span>03</span>
          <strong class="ck-flow-name">再生管理</strong>
          <h3>商品価値を高める工程を見える化</h3>
          <p><span class="ck-flow-text-full">商品の状態や清掃・点検・修理工程を見える化し、優先順位を共有。再生状況をリアルタイムに把握することで、品質向上と販売機会の損失防止を支援します。</span><span class="ck-flow-text-short">清掃・点検・修理の状況を共有し、商品価値と販売機会を高める。</span></p>
        </article>
        <article><span>04</span>
          <strong class="ck-flow-name">在庫管理</strong>
          <h3>正しい在庫情報をリアルタイムに共有</h3>
          <p><span class="ck-flow-text-full">型式情報・商品個体情報・保管場所を分けて管理し、商品の履歴を蓄積。電子棚札との連携により、システムと現場表示のズレを抑えて在庫精度を高めます。</span><span class="ck-flow-text-short">商品状態と保管情報を同期し、正しい在庫判断を支える。</span></p>
        </article>
        <article><span>05</span>
          <strong class="ck-flow-name">見積管理</strong>
          <h3>お客様の理想を形にする</h3>
          <p><span class="ck-flow-text-full">商品・原価・粗利・資料をつなぎ、提案業務を効率化。厨房づくりに必要な機器リストや案件情報を整理し、販売から在庫管理まで一元化して、お客様の理想を形にする業務を支えます。</span><span class="ck-flow-text-short">商品・原価・資料をつなぎ、お客様の理想を形にする提案を支える。</span></p>
        </article>
        <article><span>06</span>
          <strong class="ck-flow-name">売上管理</strong>
          <h3>見える数字が、次の行動を変える</h3>
          <p><span class="ck-flow-text-full">顧客・仕入・再生・在庫・販売の日々の活動を売上ダッシュボードへ集約。売上・粗利・目標達成率を共有し、次の改善行動を考えやすくします。</span><span class="ck-flow-text-short">売上・粗利・目標達成率を把握し、次の改善行動につなげる。</span></p>
        </article>
      </div>
    </div>
  </section>

  <section class="ck-home-section ck-home-dark ck-screen-section">
    <div class="ck-home-container">
      <div class="ck-home-section-head ck-center">
        <span>PRODUCT</span>
        <h2>中古厨房機器業界の<br class="sp-only">ために<br class="pc-only">生まれた<br class="sp-only">DXシステム</h2>
        <p>
          厨房君は、単なる販売管理ソフトではありません。<br class="pc-only">中古厨房機器業界の現場で生まれ、現場で磨き続けてきたDXシステムです。
        </p>
        <p>
          買取から再生、在庫、商談、販売、電子棚札連携まで。<br class="pc-only">
          バラバラだった業務をひとつにつなぎ、現場が同じ情報で動ける環境をつくります。
        </p>
        <p>
          情報がつながることで、確認が減り、判断が速くなる。<br class="pc-only">
          それが厨房君の価値です。
        </p>
      </div>
      <figure class="ck-product-package">
        <img src="image/chubo-kun_logo.png" alt="厨房君の製品イメージ">
      </figure>
      <div class="ck-product-insight">
        <article>
          <span>DATA FLOW</span>
          <h3>情報がつながる</h3>
          <p>顧客、買取、再生、在庫、見積、売上まで。各業務の情報がシームレスに連携。次の判断へ自然につながります。</p>
        </article>
        <article>
          <span>WORK FLOW</span>
          <h3>業務がまわる</h3>
          <p>担当者ごとの作業、案件の進み具合、商品の状態を共有し、確認待ちや伝達ミスを削減し、効率的な動きが可能です。</p>
        </article>
        <article>
          <span>WORK STYLE</span>
          <h3>働き方がかわる</h3>
          <p>日々の実績と現場の動きを数字で捉え、次の仕入れ、提案、販売計画に活かせます。</p>
        </article>
      </div>
      <p class="ck-product-note">※ 使用している画像は実際のパッケージ商品ではありません。<br class="sp-only">製品のイメージを伝えるための表現です。</p>
    </div>
  </section>

  <section class="ck-home-section" id="features">
    <div class="ck-home-container">
      <div class="ck-home-section-head ck-center">
        <span>FEATURES</span>
        <h2>実務に効く、<br class="sp-only">主要機能。</h2>
        <p>現場で培ったノウハウをもとに、日々の業務を支える機能を搭載しています。<br class="pc-only">実際の運用シーンに沿って、厨房君の主要機能をご紹介します。</p>
      </div>
      <div class="ck-feature-grid ck-feature-grid--twelve">
        <a href="business-support.php">
          <span class="ck-feature-icon" aria-hidden="true"><img src="image/features/01.png" alt=""></span>
          <h3>買取案件<br>進行管理ボード</h3>
          <p>買取案件の状況、担当、次のアクションをひと目で把握。</p>
          <span class="ck-feature-preview">
            <img src="image/features/screens/01.png" alt="買取案件の進行管理ボードの画面">
            <span>仕入れの進行状況をステータス別に色分けして一覧表示。担当者の対応状況をリアルタイムで把握できるため、スピーディな判断と買取成約までのサポートが可能になります。</span>
          </span>
        </a>
        <a href="business-support.php">
          <span class="ck-feature-icon" aria-hidden="true"><img src="image/features/02.png" alt=""></span>
          <h3>再生作業<br>進行管理ボード</h3>
          <p>清掃、点検、修理など再生工程の停滞を見える化。</p>
          <span class="ck-feature-preview">
            <img src="image/features/screens/02.png" alt="再生作業の進行管理ボードの画面">
            <span>入荷後の機器が再生工程のどこまで進んでいるかを一目で把握でき、作業の優先順位をコントロールしながら、再生完了までのリードタイム短縮に貢献します。</span>
          </span>
        </a>
        <a href="esl-solution.php">
          <span class="ck-feature-icon" aria-hidden="true"><img src="image/features/03.png" alt=""></span>
          <h3>在庫管理<br>× 電子棚札連携</h3>
          <p>在庫情報と現場表示をつなぎ、情報のズレを抑える。</p>
          <span class="ck-feature-preview">
            <img src="image/features/screens/03.png" alt="在庫管理と電子棚札連携の画面">
            <span>在庫にESLを連携することで、システムとの同期をリアルタイムで実現し、人為的なミスや情報のズレを防止します。常に正確な在庫情報をもとに業務を進められます。</span>
          </span>
        </a>
        <a href="business-support.php">
          <span class="ck-feature-icon" aria-hidden="true"><img src="image/features/04.png" alt=""></span>
          <h3>見積作成画面<br>（中心機能）</h3>
          <p>商品、原価、粗利、発注状況を見積業務に集約。</p>
          <span class="ck-feature-preview">
            <img src="image/features/screens/04.png" alt="見積作成画面">
            <span>厨房君の操作の起点となる見積作成画面では、原価・粗利・発注状況までを自動で把握。商品IDを入力するだけで情報が反映され、厨房づくりに関わる情報をここからつなげます。</span>
          </span>
        </a>
        <a href="business-support.php">
          <span class="ck-feature-icon" aria-hidden="true"><img src="image/features/05.png" alt=""></span>
          <h3>機器リスト表<br>作成機能</h3>
          <p>厨房機器リストを案件ごとに整理し、提案資料を効率化。</p>
          <span class="ck-feature-preview">
            <img src="image/features/screens/05.png" alt="機器リスト表作成機能の画面">
            <span>厨房レイアウトの提案や施工打合せに欠かせない機器リスト表を作成。見積作成画面からそのまま連携でき、資料作成をスムーズに進められます。</span>
          </span>
        </a>
        <a href="business-support.php">
          <span class="ck-feature-icon" aria-hidden="true"><img src="image/features/06.png" alt=""></span>
          <h3>搬入設置費用<br>自動積算システム</h3>
          <p>搬入、設置に関わる費用算出を標準化し、見積精度を高める。</p>
          <span class="ck-feature-preview">
            <img src="image/features/screens/06.png" alt="搬入設置費用自動積算システムの画面">
            <span>設置条件（階数・分解搬入・距離など）を入力するだけで、搬入設置費を自動で算出。経験や勘に頼らず、人に依存しない再現性の高い見積を可能にします。</span>
          </span>
        </a>
        <a href="business-support.php">
          <span class="ck-feature-icon" aria-hidden="true"><img src="image/features/07.png" alt=""></span>
          <h3>取扱説明書<br>承認図 一括DL</h3>
          <p>必要資料をまとめて取得し、確認と共有の手間を削減。</p>
          <span class="ck-feature-preview">
            <img src="image/features/screens/07.png" alt="取扱説明書と承認図一括ダウンロード機能の画面">
            <span>見積画面に登録した厨房機器の資料（承認図・取扱説明書・CADデータ）を一括でダウンロード可能。資料集めの手間を削減し、すぐに活用できます。</span>
          </span>
        </a>
        <a href="business-support.php">
          <span class="ck-feature-icon" aria-hidden="true"><img src="image/features/08.png" alt=""></span>
          <h3>プロジェクト<br>管理機能</h3>
          <p>納品、施工、関係者の進捗を案件単位で管理。</p>
          <span class="ck-feature-preview">
            <img src="image/features/screens/08.png" alt="プロジェクト管理機能の画面">
            <span>複数の見積案件を1つのプロジェクトとして一元管理。スタッフ、お客様、施工業者で最新の図面やタスクをクラウド上でリアルタイムに共有できます。</span>
          </span>
        </a>
        <a href="business-support.php">
          <span class="ck-feature-icon" aria-hidden="true"><img src="image/features/09.png" alt=""></span>
          <h3>ヤフオク<br>出品管理機能</h3>
          <p>出品状況と販売チャネルを管理し、在庫の販売機会を広げる。</p>
          <span class="ck-feature-preview">
            <img src="image/features/screens/09.png" alt="ヤフオク出品管理機能の画面">
            <span>在庫情報をもとに、ヤフオク出品用のHTMLデータを自動生成。商品説明や配送方法などが自動で反映され、出品作業の手間を大幅に削減します。</span>
          </span>
        </a>
        <a href="business-support.php">
          <span class="ck-feature-icon" aria-hidden="true"><img src="image/features/10.png" alt=""></span>
          <h3>売上目標に対する<br>実績と計画</h3>
          <p>目標、実績、進捗を確認し、改善の打ち手につなげる。</p>
          <span class="ck-feature-preview">
            <img src="image/features/screens/10.png" alt="売上目標に対する実績と計画の画面">
            <span>売上は実績カード、進行中の案件は計画カードとして管理。数値とグラフで進捗を可視化し、年間売上・粗利目標に対する達成度をひと目で把握できます。</span>
          </span>
        </a>
        <a href="business-support.php">
          <span class="ck-feature-icon" aria-hidden="true"><img src="image/features/11.png" alt=""></span>
          <h3>棚卸機能</h3>
          <p>在庫情報をもとに、確認作業と月次管理の負担を軽減。</p>
          <span class="ck-feature-preview">
            <img src="image/features/screens/11.png" alt="棚卸機能の画面">
            <span>ESL連携と組み合わせることで、棚卸業務の省力化と在庫精度の向上を実現。商品の状態を正しく把握し、資産管理や仕入判断にも活かせます。</span>
          </span>
        </a>
        <a href="business-support.php">
          <span class="ck-feature-icon" aria-hidden="true"><img src="image/features/12.png" alt=""></span>
          <h3>会計ソフト対応<br>CSVエクスポート</h3>
          <p>会計処理につながるデータ出力で、バックオフィスも効率化。</p>
          <span class="ck-feature-preview">
            <img src="image/features/screens/12.png" alt="会計ソフト対応CSVエクスポート機能の画面">
            <span>売上データや売掛データ等をfreee・マネーフォワード等の会計ソフトに取り込みやすいCSV形式で出力可能。手入力の手間を減らし、経理業務の効率化をサポートします。</span>
          </span>
        </a>
      </div>
    </div>
  </section>

  <section class="ck-home-section ck-home-dark ck-esl-highlight">
    <div class="ck-home-container ck-split">
      <figure class="ck-equal-visual-frame">
        <img src="image/chubo-kun-esl.png" alt="厨房君と電子棚札の連携イメージ">
      </figure>
      <div class="ck-home-section-head">
        <span>ESL SOLUTION</span>
        <h2>在庫管理の<br class="sp-only">ズレを、<br>仕組みでなくす。</h2>
        <p>
          厨房君のESL連携は、在庫データと現場の表示をリアルタイムにつなぎ、
          売約・再生・出荷までの情報ズレを防ぎます。確認や貼り替えに追われる
          ことなく、頑張らない在庫管理を実現。人ではなく仕組みで管理する、
          在庫管理の新しいスタンダードです。
        </p>
      </div>
      <div class="ck-split-action">
        <a href="esl-solution.php" class="ck-dark-button">電子棚札のメリットを見る</a>
      </div>
    </div>
  </section>

  <section class="ck-home-section ck-management-highlight">
    <div class="ck-home-container ck-split ck-split-reverse">
      <figure class="ck-equal-visual-frame">
        <img src="image/features/screens/10.png" alt="売上目標に対する実績と計画の画面">
      </figure>
      <div class="ck-home-section-head">
        <span>MANAGEMENT</span>
        <h2>現場DXは、<br>経営判断も<br class="sp-only">速く<br class="pc-only">する。</h2>
        <p>
          売上、売上原価、粗利。現場のデータをリアルタイムにつなぎ、経営判断に必要な
          数字を見える化します。数字を集めるための時間を減らし、次の一手を考える時間を増やす。
          それが、厨房君が目指す現場DXです。
        </p>
      </div>
      <div class="ck-split-action">
        <a href="business-support.php" class="ck-light-button">経営サポートを見る</a>
      </div>
    </div>
  </section>

  <section class="ck-home-section ck-home-dark ck-home-extensions">
    <div class="ck-home-container">
      <div class="ck-home-section-head ck-center">
        <span>EXTENSION</span>
        <h2>厨房君を、<br class="sp-only">もっと便利に。<br>もっと使いやすく。</h2>
        <p>契約、現場作業、お客様とのつながりまで。厨房君を中心に、実務の周辺まで使いやすく整えます。</p>
      </div>
      <div class="ck-extension-list">
        <article class="ck-extension-card">
          <figure>
            <img src="image/cloudsign_link.png" alt="クラウドサイン連携">
          </figure>
          <div>
            <span>CLOUDSIGN LINK</span>
            <h3>クラウドサイン連携</h3>
            <p class="ck-extension-lead">契約業務も厨房君で一元管理し、見積から契約までをシームレスに。</p>
            <p>厨房君は、電子契約サービス「クラウドサイン」とシステム連携。見積、発注、契約までの流れを切り離さず、厨房君上で一元管理できます。紙のやり取りや押印の手間を減らしながら、契約の抜け漏れを防ぎます。</p>
          </div>
        </article>
        <article class="ck-extension-card">
          <figure>
            <img src="image/sp-chubokun.png" alt="厨房君 for SP">
          </figure>
          <div>
            <span>CHUBO-KUN FOR SP</span>
            <h3>厨房君 for SP</h3>
            <p class="ck-extension-lead">店舗内の作業に特化した、スマホで使いやすい厨房君。</p>
            <p>厨房君はパソコンでの使用を前提にしながら、現場での使いやすさも大切にしています。スマホから一部機能を操作できるため、ESLの連携作業や入荷商品の入庫処理を、店舗内でスムーズに進められます。</p>
          </div>
        </article>
        <article class="ck-extension-card">
          <figure>
            <img src="image/prochubo-hit-app.png" alt="プロ厨房ヒット公式アプリ">
          </figure>
          <div>
            <span>PRO CHUBO HIT APP</span>
            <h3>プロ厨房ヒット公式アプリ</h3>
            <p class="ck-extension-lead">お客様との関係性を深め、プロジェクト管理にも活かす公式アプリ。</p>
            <p>プロ厨房ヒット公式アプリは、お客様とのつながりをより強く保つためのアプリです。厨房君のデータを活用することで、情報共有や進捗の可視化を進め、サービス全体の価値を高めます。</p>
          </div>
        </article>
      </div>
    </div>
  </section>

  <section class="ck-home-section ck-home-types" id="kitchen-type">
    <div class="ck-home-container">
      <div class="ck-home-section-head ck-center">
        <span>LINEUP</span>
        <h2>目的や規模に応じて、<br>4つの厨房君を<br class="sp-only">ご用意。</h2>
      </div>
      <div class="ck-type-grid">
        <article class="ck-type-std"><img src="image/plan/std.png" alt="厨房君 STD">
          <p>小規模向け。基本業務と在庫管理をシンプルに。</p>
        </article>
        <article class="ck-type-pro"><img src="image/plan/pro.png" alt="厨房君 PRO">
          <p>中規模向け。売上管理まで含めて業務効率化。</p>
        </article>
        <article class="ck-type-dlx"><img src="image/plan/dlx.png" alt="厨房君 DLX">
          <p>電子契約連携まで視野に入れた実務強化モデル。</p>
        </article>
        <article class="ck-type-hyper"><img src="image/plan/hyper.png" alt="厨房君 HYPER">
          <p>大規模、多店舗、ESL連携まで含むフル装備モデル。</p>
        </article>
      </div>
      <div class="ck-type-action">
        <a href="service.php" class="ck-light-button">機能とプランを詳しく見る</a>
      </div>
    </div>
  </section>

  <section class="ck-home-section ck-home-dark">
    <div class="ck-home-container ck-story-cta">
      <div class="ck-home-section-head ck-center">
        <span>STORY</span>
        <h2>厨房君は、<br class="sp-only">現場の困りごと<br class="pc-only">から<br class="sp-only">生まれました。</h2>
        <p>
          最初は、なんとなくやっていた業務でした。
          けれど、もっと良くできるはずだという違和感が、厨房君の開発につながっています。
        </p>
        <a href="story.php" class="ck-dark-button">開発ストーリーを見る</a>
      </div>
    </div>
  </section>

  <section class="ck-home-section ck-faq-section">
    <div class="ck-home-container">
      <div class="ck-home-section-head ck-center">
        <span>FAQ</span>
        <h2>よくある質問</h2>
      </div>
      <div class="ck-faq-list">

        <div class="qa-box">
          <button class="qa-q" type="button">
            Q1. 厨房君だけ利用することはできますか？
          </button>
          <div class="qa-a">
            <div class="answer-label">
              A. フランチャイズ加盟店専用のシステムです。
            </div>
            <div class="answer-detail">
              厨房君は、プロ厨房ヒットのフランチャイズ加盟店向けに開発した業務支援システムです。
              システムだけを提供するのではなく、業務フローや運用方法も含めてサポートするため、
              単体でのご利用は行っておりません。
            </div>
          </div>
        </div>

        <div class="qa-box">
          <button class="qa-q" type="button">
            Q2. ITに詳しくなくても運用できますか？
          </button>
          <div class="qa-a">
            <div class="answer-label">
              A. パソコンの基本操作ができれば問題ありません。
            </div>
            <div class="answer-detail">
              導入時には操作説明や研修を実施し、運用開始後も本部がサポートします。
              現在利用している加盟店でも、特別なIT知識がないスタッフが日常業務で活用しています。
            </div>
          </div>
        </div>

        <div class="qa-box">
          <button class="qa-q" type="button">
            Q3. 中古厨房機器を扱ったことがなくても加盟できますか？
          </button>
          <div class="qa-a">
            <div class="answer-label">
              A. はい、経験よりも「一緒に成長したい」という気持ちを大切にしています。
            </div>
            <div class="answer-detail">
              厨房君をはじめ、業務の進め方や中古厨房機器のノウハウは、本部がサポートします。
              加盟店と情報を共有しながら、一緒に成長できる仕組みを整えていますので、まずはお気軽にご相談ください。
            </div>
          </div>
        </div>

        <div class="qa-box">
          <button class="qa-q" type="button">
            Q4. 導入にはどのくらい時間がかかりますか？
          </button>
          <div class="qa-a">
            <div class="answer-label">
              A. ご契約後、環境構築と研修を経て運用を開始します。
            </div>
            <div class="answer-detail">
              会社の規模や準備状況によって異なりますが、導入計画を本部と一緒に進めるため、
              無理なくスムーズに運用を開始できます。
            </div>
          </div>
        </div>

        <div class="qa-box">
          <button class="qa-q" type="button">
            Q5. 今使っているシステムから移行できますか？
          </button>
          <div class="qa-a">
            <div class="answer-label">
              A. 会社の状況に合わせて移行方法をご提案します。
            </div>
            <div class="answer-detail">
              現在お使いのシステムや運用方法を確認し、できるだけ業務を止めない形で
              段階的な移行をご提案します。
            </div>
          </div>
        </div>

        <div class="qa-box">
          <button class="qa-q" type="button">
            Q6. システムは今後も改善されますか？
          </button>
          <div class="qa-a">
            <div class="answer-label">
              A. 現場の声をもとに継続して改善しています。
            </div>
            <div class="answer-detail">
              厨房君は実際の現場で日々利用されているシステムです。
              加盟店から寄せられたご意見や新しい業務課題を反映しながら、
              継続的に機能改善・アップデートを行っています。
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <?php if ($ckHomeUpdates) : ?>
    <section class="ck-home-section ck-home-updates">
      <div class="ck-home-container">
        <div class="ck-home-section-head ck-center">
          <span>UPDATE</span>
          <h2>厨房君<br>UPDATEログ</h2>
        </div>
        <div class="ck-home-update-grid">
          <?php foreach ($ckHomeUpdates as $item) : ?>
            <article>
              <div class="ck-log-card-meta">
                <time datetime="<?php echo ck_h($item['published_at'] ?? ''); ?>"><?php echo ck_h($item['published_at'] ?? ''); ?></time>
                <em><?php echo ck_h($item['category'] ?? ''); ?></em>
              </div>
              <h3><?php echo ck_h($item['title'] ?? ''); ?></h3>
              <p><?php echo ck_h(($item['public_summary'] ?? '') !== '' ? $item['public_summary'] : ($item['summary'] ?? '')); ?></p>
            </article>
          <?php endforeach; ?>
        </div>
        <div class="ck-type-action">
          <a href="updates.php" class="ck-light-button">アップデート情報を見る</a>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <section class="ck-home-final">
    <div class="ck-home-container">
      <span>FRANCHISE</span>
      <h2>厨房機器ビジネスを、<br>仕組みで強くする。</h2>
      <p>厨房君の活用事例や導入メリットは、企業HP内のFC募集ページで<br class="pc-only">詳しくご確認いただけます。</p>
      <a href="https://rise-up.net/franchise/" target="_blank" rel="noopener noreferrer">FC加盟をご検討の方はこちら</a>
    </div>
  </section>
</main>

<script src="js/script.js?v=20260702-5"></script>
<!-- /wp:html -->
