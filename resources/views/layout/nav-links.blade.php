<?php
$ltSections = $navLinks ?? trans('LaravelTreats::layout.nav.links');

// Make sure it's a nested array so we can add separators appropriately
if (!isset($ltSections[0])) {
    $ltSections = array($ltSections);
}
?>
    @foreach ($ltSections as $ltLinks)
        @foreach ($ltLinks as $ltHref => $ltLabel)
        <li><a href="{{ $ltHref }}">{{ $ltLabel }}</a></li>
        @endforeach

        @if (!$loop->last)
        <li class="divider"></li>
        @endif
    @endforeach
