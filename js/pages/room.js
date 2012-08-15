var rooms = new Array();
var activeArea = '';

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
		}
		$("div.room").draggable({
			drag: function() {
				var obj = { x: $(this).position().left,
							y: $(this).position().top };
				var loc = $(this).attr('id').substr(5);
				rooms[loc]['RC_MAP'] = obj;
			},
			stop: function() {
				var loc = $(this).attr('id').substr(5);
				var obj = { x: $(this).position().left,
							y: $(this).position().top };
				rooms[loc]['RC_MAP'] = obj;
				saveRoom(loc);
			}
		});
	}, 'json');
}

function saveRoom(id) {
	$.post('/php/room/saveRoom.php', {
		area: activeArea,
		room: rooms[id]
	}, function(ret) {
		
	}, '');
}