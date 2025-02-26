@extends('website::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('website.name') !!}</p>
@endsection
