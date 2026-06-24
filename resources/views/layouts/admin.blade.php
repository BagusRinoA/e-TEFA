@extends('layouts.app')

@section('content')
    <div @class([
        'admin-page',
        trim($__env->yieldContent('admin-page-class')),
    ])>
        <div class="admin-container">
            <div class="admin-layout">
                @include('admin.partials.sidebar')

                <div class="admin-main">
                    @yield('admin-content')
                </div>
            </div>
        </div>
    </div>
@endsection
