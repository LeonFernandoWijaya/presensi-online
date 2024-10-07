@extends('layouts.inactive-layout')

@section('content')
    <section class="bg-white min-h-screen">
        <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
            <div class="mx-auto max-w-screen-sm text-center flex flex-col items-center justify-center">
                <h1 class="mb-4 text-7xl tracking-tight font-extrabold lg:text-9xl text-primary-600">403</h1>
                <p class="mb-4 text-3xl tracking-tight font-bold text-gray-900 md:text-4xl">Account not activated.</p>
                <p class="mb-4 text-lg font-light text-gray-500">Sorry, your account has not been activated by the admin yet.
                    Please wait for your account to be activated. </p>
                <a href="{{ url('presence') }}" class="font-medium text-blue-600 hover:underline">Refresh</a>
            </div>
        </div>
    </section>
@endsection
