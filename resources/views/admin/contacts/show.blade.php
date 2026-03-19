@extends('admin.layouts.app')

@section('title', 'Enquiry Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Enquiry Details</h1>
            <p class="mt-1 text-sm text-gray-600">From {{ $contact->name }} on {{ $contact->created_at->format('M d, Y H:i') }}</p>
        </div>
        <a href="{{ route('admin.contacts.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 font-medium">
            Back to List
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">Name</h3>
                <p class="text-lg text-gray-900">{{ $contact->name }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">Email</h3>
                <p class="text-lg text-gray-900">
                    <a href="mailto:{{ $contact->email }}" class="text-blue-600 hover:underline">{{ $contact->email }}</a>
                </p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">Phone Number</h3>
                <p class="text-lg text-gray-900">{{ $contact->number ?? 'N/A' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">Status</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $contact->status === 'new' ? 'bg-blue-100 text-blue-800' : ($contact->status === 'read' ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800') }}">
                    {{ ucfirst($contact->status) }}
                </span>
            </div>
        </div>

        <div class="mb-8">
            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">Subject</h3>
            <p class="text-xl font-medium text-gray-900">{{ $contact->subject }}</p>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">Message</h3>
            <div class="bg-gray-50 rounded-lg p-6 text-gray-800 whitespace-pre-wrap leading-relaxed border border-gray-100">
                {{ $contact->message }}
            </div>
        </div>

        <div class="mt-8 pt-8 border-t border-gray-100 flex justify-between items-center">
            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800 font-medium" onclick="return confirm('Delete this enquiry?')">
                    Delete Enquiry
                </button>
            </form>
            <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}" class="inline-flex items-center px-6 py-3 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-medium shadow-sm transition-colors">
                Reply via Email
            </a>
        </div>
    </div>
</div>
@endsection
