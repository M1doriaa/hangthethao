@extends('layouts.app')

@section('title', 'Test Blade')

@section('content')
<div class="container">
    <h1>Test Blade Rendering</h1>
    <p>Nếu bạn thấy message này render đúng thì Blade đang hoạt động.</p>
    <p>Current time: {{ now() }}</p>
    <p>Random number: {{ rand(1, 100) }}</p>
</div>
@endsection
