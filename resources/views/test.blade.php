@extends('layouts.app')

@section('title', 'Test Page')

@push('styles')
<style>
    .test-container {
        padding: 2rem;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="test-container">
    <h1>Test Page</h1>
    <p>Trang này để test xem có lỗi section không.</p>
</div>
@endsection
