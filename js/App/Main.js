var channelsList = new Vue({
	el: '#channelsList',
	data: {
		activeChannel: null,
		channels: null
	},
	methods: {
		changeItem(event) {
			var self = this;
			// this.activeChannel = this.channels[event.target.value];
			$.ajax({
				url: 'http://manch.pw/dappsociety/introparser/a/parser/',
				type: 'POST',
				dataType: 'json',
				async: false,
				data: { archive_name: 'channelsHistory', channel: this.channels[event.target.value].id },
				success: function (res) {
					if(res.ok) {
						self.activeChannel = self.channels[event.target.value];
						self.activeChannel.last_message = res.messages[0];
					}
				}
			});
		}
	},
	mounted: function () {
		var self = this;
		$.ajax({
			url: 'http://manch.pw/dappsociety/introparser/a/parser/',
			type: 'POST',
			dataType: 'json',
			data: { archive_name: 'channelsList' },
			success: function (res) {
				console.log(res);
				if (res.ok) {
					self.channels = res.channels;
				}
			}
		});
	}
});
function updateAnArchive() {
	$.ajax({
		url: 'http://manch.pw/dappsociety/introparser/a/parser/',
		type: 'POST',
		dataType: 'json',
		async: false,
		data: { archive_name: 'channelsHistory', channel: channelsList.activeChannel.id, update: true },
		success: function (res) {
			if (res.ok) {
				//
			}
		}
	});
}