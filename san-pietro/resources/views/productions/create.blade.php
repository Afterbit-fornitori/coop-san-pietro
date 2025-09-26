@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold">{{ __('New Production') }}</h2>
                </div>

                <form action="{{ route('productions.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if(auth()->user()->is_super_admin)
                        <!-- Company Selection for Super Admin -->
                        <div>
                            <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Company') }}
                            </label>
                            <select name="company_id" id="company_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                required>
                                <option value="">{{ __('Select Company') }}</option>
                                @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('company_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif
                        <!-- Production Date -->
                        <div>
                            <label for="production_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Production Date') }}
                            </label>
                            <input type="date" name="production_date" id="production_date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                value="{{ old('production_date', date('Y-m-d')) }}" required>
                            @error('production_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Production Zone -->
                        <div>
                            <label for="production_zone_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Production Zone') }}
                            </label>
                            <select name="production_zone_id" id="production_zone_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                required>
                                <option value="">{{ __('Select Production Zone') }}</option>
                                @foreach($productionZones as $zone)
                                <option value="{{ $zone->id }}" {{ old('production_zone_id') == $zone->id ? 'selected' : '' }}>
                                    {{ $zone->name }} ({{ $zone->code }})
                                </option>
                                @endforeach
                            </select>
                            @error('production_zone_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Member -->
                        <div>
                            <label for="member_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Member') }}
                            </label>
                            <select name="member_id" id="member_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                required>
                                <option value="">{{ __('Select Member') }}</option>
                                @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('member_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Product -->
                        <div>
                            <label for="product_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Product') }}
                            </label>
                            <select name="product_id" id="product_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                required>
                                <option value="">{{ __('Select Product') }}</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                    data-base-price="{{ $product->base_price }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('product_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Production Type -->
                        <div>
                            <label for="production_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Production Type') }}
                            </label>
                            <select name="production_type" id="production_type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                required>
                                <option value="">{{ __('Select Type') }}</option>
                                <option value="internal_reimmersion" {{ old('production_type') == 'internal_reimmersion' ? 'selected' : '' }}>
                                    {{ __('Internal Reimmersion') }}
                                </option>
                                <option value="resale_reimmersion" {{ old('production_type') == 'resale_reimmersion' ? 'selected' : '' }}>
                                    {{ __('Resale Reimmersion') }}
                                </option>
                                <option value="consumption" {{ old('production_type') == 'consumption' ? 'selected' : '' }}>
                                    {{ __('Consumption') }}
                                </option>
                            </select>
                            @error('production_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Category') }}
                            </label>
                            <select name="category" id="category"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                required>
                                <option value="">{{ __('Select Category') }}</option>
                                <option value="micro" {{ old('category') == 'micro' ? 'selected' : '' }}>{{ __('Micro') }}</option>
                                <option value="small" {{ old('category') == 'small' ? 'selected' : '' }}>{{ __('Small') }}</option>
                                <option value="medium" {{ old('category') == 'medium' ? 'selected' : '' }}>{{ __('Medium') }}</option>
                                <option value="large" {{ old('category') == 'large' ? 'selected' : '' }}>{{ __('Large') }}</option>
                                <option value="super" {{ old('category') == 'super' ? 'selected' : '' }}>{{ __('Super') }}</option>
                            </select>
                            @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label for="quantity_kg" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Quantity (kg)') }}
                            </label>
                            <input type="number" step="0.01" min="0" name="quantity_kg" id="quantity_kg"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                value="{{ old('quantity_kg') }}" required>
                            @error('quantity_kg')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Unit Price -->
                        <div>
                            <label for="unit_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Unit Price') }}
                            </label>
                            <input type="number" step="0.01" min="0" name="unit_price" id="unit_price"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                value="{{ old('unit_price') }}" required>
                            @error('unit_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Total (calculated automatically) -->
                        <div>
                            <label for="total" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Total') }}
                            </label>
                            <input type="number" step="0.01" id="total" name="total"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 bg-gray-100"
                                value="{{ old('total') }}" readonly>
                            @error('total')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Notes') }}
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">{{ old('notes') }}</textarea>
                            @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('productions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition dark:border-gray-600 dark:text-gray-300 dark:hover:text-gray-100">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                            {{ __('Create Production') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Company change handler for super admin
        const companySelect = document.getElementById('company_id');
        if (companySelect) {
            companySelect.addEventListener('change', function() {
                const companyId = this.value;
                if (companyId) {
                    // Fetch production zones for selected company
                    fetch(`/api/companies/${companyId}/production-zones`)
                        .then(response => response.json())
                        .then(data => {
                            const zoneSelect = document.getElementById('production_zone_id');
                            zoneSelect.innerHTML = '<option value="">{{ __("Select Production Zone") }}</option>';
                            data.forEach(zone => {
                                zoneSelect.innerHTML += `<option value="${zone.id}">${zone.name} (${zone.code})</option>`;
                            });
                        });

                    // Fetch members for selected company
                    fetch(`/api/companies/${companyId}/members`)
                        .then(response => response.json())
                        .then(data => {
                            const memberSelect = document.getElementById('member_id');
                            memberSelect.innerHTML = '<option value="">{{ __("Select Member") }}</option>';
                            data.forEach(member => {
                                memberSelect.innerHTML += `<option value="${member.id}">${member.name}</option>`;
                            });
                        });
                }
            });
        }

        // Calculate total when quantity or unit price changes
        const quantityInput = document.getElementById('quantity_kg');
        const unitPriceInput = document.getElementById('unit_price');
        const totalInput = document.getElementById('total');
        const productSelect = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity_kg');
        const unitPriceInput = document.getElementById('unit_price');
        const totalInput = document.getElementById('total');
        const productSelect = document.getElementById('product_id');

        function calculateTotal() {
            const quantity = parseFloat(quantityInput.value) || 0;
            const unitPrice = parseFloat(unitPriceInput.value) || 0;
            totalInput.value = (quantity * unitPrice).toFixed(2);
        }

        quantityInput.addEventListener('input', calculateTotal);
        unitPriceInput.addEventListener('input', calculateTotal);

        // Set base price when product is selected
        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption) {
                const basePrice = selectedOption.dataset.basePrice;
                unitPriceInput.value = basePrice;
                calculateTotal();
            }
        });
    });
</script>
@endpush

@endsection