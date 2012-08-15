var attributes = new Array();

function show(item) {
	$('#settingList li').removeClass('active');
	$('#settingList li.'+item).addClass('active');
	$('#settingPanels > div').hide();
	$('#' + item).show();
	$('#settingHeader').text($('#' + item).attr('alt'));
}

function saveFilePath() {
	$('#notWriteable, #notThere').hide();
	$.post('/php/settings/checkPathWritable.php', {
		path: $('#filePath').val()
	}, function (ret) {
		if (ret.status == 'NOWRITE') { $('#notWriteable').show(); }
		if (ret.status == 'FAILED') { $('#notThere').show(); }
		if (ret.status == 'OK') { $('#SaveBtn').addClass('btn-success').text('Saved').doTimeout('ashdajhfas', 1200, function() {
			this.text('Save').removeClass('btn-success');
		}); }
	}, 'json');
}

function saveMOTD() {
	$.post('/php/settings/saveMOTD.php', {
		motd: $('#motd_file').val()
	}, function(ret) {
		if (ret.status == 'OK') { $('#motdBtn').addClass('btn-success').text('Saved').doTimeout('eregtfzdfd', 1200, function() {
			this.text('Save MOTD').removeClass('btn-success');
		}); } else {
			$('#motdBtn').addClass('btn-error').text('Error').doTimeout('fdgfdssd', 1200, function() {
				this.text('Save MOTD').removeClass('btn-error');
			});
		}
	}, 'json');
}

function defNBSP(x) { return (x == '' ? '&nbsp;' : x); }

function getCurrentPlayerSetup() {
	$.post('/php/settings/getPlayerSetup.php', {
	
	}, function(ret) {
		var types = { string: 'String', number: 'Number', enum: 'Defined Item' };
		$('#playerAttrTable tbody').html('');
		attributes = ret;
		for (var x = 0; x < ret.length; x++) {
			$('#playerAttrTable tbody').append('<tr><td>' + ret[x].field + '</td><td>' + defNBSP(ret[x].default) + '</td><td>' + types[ret[x].type] + '</td><td><a href="#" onClick="editAttribute(' + x + ');"><i class="icon-pencil"></i> Edit</a></td></tr>');
		}
	}, 'json');
}

function editAttribute(x) {
	$('#editorID').val(x);
	if (x >= 0) {
		$('#editorField').val(attributes[x].field);
		$('#editorDefault').val(attributes[x].default);
		$('#editorType').val(attributes[x].type);
		$('ul.staticList li').remove(':not(.addItemToList)');
		if ($.isArray(attributes[x].extra)) {
			for (var n = 0; n < attributes[x].extra.length; n++) { addItemToList(attributes[x].extra[n]); }
		}
	} else {
		$('#editorField').val('');
		$('#editorDefault').val('');
		$('#editorType').val('');
		$('ul.staticList li').remove(':not(.addItemToList)');
	}
	$('#myModal').modal('show');
}

function saveAttribute() {
	var id = $('#editorID').val();
	if (id >= 0) {
		attributes[id].field = $('#editorField').val();
		attributes[id].default = $('#editorDefault').val();
		attributes[id].type = $('#editorType').val();
		if (attributes[id].type == 'enum') {
			attributes[id].extra = $("ul.staticList").sortable('toArray');
		} else {
			attributes[id].extra = '';
		}
	} else {
		attributes.push({
			field: $('#editorField').val(),
			default: $('#editorDefault').val(),
			type: $('#editorType').val(),
			extra: ($('#editorType').val() == 'enum' ? $("ul.staticList").sortable('toArray') : '')
		});
	}
	$.post('/php/settings/savePlayerSetup.php', {
		attr: attributes
	}, function(ret) {
		getCurrentPlayerSetup();
		$('#myModal').modal('hide');
	}, '');
}

function handleSettingChange() {
	$('div.hideable').hide();
	$('.setting_' + $('#editorType').val()).show();
}

function addItemToList(item) {
	if (item.trim() != '') {
		$('ul.staticList').append('<li class="span12" id="' + item + '">' + item + '</li>');
	}
	$('#addItem').val('');
}

$(document).ready(function() {
	$('#myModal').modal({
		backdrop: 'static',
		keyboard: false,
		show: false
	});
	$("ul.staticList").sortable({
		items: "li:not(.addItemToList)",
	}).disableSelection();
});