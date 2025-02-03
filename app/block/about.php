<?php  
// 引入自动加载文件
!defined('PROJECT_ROOT') && require_once __DIR__. "/../../autoload.php";
include(PROJECT_ROOT. "/app/block/head.php");
include(PROJECT_ROOT. "/app/block/navigation.php");
?>

<main class="container">
    <header>
        <h2>关于我们</h2>
    </header>
    <section class="about-section">
        <h3>网站背景</h3>
        <p>枫桥驿站是一个专注于分享生活、记录时光的博客平台。我们希望通过这个平台，让用户能够分享自己的故事、经验和见解，同时也能够从他人的分享中获得启发和乐趣。</p>
    </section>
    <section class="about-section">
        <h3>我们的目标</h3>
        <p>我们的目标是打造一个温馨、友好、有价值的社区，让每一个用户都能够在这里找到归属感和认同感。我们鼓励用户积极参与互动，分享自己的生活点滴，共同营造一个积极向上的网络环境。</p>
    </section>
    <section class="about-section">
        <h3>团队成员</h3>
        <p>枫桥驿站的团队由一群热爱生活、热爱写作的人组成。我们来自不同的背景，但都有着共同的目标和愿景。我们相信，通过我们的努力，枫桥驿站能够成为一个让用户喜爱和信赖的平台。</p>
    </section>
</main>
<?php include(PROJECT_ROOT . "/app/block/footer.php"); ?>
