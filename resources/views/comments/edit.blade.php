@extends('layouts.app')

@section('content')

<form action="/comments/{{ $comment->id }}" method="POST">

@csrf
@method('PUT')

<textarea name="message">

{{ $comment->message }}

</textarea>

<br><br>

<button type="submit">
Update
</button>

</form>

@endsection