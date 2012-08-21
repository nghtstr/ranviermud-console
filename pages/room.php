<?

$settings = loadSettings();
if (!is_array($settings['rooms'])) {
	//error_reporting(E_ALL);
	loadRoomList();
	$settings = loadSettings();
}
include(dirname(__FILE__) . '/../php/global/yaml/spyc.php');

$areas = array();

if ($handle = opendir($settings['base_game'] . '/entities/areas')) {
	while (false !== ($entry = readdir($handle))) {
		if ($entry != "." && $entry != "..") {
			$array = Spyc::YAMLLoad($settings['base_game'] . '/entities/areas/' . $entry . '/manifest.yml');
			$areas[$entry] = $array[$entry]['title'];
		}
	}
	closedir($handle);
}

?><script language="javascript" src="/js/jquery.domline.min.js"></script>
<div id="titlebody">
	<div class="row-fluid">
		<div class="span12 titlediv">
			<h1>Areas and Rooms</h1>
		</div>
	</div>
</div>
<textarea id="roomList" style="display: none"><?= json_encode($settings['rooms']); ?></textarea>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span3">
			<div class="row-fluid">
				<div class="span12 widget">
					<h2>Areas &nbsp; <a href="#" onClick="addNewArea()" class="btn btn-mini"><i class="icon-plus-sign"></i> Add</a></h2>
					<div class="well">
						<ul class="nav nav-list" id="settingList" style="padding: 8px 0;">
							<?
							foreach ($areas as $k=>$l) {
								echo '<li onClick="loadAreaMap(\'' . $k . '\');" id="area_' . $k . '" class="areaMenu"><a href="#">' . $l . '<i class="icon-info-sign"></i></a></li>';
							}
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="span9">
			<div class="row-fluid">
				<div class="span12 widget" style="margin-left: 0">
					<h2>Rooms &nbsp; <a href="#" onClick="addNewRoom()" class="btn btn-primary btn-mini"><i class="icon-asterisk icon-white"></i> New</a></h2>
					<div class="widget-inside" id="settingPanels">
						<div class="span12 map"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal hide fade" id="areaEditor">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<h3>Area Editor</h3>
	</div>
	<div class="modal-body">
		<div class="row-fluid">
			<div class="span4">Area Short Name</div>
			<div class="span8"><input type="text" class="span12" id="areaShortName"></div>
		</div>
		<div class="row-fluid">
			<div class="span4">Area Name</div>
			<div class="span8"><input type="text" class="span12" id="areaName"></div>
		</div>
		<div class="row-fluid">
			<div class="span4">Area Level Range</div>
			<div class="span8"><div class="row-fluid"><div class="span2"><input type="text" class="span12" placeholder="Min" id="areaLevelMin"></div><div class="span1">to</div><div class="span2"><input type="text" class="span12" placeholder="Max" id="areaLevelMax"></div></div></div>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>
		<a href="#" class="btn btn-primary" onClick="saveAreaEditor()">Save changes</a>
	</div>
</div>
<div class="modal hide fade" id="roomEditor">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<h3>Room Editor</h3>
	</div>
	<div class="modal-body">
		<div class="row-fluid">
			<div class="span4">Room</div>
			<div class="span8 input-prepend"><span class="add-on">#</span><input type="text" placeholder="ID" class="span3" id="roomID" style="margin-right: 8px;"><input type="text" placeholder="Room Title" class="span8" id="roomTitle"></div>
		</div>
		<div class="row-fluid">
			<div class="span4">Room Description</div>
			<div class="span8"><textarea class="span12" id="roomDescription" rows="6"></textarea></div>
		</div>
		<div class="row-fluid">
			<div class="span4">Room Script</div>
			<div class="span8"><a href="#" class="btn" onClick="openRoomScriptEditor()">Script Editor</a></div>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>
		<a href="#" class="btn btn-primary" onClick="saveRoomEditor()">Save changes</a>
	</div>
</div>
<div class="modal hide fade" id="junctionEditor">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<h3>Junction Editor</h3>
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

<style>
.well { margin-bottom: 0px !important; }
div.map {
	height: 600px;
	overflow: auto;
	position: relative;
}
div.room {
	height: 50px;
	width: 60px;
	border: 1px solid black;
	position: absolute;
	z-index: 400;
	background-color: white;
}
div.transition
{
	height: 50px;
	width: 60px;
	border: 2px ridge black;
	position: absolute;
	z-index: 400;
	background-color: #D1FBFC;
	-webkit-transform: skew(20deg);
	-moz-transform: skewX(20deg);
	-o-transform: skew(20deg);
}
div.line{
	-webkit-transform-origin: 0 50%;
	   -moz-transform-origin: 0 50%;
			transform-origin: 0 50%;
				 
	height: 5px; /* Line width of 3 */
	background: #2B7F11; /* Black fill */
	opacity: 0.5;
	box-shadow: 0 0 8px #18B22A;
	z-index: 200;

}
li.areaMenu i {
	float: right;
}
</style>