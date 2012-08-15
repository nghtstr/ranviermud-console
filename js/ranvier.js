if(typeof String.prototype.trim !== 'function') {
	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g, ''); 
	}
}

function uniqueID(pre) {
	pre = (typeof pre == 'undefined') ? 'id' : pre;
	return pre + (new Date()).getTime();
}

function hideNotification(id) {
	$('#' + id).fadeOut(700);
}

function createNotification(id, type, size, header, text, icon) {
	// Do Notification code here
	var code = '<div class="row-fluid" id="' + id + '"><div class="span' + size + '"><div class="alert alert-block ' + type + '">';
	code += '<button class="close" id="' + id + '">x</button>';
	code += '<h4 class="alert-heading"><i class="' + icon + ' icon-color"></i> ' + header + '</h4>';
	code += text + '</div></div></div>';
	return code;
}

function createNotificationBefore(type, size, header, text, icon, child, timeout) {
	timeout = (typeof timeout == 'undefined') ? 0 : timeout;
	var id = uniqueID('id');
	var code = createNotification(id, type, size, header, text, icon);
	$(child).before(code);
	$('#' + id + ' button').click(function(e) {
		e.preventDefault();
		hideNotification(this.id);
	});
	if (timeout > 0) setTimeout("hideNotification('" + id + "')", timeout);
}

function createNotificationAfter(type, size, header, text, icon, child, timeout) {
	timeout = (typeof timeout == 'undefined') ? 0 : timeout;
	var id = uniqueID('id');
	var code = createNotification(id, type, size, header, text, icon);
	$(child).after(code);
	$('#' + id + ' button').click(function(e) {
		e.preventDefault();
		hideNotification(this.id);
	});
	if (timeout > 0) setTimeout("hideNotification('" + id + "')", timeout);
}