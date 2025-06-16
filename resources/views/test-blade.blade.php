@extends('layouts.app')

@section('title', 'Test Page')

@section('content')
<div class="container">
    <h1>Test Page</h1>
    <p>This is a test to see if Blade is working properly.</p>
    <p>Current time: {{ now() }}</p>
    <p>User: {{ auth()->check() ? auth()->user()->name : 'Guest' }}</p>
</div>
@endsection
