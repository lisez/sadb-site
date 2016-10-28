function appendImage(file, src, pos) {
	$.ajax({
		url: file,
		success: function(){
			var loc = $('<a>').attr('href',src);
			var img = $('<img>').attr({'src':file});
			loc.append(img);
			$(pos).append(loc);
			$(pos).css('display','block');
		},
	});
}