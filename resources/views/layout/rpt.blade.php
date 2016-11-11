	<script>
		var aJsBeacons = [];
        @if (isset($aBeacons))
		@foreach ($aBeacons as $aBeacon) <?php $aBeacon['data']['_token'] = csrf_token(); ?>
		aJsBeacons.push({
			'url': '{{ $aBeacon['url'] }}',
			'data': {!! json_encode($aBeacon['data']) !!},
		});
		@endforeach
        @endif
	</script>
