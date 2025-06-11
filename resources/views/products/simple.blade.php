@extends('layouts.app')

@section('title', 'Sản phẩm - Hang The Thao')

@section('content')
<div class="container mt-5">
    <h1>{{ $product->name }}</h1>
    <p>Giá: {{ $product->formatted_price }}</p>
    <p>Mô tả: {{ $product->description }}</p>
</div>
@endsection
