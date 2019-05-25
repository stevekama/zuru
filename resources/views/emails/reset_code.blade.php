@extends('emails.master')

@section('content')

    <h1>Reset password code</h1>
    {{$code->code}}

@endsection
