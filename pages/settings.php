<?
$settings = loadSettings();
$motd = readRanvierFile('/data/motd');

?>
<script language="javascript" src="/js/prettify/prettify.js"></script>
<div id="titlebody">
	<div class="row-fluid">
		<div class="span12 titlediv">
			<h1>Settings</h1>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span3">
			<div class="row-fluid">
				<div class="span12 widget">
					<div class="well">
						<ul class="nav nav-list" id="settingList" style="padding: 8px 0;">
							<li class="motd active" onclick="show('motd');">
								<a href="#">MOTD</a>
							</li>
							<li class="objectBehaviors" onclick="show('objectBehaviors'); getCurrentObjectBehaviors();">
								<a href="#">Object Behaviors</a>
							</li>
							<li class="mobBehaviors" onclick="show('mobBehaviors'); getCurrentMobBehaviors();">
								<a href="#">Mob Behaviors</a>
							</li>
							<li class="playerSettings" onclick="show('playerSettings'); getCurrentPlayerSetup();">
								<a href="#">Player Definition</a>
							</li>
							<li class="controlFiles" onclick="show('controlFiles');">
								<a href="#">Control File Locations</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="span9">
			<div class="row-fluid">
				<div class="span12 widget" style="margin-left: 0">
					<h2 id="settingHeader">MOTD</h2>
					<div class="widget-inside" id="settingPanels">
						<div class="row-fluid" id="motd" style="display: block" alt="MOTD">
							<div class="row-fluid">
								<div class="span12"><textarea rows="20" class="span12" id="motd_file"><?= $motd; ?></textarea></div>
							</div>
							<div class="row-fluid">
								<div class="span12" style="text-align: center"><a href="#" class="btn" onClick="saveMOTD();" id="motdBtn">Save MOTD</a></div>
							</div>
						</div>
						<div class="row-fluid" id="playerSettings" style="display: none" alt="Player Settings">
							<table class="table table-striped table-bordered table-condensed" id="playerAttrTable">
								<thead>
									<tr>
										<th>Field &nbsp;&nbsp;&nbsp;<a href="#" onClick="editAttribute(-1);" class="btn btn-sucess">Create New</a></th>
										<th width="100">Default</th>
										<th width="100">Type</th>
										<th width="100">Actions</th>
									</tr>
								</thead>
								<tbody>
								
								</tbody>
							</table>
						</div>
						<div class="row-fluid" id="objectBehaviors" style="display: none" alt="Object Behaviors">
							<table class="table table-striped table-bordered table-condensed" id="objectBehaviorTable">
								<thead>
									<tr>
										<th>File &nbsp;&nbsp;&nbsp;<a href="#" onClick="createBehavior('objects');" class="btn btn-sucess">Create New</a></th>
										<th width="100">Actions</th>
									</tr>
								</thead>
								<tbody>
								
								</tbody>
							</table>
						</div>
						<div class="row-fluid" id="mobBehaviors" style="display: none" alt="Mob Behaviors">
							<table class="table table-striped table-bordered table-condensed" id="mobBehaviorsTable">
								<thead>
									<tr>
										<th>File &nbsp;&nbsp;&nbsp;<a href="#" onClick="createBehavior('npcs');" class="btn btn-sucess">Create New</a></th>
										<th width="100">Actions</th>
									</tr>
								</thead>
								<tbody>
								
								</tbody>
							</table>
						</div>
						<div class="row-fluid" id="controlFiles" style="display: none" alt="Control File Locations">
							<div class="row-fluid">
								<div class="span3">Local File Path</div>
								<div class="span9 input-append"><input class="span10" id="filePath" size="16" type="text" value="<?= $settings['base_game']; ?>" placeholder="Absolute Path to Base Ranvier MUD Directory"><a href="#" class="btn" value="Save" onClick="saveFilePath()" id="SaveBtn">Save</a></div>
							</div>
							<div class="row-fluid" id="notWriteable" style="display: none">
								<div class="span2">&nbsp;</div>
								<div class="span10"><span class="label label-warning">Warning!!</span> The file structure needs to be writable by the web server.  This path will not work correctly until its fixed.</div></div>
							</div>
							<div class="row-fluid" id="notThere" style="display: none">
								<div class="span2">&nbsp;</div>
								<div class="span10"><span class="label label-important">Error!!</span> The path given does not point to a valid Ranvier MUD installation.  Please double-check your file path.</div></div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal hide fade" id="myModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<h3>Field Editor</h3>
	</div>
	<div class="modal-body">
		<div class="row-fluid">
			<div class="span4">Field Name<input type="hidden" id="editorID"></div>
			<div class="span8"><input type="text" class="span12" id="editorField"></div>
		</div>
		<div class="row-fluid">
			<div class="span4">Default Value</div>
			<div class="span8"><input type="text" class="span12" id="editorDefault"></div>
		</div>
		<div class="row-fluid">
			<div class="span4">Field Type</div>
			<div class="span8"><select id="editorType" onChange="handleSettingChange()"><option value="string">String</option><option value="number">Number</option><option value="enum">Defined Item</option></select></div>
		</div>
		<div class="row-fluid hideable setting_enum">
			<div class="span3"><label class="control-label">List</label></div>
			<div class="span9">
				<ul class="span12 staticList">
					<li class="addItemToList span12 input-append"><input id="addItem" placeholder="Create an Item" class="span10"><button class="btn" type="button" onClick="addItemToList($('#addItem').val())">Add</button></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>
		<a href="#" class="btn btn-primary" onClick="saveAttribute()">Save changes</a>
	</div>
</div>
<div class="modal hide fade" id="createBehaviorModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<h3>Behavior Creator</h3>
	</div>
	<div class="modal-body">
		<div class="row-fluid">
			<div class="span4">Behavior Name<input type="hidden" id="behaviorType"></div>
			<div class="span8"><input type="text" class="span12" id="behavior"></div>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>
		<a href="#" class="btn btn-primary" onClick="saveBehavior()">Save Behavior</a>
	</div>
</div>
<style>
.well { margin-bottom: 0px !important; }
textarea { line-height: 12px; font-size: 12px; font-family: courier, fixed-width; }
table th {
	font-size: 14px !important
}
ul.staticList {
	border: 2px solid black;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	box-shadow: 0 1px 2px #858585;
}
ul.staticList li {
	margin-left: 0 !important;
	padding-left: 14px;
	padding-top: 4px;
}
ul.staticList li.addItemToList {
	background-color: #666 !important;
	padding: 0 !important;
}
ul.staticList li:nth-child(odd) {
	background-color: #DDD
}
</style>