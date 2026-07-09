🚨 <b>SMS FAILED</b>

<b>Event:</b> {{ $event_type }}

<b>Owner (SIM):</b> {{ $owner_phone ?? 'N/A' }}
<b>Target:</b> {{ $target_phone ?? 'N/A' }}

<b>Message ID:</b> {{ $message_id ?? 'N/A' }}
<b>Request ID:</b> {{ $request_id ?? 'N/A' }}
<b>SIM:</b> {{ $sim ?? 'N/A' }}

<b>Content:</b>
{{ $content ?? 'N/A' }}

<b>Error:</b>
{{ $error_message ?? 'Unknown error' }}

<b>Time:</b> {{ $timestamp ?? now() }}
