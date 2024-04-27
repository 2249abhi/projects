@extends('layouts.app')

@section('content')
<div class="container-fluid dashboard-content">
    <div class="row">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="page-header" id="top">
                        <h2 class="pageheader-title">{{ __('Permissions') }} </h2>
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
    <?php
    $role = $_GET['role'] ?? '';
    $module = $_GET['module'] ?? '';
    
    ?>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="pull-left">
                                Permission Detail
                            </div>
                            <div>
                                <form action="{{ route('rolepermissions.index') }}" method="GET">
                                    <div class="row">
                                        <div class="col-xs-4 col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <strong>Role:</strong>
                                                {!! Form::select('role', [''=>'Select Role']+$roles, $role, array('class' => 'form-control','required'=>true)) !!}
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <strong>Module:</strong>
                                                {!! Form::select('module', [''=>'Select Module']+$modules,$module, array('class' => 'form-control','required'=>true)) !!}
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <button style="margin-top:18px;" type="submit" class="btn btn-primary">Submit</button>      
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!--------------->
                            </div>
                        </div>
                        <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                            <?php $i = 0; ?>
                            <blockquote class="blockquote mb-0">
                                
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Permission Name</th>
                                        <th>Allow permission</th>
                                        <th>Don't Show in Menu</th>
                                    </tr>
                                    <tbody>
                                    @if(!empty($sub_modules))
                                    {!! Form::model(['method' => 'POST','route' => ['rolepermissions.update']]) !!}
                                    @foreach ($sub_modules as $id => $sub_module)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $sub_module }}</td>
                                            <td>{{ Form::checkbox('rolepermission[]', $id, in_array($id, $role_permissions) ? true : false,array('class' => 'form-control')) }}</td>
                                            <td>{{ Form::checkbox('menu['.$id.']','', (in_array($id, $role_permissions) && $menu[$id] == 1) ? true : false,array('class' => 'form-control')) }}</td>
                                        </tr>
                                    @endforeach
                                        <tr text-align="center">
                                            <input type="hidden" name="role_id" value="{{ $_GET['role'] ?? '' }}">
                                            <input type="hidden" name="module_id" value="{{ $_GET['module'] ?? '' }}">
                                            <td colspan="3"><button type="submit" class="btn btn-primary">Submit</button></td>
                                        </tr>
                                    {!! Form::close() !!}
                                    @endif
                                    </tbody>
                                </table>
                                
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