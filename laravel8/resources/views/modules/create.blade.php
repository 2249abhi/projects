@extends('layouts.app')

@section('content')
<div class="container-fluid dashboard-content">
    <div class="row">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="page-header" id="top">
                        <h2 class="pageheader-title">{{ __('Products') }} </h2>
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
                        <!-- <div class="card-header">
                            <div class="pull-left">
                                Product Detail
                            </div>
                            <div class="pull-right" style="text-align:right;">
                                @can('product-create')
                                <a class="btn btn-success" href="{{ route('products.create') }}"> Create New Product</a>
                                @endcan
                            </div>
                        </div> -->
                        <div class="card-body">
                            @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                            @endif
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <blockquote class="blockquote mb-0">
                                <form action="{{ route('modules.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="form-group">
                                                <strong>Parent Module:</strong>
                                                <?php $select  = ''; ?>
                                                {!! Form::select('pid', $modules,0, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="form-group">
                                                <strong>Name:</strong>
                                                <input type="text" name="name" class="form-control" placeholder="Name">
                                            </div>
                                        </div>
                                        <div class="col-xs-8 col-sm-8 col-md-8 action" style="display:block;">
                                            <div class="form-group">
                                                <strong>Action:</strong>
                                                <input type="text" name="action" class="form-control" placeholder="Action">
                                            </div>
                                        </div>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="form-group">
                                                <strong>Order:</strong>
                                                <input type="number" name="depth" class="form-control" placeholder="Order">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 submodule">
                                            <div class="form-group">
                                                <strong>Create sub Modules</strong>
                                                <br/>
                                                <input type="checkbox" name="submodule[]" value="index"> List <br/>
                                                <input type="checkbox" name="submodule[]" value="create"> Create <br/>
                                                <input type="checkbox" name="submodule[]" value="edit"> Edit <br/>
                                                <input type="checkbox" name="submodule[]" value="destroy"> Delete <br/>
                                            </div>
                                        </div>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="form-group">
                                                <strong>Detail:</strong>
                                                <textarea class="form-control" style="height:150px" name="detail"
                                                    placeholder="Detail"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>


                                </form>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function({

    }));
</script>
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