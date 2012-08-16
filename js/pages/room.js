var rooms = new Array();
var roomList = '';
var roomListByArea = new Array();
var activeArea = '';
var paths = new Array();
var transitions = new Array();

function doTransformMath(x1,y1, x2,y2){
	return {
		length: Math.sqrt((x1-x2)*(x1-x2) + (y1-y2)*(y1-y2)),
		angle: Math.atan2(y2 - y1, x2 - x1) * 180 / Math.PI,
	};
}

function changeLine(line, x1,y1, x2,y2){
	var delta = doTransformMath(x1,y1, x2,y2);
	var transform = 'rotate('+ delta.angle +'deg)';
	$(line).css({
		'transform': transform,
		'top': y1 + 'px',
		'left': x1 + 'px'
	})
	.width(delta.length);
}

function createLine(x1,y1, x2,y2){
	var line = $('<div>')
		.appendTo('div.map')
		.addClass('line')
		.css({
		  'position': 'absolute'
		});
	
	changeLine(line, x1,y1, x2,y2);
	
	return line;
}

function addPath(start, end) {
	var doAdd = true;
	for (var x = 0; x < paths.length; x++) {
		if (((paths[x].a == start) && (paths[x].b == end)) || ((paths[x].b == start) && (paths[x].a == end))) {
			doAdd = false;
			x = paths.length;
		}
	}
	if (doAdd) {
		paths.push({a: start, b: end, line: ''});
	}
}

function getRoomCoord(id) {
	if (typeof(rooms[id]) == 'undefined') {
		return transitions[id];
	} else {
		return rooms[id].RC_MAP;
	}
}

function setupLines() {
	for (var x = 0; x < paths.length; x++) {
		var a = getRoomCoord(paths[x].a);
		var b = getRoomCoord(paths[x].b);
		paths[x].line = createLine(
			a.x + ($('#room_' + paths[x].a).width() / 2),
			a.y + ($('#room_' + paths[x].a).height() / 2),
			b.x + ($('#room_' + paths[x].b).width() / 2),
			b.y + ($('#room_' + paths[x].b).height() / 2)
		);
	}
}

function changePath(id) {
	for (var x = 0; x < paths.length; x++) {
		if ((paths[x].a == id) || (paths[x].b == id)) {
			var a = getRoomCoord(paths[x].a);
			var b = getRoomCoord(paths[x].b);
			changeLine(
				paths[x].line,
				a.x + ($('#room_' + paths[x].a).width() / 2),
				a.y + ($('#room_' + paths[x].a).height() / 2),
				b.x + ($('#room_' + paths[x].b).width() / 2),
				b.y + ($('#room_' + paths[x].b).height() / 2)
			);
		}
	}
}

function loadAreaMap(area) {
	activeArea = area;
	transitions = new Array();
	$.post('/php/room/loadAreaMap.php', {
		area: area
	}, function (r) {
		rooms = r;
		$('div.map div').remove();
		for (var loc in rooms) {
			if (typeof(rooms[loc]['RC_MAP']) == 'undefined') {
				rooms[loc]['RC_MAP'] = {x: 10, y: 10};
			}
			var rm = rooms[loc];
			$('div.map').append('<div class="room" id="room_' + rm.location + '" style="top: ' + rm.RC_MAP.y + 'px; left: ' + rm.RC_MAP.x + 'px"><span>' + rm.title.en + '</span></div>');
			for (var e = 0; e < rm.exits.length; e++) {
				addPath(loc, String(rm.exits[e].location));
				if (typeof(rm.exits[e].transition) != 'undefined') {
					if (typeof(transitions[rm.exits[e].location]) == 'undefined') {
						transitions[rm.exits[e].location] = rm.exits[e].transition;
						transitions[rm.exits[e].location]['rooms'] = new Array();
						transitions[rm.exits[e].location]['rooms'].push({l: loc, e: e});
						var name = (typeof(roomList[rm.exits[e].location]) != 'undefined' ? roomList[rm.exits[e].location].title : rm.exits[e].location);
						$('div.map').append('<div class="transition" id="room_' + rm.exits[e].location + '" style="top: ' + rm.exits[e].transition.y + 'px; left: ' + rm.exits[e].transition.x + 'px"><span>To ' + name + '</span></div>');
					} else {
						transitions[rm.exits[e].location]['rooms'].push({l: loc, e: e});
					}
				}
			}
		}
		$("div.room").draggable({
			drag: function() {
				var obj = { x: $(this).position().left,
							y: $(this).position().top };
				var loc = $(this).attr('id').substr(5);
				rooms[loc]['RC_MAP'] = obj;
				changePath(loc);
			},
			stop: function() {
				var loc = $(this).attr('id').substr(5);
				var obj = { x: $(this).position().left,
							y: $(this).position().top };
				rooms[loc]['RC_MAP'] = obj;
				saveRoom(loc);
			}
		});
		$("div.transition").draggable({
			drag: function() {
				var obj = { x: $(this).position().left,
							y: $(this).position().top };
				var loc = $(this).attr('id').substr(5);
				for (var n = 0; n < transitions[loc].rooms.length; n++) {
					var l = transitions[loc].rooms[n].l;
					var e = transitions[loc].rooms[n].e;
					rooms[l].exits[e].transition.x = obj.x;
					rooms[l].exits[e].transition.y = obj.y;
				}
				changePath(loc);
			},
			stop: function() {
				var loc = $(this).attr('id').substr(5);
				var obj = { x: $(this).position().left,
							y: $(this).position().top };
				for (var n = 0; n < transitions[loc].rooms.length; n++) {
					var l = transitions[loc].rooms[n].l;
					var e = transitions[loc].rooms[n].e;
					rooms[l].exits[e].transition.x = obj.x;
					rooms[l].exits[e].transition.y = obj.y;
					saveRoom(l);
				}
			}
		});
		setupLines();
	}, 'json');
}

function saveRoom(id) {
	$.post('/php/room/saveRoom.php', {
		area: activeArea,
		room: rooms[id]
	}, function(ret) {
		roomList[id].area = activeArea;
		roomList[id].title = rooms[id].title.en;
	}, '');
}

function addNewArea() {
	$('#areaName').val('');
	$('#areaShortName').val('').attr('disabled', false);
	$('#areaEditor').modal('show');
}

function addNewRoom() {
	if (activeArea == '') {
		alert('You have not selected an Area yet to put this room.  Please select or create your area now');
	} else {
		$('#roomID').val('').attr('disabled', false);
		$('#roomTitle').val('');
		$('#roomDescription').val('');
		$('#roomEditor').modal('show');
	}
}

function generateRoomByArea() {
	for (rid in roomList) {
		var a = roomList[rid].area;
		if (typeof(roomListByArea[a]) == 'undefined') roomListByArea[a] = new Array();
		roomListByArea[a].push({loc: rid, title: roomList[rid].title });
	}
}

$(document).ready(function (){
	$('#areaEditor, #roomEditor, #junctionEditor').modal({
		backdrop: 'static',
		keyboard: false,
		show: false
	});
	roomList = JSON.parse($('#roomList').val());
	$('#roomList').remove();
	generateRoomByArea();
	
	$('body').on({
		click: function(e) {
			
		}
	}, 'i.icon-info-sign');
	
	$('div.map').on({
		dblclick: function(e) {
			
		}
	}, 'div.room');
	
});