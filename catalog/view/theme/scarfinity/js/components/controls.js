$(function() {
    $('.ctr-utt').on('click', function(e) {
		var name = e.target.name;
		var input = $('.ctr-utt input[type="number"]');

		if(input != null) {
			var value = input.val();

			if(value != null) {
				var intValue = parseInt(value, 10);
				input.val(Math.max(1, Math.min(500, intValue + (name == 'inc' ? 1 : name == 'dec' ? -1 : 0))));
			}
		}
	});
});