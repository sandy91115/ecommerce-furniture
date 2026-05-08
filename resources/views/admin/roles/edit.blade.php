@extends('admin.layouts.app')

@section('title', 'Edit Role')

@section('content')
@include('admin.roles.partials.form', [
    'pageTitle' => 'Manage Role',
    'pageDescription' => 'Role details aur permissions ko ek dedicated workflow me update karo.',
    'formAction' => route('admin.roles.update', $role),
    'formMethod' => 'PUT',
    'submitLabel' => $isLockedRole ? 'Locked' : 'Save Changes',
])
@endsection
