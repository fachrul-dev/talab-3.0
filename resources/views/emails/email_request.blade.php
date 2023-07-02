<!DOCTYPE html>
<html>
<head>
    <title>Talab 3.0</title>
</head>
<body>
    <h3>{{ $data['name'] }}</h3>
    <h4>{{ $data['body'] }}</h4>
    <p>Nama Pengirim : {{ Auth::user()->name }}</p>
    <p>Judul : {{$data['DataRequest']['title']}}</p>
    <p>Requirements : {{$data['DataRequest']['requirements']}}</p>
    <p>Type : {{$data['DataRequest']['type']}}</p>
    @if(isset($data['ButtonStatus']))
        <a href="{{url($data['ButtonStatus']['Approve'])}}">Approve</a>
        <a href="{{url($data['ButtonStatus']['Reject'])}}">Reject</a>
    @endif
    <p>Terimakasih</p>
</body>
</html>
