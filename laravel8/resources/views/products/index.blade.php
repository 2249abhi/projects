@extends('layouts.app')

@section('content')
<div class="container-fluid dashboard-content">
    <div class="row">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="page-header" id="top">
                        <h2 class="pageheader-title">{{ __('Products') }} </h2>
                        <p class="pageheader-text">Dashboard Text.</p>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"
                                            class="breadcrumb-link">{{ __('Product') }}</a></li>
                                    <!-- <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">UI Elements</a></li>
                                                <li class="breadcrumb-item active" aria-current="page">Cards</li> -->
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="pull-left">
                                Product Detail
                            </div>
                            <div class="pull-right" style="text-align:right;">
                                @can('product-create')
                                <a class="btn btn-success" href="{{ route('products.create') }}"> Create New Product</a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                            
                            <blockquote class="blockquote mb-0">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Details</th>
                                        <th width="280px">Action</th>
                                    </tr>
                                    @foreach ($products as $product)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->detail }}</td>
                                        <td>
                                            <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                                                <a class="btn btn-info"
                                                    href="{{ route('products.show',$product->id) }}">Show</a>
                                                @can('product-edit')
                                                <a class="btn btn-primary"
                                                    href="{{ route('products.edit',$product->id) }}">Edit</a>
                                                @endcan


                                                @csrf
                                                @method('DELETE')
                                                @can('product-delete')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                @endcan
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>


                                {!! $products->links() !!}
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">{{ __('Dashboard') }}</div>

                            <div class="card-body">
                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                {{ __('You are logged in!') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
@endsection