	<script>
		var jsBeacons = [];
        @if (isset($beacons))
		@foreach ($beacons as $beacon) <?php $beacon['data']['_token'] = csrf_token(); ?>
		jsBeacons.push({
			'url': '{{ $beacon['url'] }}',
			'data': {!! json_encode($beacon['data']) !!},
		});
		@endforeach
        @endif
	</script>
