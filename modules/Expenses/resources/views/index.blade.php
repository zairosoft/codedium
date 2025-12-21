@extends('expenses::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('expenses.name') !!}</p>
@endsection
