<?php use Cake\Core\Configure; ?>
<?php $this->loadHelper('Link'); ?>
<?php $this->loadHelper('Content'); ?>
<header id="header">
    <nav class="navbar">
        <div class="menu-wrapper">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="<?php echo $this->Link->homeUrl(); ?>">
                    <?php echo $this->Html->image('logo.png'); ?>
                </a>
                <!-- /logo -->
            </div>
            <div class="collapse navbar-collapse navbar-main-collapse" id="#options">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#home">Home</a>
                    </li>
                    <li>
                        <a href="#about" >About</a>
                    </li>
                    <li>
                        <a href="#menu">Menu</a>
                        <ul>
                            <li><a href="#">Teste</a></li>
                            <li><a href="#">Teste</a></li>
                            <li><a href="#">Teste</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
