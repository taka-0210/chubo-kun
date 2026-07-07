<!-- wp:html -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/core.css?v=20260701-15">
<link rel="stylesheet" href="css/components.css?v=20260701-1">
<link rel="stylesheet" href="css/pages.css?v=20260704-01">

<?php
$serviceSource = __DIR__ . '/private/service.txt';
$serviceHtml = is_readable($serviceSource) ? file_get_contents($serviceSource) : '';

function ck_extract_table($html, $className)
{
  if (preg_match('/<table\s+class="' . preg_quote($className, '/') . '"[\s\S]*?<\/table>/u', $html, $matches)) {
    return $matches[0];
  }

  return '';
}

$summaryTable = ck_extract_table($serviceHtml, 'chubo-table');
$detailTable = ck_extract_table($serviceHtml, 'chubo-feature-table');

function ck_build_detail_accordions($tableHtml)
{
  if (!$tableHtml) return '';
  if (!preg_match_all('/<tr>[\s\S]*?<\/tr>/u', $tableHtml, $rowMatches)) return '';

  $rows = $rowMatches[0];
  $groups = [];
  $current = null;

  foreach ($rows as $row) {
    if (preg_match('/<td\s+class="([^"]+)"\s+rowspan="(\d+)">([\s\S]*?)<\/td>/u', $row, $match)) {
      if ($current) $groups[] = $current;
      $current = [
        'class' => $match[1],
        'name' => trim(strip_tags(str_replace('<br>', ' ', $match[3]))),
        'rows' => [preg_replace('/<td\s+class="[^"]+"\s+rowspan="\d+">[\s\S]*?<\/td>\s*/u', '', $row, 1)]
      ];
      continue;
    }

    if ($current) {
      $current['rows'][] = $row;
    }
  }

  if ($current) $groups[] = $current;
  if (!$groups) return '';

  $html = '<div class="ck-detail-accordion-list">';
  foreach ($groups as $index => $group) {
    $open = $index === 0 ? ' open' : '';
    $html .= '<details class="ck-detail-accordion ck-detail-' . htmlspecialchars($group['class'], ENT_QUOTES, 'UTF-8') . '"' . $open . '>';
    $html .= '<summary><span>' . sprintf('%02d', $index + 1) . '</span><strong>' . htmlspecialchars($group['name'], ENT_QUOTES, 'UTF-8') . '</strong></summary>';
    $html .= '<div class="ck-service-table-wrap ck-service-table-dark ck-service-table-compact">';
    $html .= '<table><thead><tr><th>詳細機能</th><th>厨房君 STD</th><th>厨房君 PRO</th><th>厨房君 DLX</th><th>厨房君 HYPER</th></tr></thead><tbody>';
    $html .= implode('', $group['rows']);
    $html .= '</tbody></table></div></details>';
  }
  $html .= '</div>';

  return $html;
}

$detailAccordions = ck_build_detail_accordions($detailTable);
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
      <a href="service.php" aria-current="page">機能とプラン</a>
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

