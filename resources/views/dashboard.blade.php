<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 text-center">
                    <b>{{ __("Welcome to our site, You're successfully logged in!!") }}</b>
                </div>

            </div>
            <br>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <b><u>{{ __("About Site :") }}</u></b>
                </div>

                <div class="p-6 text-gray-900">
                    {{ __("Our site is to convert Url to Short Url") }}
                </div>

                <div class="p-6 text-gray-900">
                    {{ __("To convert your click on Url on Navigation bar") }}
                </div>

            </div>

        </div>
    </div>
    <div>
    </div>
</x-app-layout>
