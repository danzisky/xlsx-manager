<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('dashboard') }}" class="">
                <button class="w3-button w3-grey w3-margin-top">
                    DASHBOARD
                </button>
            </a>
        </div>
        
        <br>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div>
                <div class="bg-green overflow-hidden shadow-sm sm:rounded-lg w3-medium">
                    To use the provided API to import your spreadsheet documents, send a request to <span class="w3-gray w3-text-white w3-padding" style="padding: 2px;margin-right:4px;margin-left:4px">{{ route('import.index') }}</span>
                    with the following parameters
                    <br>
                    <p class="w3-medium w3-container w3-white w3-topbar w3-border-grey w3-margin">
                        <div>
                            Required Parameters:
                        </div>
                        <ul class="w3-ul">
                            <li>
                                <span class="w3-gray w3-text-white" style="padding: 2px;margin-right:4px">spreadsheet </span> This should contain your spreadsheet file in .xlsx, .xls, .csv
                            </li>
                            <li>
                                <span class="w3-gray w3-text-white" style="padding: 2px;margin-right:4px">email </span> This should be your account email used for signup
                            </li>
                        </ul>
                    </p>
                    <br>
                    <p class="w3-medium w3-container w3-white w3-topbar w3-border-grey w3-margin">
                        <div>
                            Optional Parameters:
                        </div>
                        <ul class="w3-ul">
                            <li>
                               <span class="w3-gray w3-text-white" style="padding: 2px;margin-right:4px">has_header </span> Default value is false. If the first row of your spreadsheet contains headers for the columns, set this to true. If it is not set to true, a makeshift sequential alphabetic header is made for your file.
                            </li>
                        </ul>
                    </p>
                </div>
</div>
        </div>
    </div>
</x-app-layout>