<main class="ck-service">
  <section class="ck-service-hero">
    <div class="ck-home-container">
      <span>PLAN & FEATURES</span>
      <h1>自社に合う厨房君が、<br>すぐにわかる。</h1>
      <p>
        規模、業務範囲、連携機能に応じて、4つのシリーズから最適な構成を選べます。
        月額費用と対応機能を比較しながら、現場に合うプランを確認できます。
      </p>
    </div>
  </section>

  <section class="ck-home-section ck-service-lineup">
    <div class="ck-home-container">
      <div class="ck-home-section-head ck-center">
        <span>LINEUP</span>
        <h2>4つのシリーズから、<br>最適な構成を選ぶ。</h2>
        <p>基本業務から売上管理、クラウドサイン、ESL連携まで。<br>現場の規模と運用に合わせて拡張できます。</p>
      </div>
      <p class="ck-plan-tax-note">※ 価格はすべて税別です</p>
      <div class="ck-plan-grid">
        <article class="ck-plan-card ck-plan-std">
          <img src="image/plan/std.png" alt="厨房君 STD">
          <div class="ck-plan-labels">
            <span>スタンダード</span>
            <span>小規模向け</span>
          </div>
          <div class="ck-plan-price"><strong>16,000円</strong><span>/ 月額</span></div>
          <p class="ck-plan-fit">まずは基本業務と在庫管理を整えたい会社に。</p>
          <ul>
            <li>基本業務管理</li>
            <li>在庫管理</li>
          </ul>
          <p>必要最低限の業務をしっかり管理できるシンプルなモデル。初めての導入におすすめです。</p>
        </article>
        <article class="ck-plan-card ck-plan-pro">
          <img src="image/plan/pro.png" alt="厨房君 PRO">
          <div class="ck-plan-labels">
            <span>プロ</span>
            <span>中規模向け</span>
          </div>
          <div class="ck-plan-price"><strong>20,000円</strong><span>/ 月額</span></div>
          <p class="ck-plan-fit">売上管理まで含めて、実務全体を見える化したい会社に。</p>
          <ul>
            <li>基本業務管理</li>
            <li>在庫管理</li>
            <li>売上管理</li>
          </ul>
          <p>業務効率化と売上管理の両立を実現。中規模店舗に最適な、実務重視のモデルです。</p>
        </article>
        <article class="ck-plan-card ck-plan-dlx">
          <img src="image/plan/dlx.png" alt="厨房君 DLX">
          <div class="ck-plan-labels">
            <span>デラックス</span>
            <span>中規模向け</span>
          </div>
          <div class="ck-plan-price"><strong>24,000円</strong><span>/ 月額</span></div>
          <p class="ck-plan-fit">契約業務までつなぎ、受発注や書類管理を効率化したい会社に。</p>
          <ul>
            <li>基本業務管理</li>
            <li>在庫管理</li>
            <li>売上管理</li>
            <li>クラウドサイン</li>
          </ul>
          <p>売上管理に加え、電子契約まで連携。契約、受発注、実績管理をよりスムーズに進められます。</p>
        </article>
        <article class="ck-plan-card ck-plan-hyper">
          <img src="image/plan/hyper.png" alt="厨房君 HYPER">
          <div class="ck-plan-labels">
            <span>ハイパー</span>
            <span>大規模向け</span>
          </div>
          <div class="ck-plan-price"><strong>39,000円</strong><span>/ 月額</span></div>
          <p class="ck-plan-fit">多店舗展開、在庫数の多い現場、ESL連携まで進めたい会社に。</p>
          <ul>
            <li>基本業務管理</li>
            <li>在庫管理</li>
            <li>売上管理</li>
            <li>クラウドサイン</li>
            <li>ESL管理</li>
          </ul>
          <p>多店舗展開や在庫数の多い現場に向けた最上位モデル。ESL連携まで含めて業務を一体化します。</p>
        </article>
      </div>
      <div class="ck-plan-note">
        <div>
          <span>アカウント利用料</span>
          <strong>2,000円 / 1名</strong>
        </div>
      </div>
      <p class="ck-plan-footnote">※ ご利用にはプロ厨房ヒットのFCに加盟する必要があります。その際に加盟金など別途費用がかかります。</p>
    </div>
  </section>

  <section class="ck-home-section ck-service-summary">
    <div class="ck-home-container">
      <div class="ck-home-section-head ck-center">
        <span>COMPARE</span>
        <h2>主要機能を比較する。</h2>
        <p>まずは、必要な業務範囲を確認してください。<br>基本業務、在庫、売上、契約、ESLのどこまで必要かで、最適なシリーズが変わります。</p>
      </div>
      <div class="ck-compare-guide">
        <article>
          <span>01</span>
          <strong>基本業務</strong>
          <p>顧客、仕入、再生、在庫を中心に現場の情報を整えます。</p>
        </article>
        <article>
          <span>02</span>
          <strong>売上管理</strong>
          <p>販売状況や実績まで見える化したい場合はPRO以上が目安です。</p>
        </article>
        <article>
          <span>03</span>
          <strong>外部連携</strong>
          <p>契約やESLまでつなぐ場合は、DLXまたはHYPERが選択肢になります。</p>
        </article>
      </div>
      <div class="ck-service-table-wrap">
        <?= $summaryTable ?: '<p class="ck-service-empty">主要機能表を読み込めませんでした。</p>'; ?>
      </div>
    </div>
  </section>

  <section class="ck-home-section ck-service-detail">
    <div class="ck-home-container">
      <div class="ck-home-section-head">
        <span>DETAIL</span>
        <h2>詳細機能一覧</h2>
        <p>顧客、仕入、再生、在庫、販売、売上、外部連携まで。<br>プランごとの対応範囲を確認できます。</p>
      </div>
      <?= $detailAccordions ?: '<div class="ck-service-table-wrap ck-service-table-dark">' . ($detailTable ?: '<p class="ck-service-empty">詳細機能表を読み込めませんでした。</p>') . '</div>'; ?>
    </div>
  </section>

  <section class="ck-home-final">
    <div class="ck-home-container">
      <span>FRANCHISE</span>
      <h2>自社に合う厨房君を、<br>仕組みから選ぶ。</h2>
      <p>必要な機能や店舗規模に合わせて、最適なシリーズをご提案します。導入やFC加盟についてもあわせてご相談いただけます。</p>
      <a href="https://rise-up.net/franchise/" target="_blank" rel="noopener noreferrer">FC加盟をご検討の方はこちら</a>
    </div>
  </section>
</main>

<script src="js/script.js?v=20260701-2"></script>
<!-- /wp:html -->
