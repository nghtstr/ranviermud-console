<?

$settings = loadSettings();
loadRoomList();

$players = count(glob($settings['base_game'] . '/data/players/*.json'));
$areas = getAreaList();

?><script language="Javascript" src="/js/highcharts.js"></script>
<div id="titlebody">
	<div class="row-fluid">
		<div class="span12 titlediv">
			<h1>Home</h1>
			<div id="currentPlayers" style="display: none"><?= readRanvierFile('/stats/current.counts'); ?></div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span3">
			<div class="row-fluid">
				<div class="span12 widget">
					<h2 id="settingHeader">Status</h2>
					<div class="widget-inside">
						<div class="row-fluid">
							<div class="span12">
								<button class="span12 btn btn-success">Running</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12 widget">
					<h2 id="settingHeader">Quick Stats</h2>
					<div class="widget-inside">
						<div class="row-fluid">
							<div class="span9">Total Rooms</div>
							<div class="span3"><?= count($settings['rooms']); ?></div>
						</div>
						<div class="row-fluid">
							<div class="span9">Total Areas</div>
							<div class="span3"><?= count($areas); ?></div>
						</div>
						<div class="row-fluid">
							<div class="span9">Total Players</div>
							<div class="span3"><?= $players; ?></div>
						</div>
						<div class="row-fluid">
							<div class="span9">Total Items</div>
							<div class="span3">0</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="span9">
			<div class="row-fluid">
				<div class="span12 widget">
					<h2 id="settingHeader">Active Players</h2>
					<div class="widget-inside">
						<div class="row-fluid">
							<div class="span12">
								<div id="activePlayersGraph"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12 widget">
					<h2 id="settingHeader">Monthly Players</h2>
					<div class="widget-inside">
						<div class="row-fluid">
							<div class="span12">
								<div id="monthlyPlayersGraph"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>