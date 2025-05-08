<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>UserDetails</title>
</head>
<body>
        @foreach ($data as $key => $value)
        <h2> {{$key}}

        @if(is_array($value))
        
             @foreach ($value as $val)
                 {{$val}} , {{' '}}             
             @endforeach

        @elseif($key == 'avatar')
            <img src = "{{$value}}" alt="#">
        @else
        {{$value}}  </h2>
        @endif
        @endforeach
</body>
</html>