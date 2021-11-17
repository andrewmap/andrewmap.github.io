<?php
    if(basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
        header("Location: http://127.0.0.1/index.php");
        die();
    }
?>

<header>
    <div class="header-logo"><img src="img/logowhite2.png"></div>
    <!-- <div class="header-title">S.T.A.R.</div> -->
    

    <p>STK</p>
    <a href="index.php">Daily Reports</a>
    <a href="pl_real_time.php">STK PL</a>
    <!-- <a href="attribution.php">Attribution</a>
    <a href="attribution_short.php">Attribution Short</a> -->
    <!--<a href="pair_trade_pl.php">Comparativo Fundos</a> -->
    
    <a href="presentations.php">Presentations</a>
    <a href="optimal.php">Portfolio Universe</a>
    <a href="valor_relativo.php">Valor Relativo</a>
    <!-- <a href="lbpl.php">Quotes</a> -->

    <p>Account</p>
    <a href="user.password.php">Change Password</a>
    <a href="user.create.php">Create User</a>
    <a href="user.remove.php">Remove User</a>
    <a href="users.php">Users</a>
    <a href="registry.php">Registry</a>
    <a href="logout.php">Logout</a>

    <div class="header-subtitle">User: <?php echo($_SESSION['user']); ?></div>
</header>

<div class="menu-button">
    <i class="fas fa-bars fa-fw" id="menu-open"></i>
    <i class="fas fa-times fa-fw" id="menu-close"></i>
</div>

<script>
    $('.menu-button').click(function() {
        $('header').stop(true,true).toggle("slide", {direction: 'left'}, 200);
        $('body').toggleClass('section_hide');
        $('.fa-bars').stop(true,true).toggle(0);
        $('.fa-times').stop(true,true).toggle(0);
    });
</script>
