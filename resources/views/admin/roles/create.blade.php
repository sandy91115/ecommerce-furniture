@extends('admin.layouts.app')

@section('title', 'Create Role')

@section('content')
@include('admin.roles.partials.form', [
    'pageTitle' => 'Create New Role',
    'pageDescription' => 'Ek focused role banao aur permissions ko grouped sections me assign karo.',
    'formAction' => route('admin.roles.store'),
    'formMethod' => 'POST',
    'submitLabel' => 'Create Role',
])
@endsection
