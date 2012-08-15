var rooms = new Array();
var activeArea = '';
var paths = new Array();

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

function setupLines() {
	for (var x = 0; x < paths.length; x++) {
		paths[x].line = createLine(
			rooms[paths[x].a].RC_MAP.x + ($('#room_' + paths[x].a).width() / 2),
			rooms[paths[x].a].RC_MAP.y + ($('#room_' + paths[x].a).height() / 2),
			rooms[paths[x].b].RC_MAP.x + ($('#room_' + paths[x].b).width() / 2),
			rooms[paths[x].b].RC_MAP.y + ($('#room_' + paths[x].b).height() / 2)
		);
	}
}

function changePath(id) {
	for (var x = 0; x < paths.length; x++) {
		if ((paths[x].a == id) || (paths[x].b == id)) {
			changeLine(
				paths[x].line,
				rooms[paths[x].a].RC_MAP.x + ($('#room_' + paths[x].a).width() / 2),
				rooms[paths[x].a].RC_MAP.y + ($('#room_' + paths[x].a).height() / 2),
				rooms[paths[x].b].RC_MAP.x + ($('#room_' + paths[x].b).width() / 2),
				rooms[paths[x].b].RC_MAP.y + ($('#room_' + paths[x].b).height() / 2)
			);
		}
	}
}

function loadAreaMap(area) {
	activeArea = area;
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
		setupLines();
	}, 'json');
}

function saveRoom(id) {
	$.post('/php/room/saveRoom.php', {
		area: activeArea,
		room: rooms[id]
	}, function(ret) {
		
	}, '');
}