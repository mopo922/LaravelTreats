<?php /*

!!!!! DO NOT USE THIS IN PRODUCTION !!!!!

This is open-source software and NOT legal advice. The following page is just
a placeholder. Did we mention - DO NOT USE THIS!!!!!

*/ ?>
@extends('layout.master')

@section('content')
    <h2>{{ $domain }} Privacy Policy</h2>

    <p>
        This privacy policy discloses the privacy practices for {{ $domain }},
        henceforth referred to as {{ $siteName }}. This privacy policy applies solely to information
        collected by this web site. It will notify you of the following:
    </p>
    <ul>
        <li>What personally identifiable information is collected from you through {{ $siteName }}, how it is used and with whom it may be shared.</li>
        <li>What choices are available to you regarding the use of your data.</li>
        <li>The security procedures in place to protect the misuse of your information.</li>
        <li>How you can correct any inaccuracies in the information.</li>
    </ul>

    <h3>Information Collection, Use, and Sharing</h3>
    <p>
        {{ $siteName }} is the sole owner of the information collected on {{ $domain }}.
        {{ $siteName }} only has access to / collects information that you voluntarily
        give via {{ $domain }} and/or email or phone communication.
        {{ $siteName }} will not sell or rent this information to anyone, ever.
    </p>
    <p>
        {{ $siteName }} will use your information to respond to you, regarding the reason you contacted us.
        {{ $siteName }} will not share your information with any third party outside of our organization,
        other than as necessary to fulfill your request.
    </p>
    <p>
        {{ $siteName }} may contact you via email in the future to inform you of new products or services, and changes to this Privacy Policy.
    </p>

    <h3>Your Access to and Control Over Information</h3>
    <p>
        You may opt out of any future contacts from {{ $siteName }} at any time.
        You can do the following at any time by contacting {{ $siteName }} via the
        email address or phone number given on
        <a href="//{{ $domain }}" target="_blank">{{ $domain }}</a>:
    </p>
    <ul>
        <li>Have us delete any data we have about you.</li>
        <li>Express any concern you have about our use of your data.</li>
    </ul>

    <h3>Security</h3>
    <p>
        {{ $siteName }} takes precautions to protect your information.
        When you submit sensitive information via the website, your information is protected both online and offline.
    </p>
    <p>
        Wherever we collect sensitive information (such as credit card data),
        that information is encrypted and transmitted to us in a secure way.
        You can verify this by looking for a closed lock icon at the bottom of your web browser,
        or looking for "https" at the beginning of the address of the web page.
    </p>
    <p>
        While we use encryption to protect sensitive information transmitted online,
        we also protect your information offline. Only employees who need the information
        to perform a specific job (for example, billing or customer service) are granted
        access to personally identifiable information. The computers/servers on which
        we store personally identifiable information are kept in a secure environment.
    </p>

    <h3>Registration</h3>
    <p>
        In order to use this website, a user must first complete the registration process.
        During registration a user is required to give certain information (such as name and email address).
        This information is used to contact you about the products/services on our site in which you have expressed interest.
    </p>

    <h3>Cookies</h3>
    <p>
        {{ $domain }} uses "cookies" to persist log-in sessions. A cookie is a
        piece of data stored on your device that tells {{ $siteName }} that you've
        been here before, thus eliminating the need to log in every time you
        use the site. Cookies can also enable us to track and target the interests
        of our users to enhance the experience on {{ $domain }}. Usage of a cookie
        is in no way linked to any personally identifiable information on our site.
    </p>

    <h3>Third-parties</h3>
    <p>
        {{ $siteName }} uses a third-party credit card processing service for online
        donations and subscriptions. Your credit card and PayPal information
        is sent directly to these experts and never enters any {{ $siteName }} servers.
        The third-party vendor stores names and email addresses for the sole
        purpose of processing credit card payments.
    </p>

    <h3>Links</h3>
    <p>
        {{ $domain }} contains links to other sites. Please be aware that {{ $siteName }} is
        not responsible for the content or privacy practices of such other sites.
        We encourage our users to be aware when they leave our site and to read
        the privacy statements of any other site that collects personally identifiable information.
    </p>

    <h3>Updates</h3>
    <p>
        The {{ $siteName }} Privacy Policy may change from time to time without notice and all updates will be posted here.
    </p>
    <p><strong>
        If you feel that we are not abiding by this privacy policy, you should
        <a href="//{{ $domain }}/contact" target="_blank">contact us immediately</a>.
    </strong></p>
    <br>
@endsection
