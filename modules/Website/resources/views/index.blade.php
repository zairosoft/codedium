@extends('layouts.layout')
@section('title', 'เว็บไซต์')
@section('style')
@endsection
@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('website.name') !!}</p>
@endsection
