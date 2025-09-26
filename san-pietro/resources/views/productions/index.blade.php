@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">{{ __('Productions') }}</h2>
                    <a href="{{ route('productions.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        {{ __('New Production') }}
                    </a>
                </div>

                <!-- Filters -->
                <div class="mb-6 bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                    <form action="{{ route('productions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <!-- Date Range -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Start Date') }}</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:border-gray-500">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('End Date') }}</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:border-gray-500">
                        </div>

                        <!-- Production Type -->
                        <div>
                            <label for="production_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Type') }}</label>
                            <select name="production_type" id="production_type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:border-gray-500">
                                <option value="">{{ __('All Types') }}</option>
                                <option value="internal_reimmersion" {{ request('production_type') == 'internal_reimmersion' ? 'selected' : '' }}>
                                    {{ __('Internal Reimmersion') }}
                                </option>
                                <option value="resale_reimmersion" {{ request('production_type') == 'resale_reimmersion' ? 'selected' : '' }}>
                                    {{ __('Resale Reimmersion') }}
                                </option>
                                <option value="consumption" {{ request('production_type') == 'consumption' ? 'selected' : '' }}>
                                    {{ __('Consumption') }}
                                </option>
                            </select>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Category') }}</label>
                            <select name="category" id="category"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:border-gray-500">
                                <option value="">{{ __('All Categories') }}</option>
                                <option value="micro" {{ request('category') == 'micro' ? 'selected' : '' }}>{{ __('Micro') }}</option>
                                <option value="small" {{ request('category') == 'small' ? 'selected' : '' }}>{{ __('Small') }}</option>
                                <option value="medium" {{ request('category') == 'medium' ? 'selected' : '' }}>{{ __('Medium') }}</option>
                                <option value="large" {{ request('category') == 'large' ? 'selected' : '' }}>{{ __('Large') }}</option>
                                <option value="super" {{ request('category') == 'super' ? 'selected' : '' }}>{{ __('Super') }}</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:border-gray-500">
                                <option value="">{{ __('All Statuses') }}</option>
                                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>{{ __('Available') }}</option>
                                <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>{{ __('Sold') }}</option>
                                <option value="reimmersed" {{ request('status') == 'reimmersed' ? 'selected' : '' }}>{{ __('Reimmersed') }}</option>
                            </select>
                        </div>

                        @if(auth()->user()->hasRole('SUPER_ADMIN'))
                        <!-- Company Filter (only for SUPER_ADMIN) -->
                        <div>
                            <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Company') }}</label>
                            <select name="company_id" id="company_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:border-gray-500">
                                <option value="">{{ __('All Companies') }}</option>
                                @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Filter Button -->
                        <div class="md:col-span-3 lg:col-span-4 flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 rounded hover:bg-gray-700 dark:hover:bg-gray-300">
                                {{ __('Apply Filters') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Productions Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Date') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Type/Category') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Member') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Quantity (kg)') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Total') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Status') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                            @forelse($productions as $production)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $production->production_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm">{{ __(ucfirst(str_replace('_', ' ', $production->production_type))) }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __(ucfirst($production->category)) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm">{{ $production->member->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $production->production_zone->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ number_format($production->quantity_kg, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    € {{ number_format($production->total, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($production->status === 'available') bg-green-100 text-green-800 
                                        @elseif($production->status === 'sold') bg-blue-100 text-blue-800 
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ __(ucfirst($production->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('productions.show', $production) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        {{ __('View') }}
                                    </a>
                                    <a href="{{ route('productions.edit', $production) }}" class="text-green-600 hover:text-green-900 mr-3">
                                        {{ __('Edit') }}
                                    </a>
                                    @if($production->status === 'available')
                                    <form action="{{ route('productions.destroy', $production) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('@lang('Are you sure you want to delete this production?')')">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('No productions found.') }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $productions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection