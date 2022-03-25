<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
<h1>Dear Developers</h1>

<p>Your application {{ $report->package_name }} (version {{ $report->app_version_name }}
    ) {{ $report->is_silent ? 'has encountered an error on' : 'crashed at' }}
    {{ $report->created_at }}, with exception {{ $report->exception }}</p>
<p>Crash occurs on {{ $report->brand . ' ' . $report->phone_model }} device with android version
    {{ $report->android_version }}</p>
<p><a href="{{ url('report/' . $report->report_id) }}">Open</a> in Acra Web Admin for more details</p>
<p>Please check immediately and fix the problem</p>

<br>
<br>

<p>Thanks</p>
<br>
<p>Crash Report System</p>

</body>

</html>
