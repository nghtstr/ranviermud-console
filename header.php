<?
	$sec = $_POST['sec'];
	if (($sec != 'room') && ($sec != 'mobs') && ($sec != 'item') && ($sec != 'home') && ($sec != 'settings')) {
		$settings = loadSettings();
		$sec = $_POST['sec'] = ($settings['base_game'] != '' ? 'home' : 'settings');
	}
?>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<button type="button"class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="brand" href="/">Ranvier Console</a>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li class="<?= ($sec == 'home' ? 'active' : ''); ?>">
							<a href="?sec=home" class="post"><i class="icon-home icon-white"></i> Home</a>
						</li>
						<li class="<?= ($sec == 'room' ? 'active' : ''); ?>">
							<a href="?sec=room" class="post">Rooms</a>
						</li>
						<li class="<?= ($sec == 'mobs' ? 'active' : ''); ?>">
							<a href="?sec=mobs" class="post">Mobs</a>
						</li>
						<li class="<?= ($sec == 'item' ? 'active' : ''); ?>">
							<a href="?sec=item" class="post">Items</a>
						</li>
						<li class="<?= ($sec == 'settings' ? 'active' : ''); ?>">
							<a href="?sec=settings" class="post"><i class="icon-white icon-cog"></i> Settings</a>
						</li>
						
					</ul>
				</div>
			</div>
		</div>
	</div>
	<script language="javascript" src="/js/pages/<?= $sec; ?>.js"></script>