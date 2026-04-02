@extends('admin.layouts.app')

@section('title', $attribute->name . ' - Values')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ $attribute->name }} Values</h1>
        <p class="text-gray-600 mt-1">{{ $attribute->slug }}</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.attributes.values.create', $attribute) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
            Add New Value
        </a>
        <a href="{{ route('admin.attributes.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
            Back to Attributes
        </a>
    </div>
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                    @if(strtolower($attribute->slug) === 'color' || strtolower($attribute->name) === 'color')
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color Code</th>
                    @endif
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($values as $value)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $value->value }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $value->slug }}</td>
                        @if(strtolower($attribute->slug) === 'color' || strtolower($attribute->name) === 'color')
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($value->color_code)
                                    <div class="flex items-center gap-2">
                                        <span class="inline-block w-4 h-4 rounded-full border border-gray-200" style="background-color: {{ $value->color_code }}"></span>
                                        <span>{{ $value->color_code }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('admin.attributes.values.edit', [$attribute, $value]) }}" class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.attributes.values.destroy', [$attribute, $value]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ (strtolower($attribute->slug) === 'color' || strtolower($attribute->name) === 'color') ? 4 : 3 }}" class="px-6 py-12 text-center text-gray-500">
                            No values found. <a href="{{ route('admin.attributes.values.create', $attribute) }}" class="text-blue-600 hover:text-blue-800 font-medium">Create one now</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4">
        {{ $values->appends(request()->query())->links() }}
    </div>
</div>
@endsection
