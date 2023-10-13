@extends('layouts.app')

@section('content')
<style>

   span.relative svg {
    width:20px;
   }
</style>
<div class="container-fluid dashboard-content">
    <div class="row">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="page-header" id="top">
                        <h2 class="pageheader-title">{{ __('Modules') }} </h2>
                        <p class="pageheader-text">Dashboard Text.</p>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"
                                            class="breadcrumb-link">{{ __('Module') }}</a></li>
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
                                Module Detail
                            </div>
                            <div class="pull-right" style="text-align:right;">
                                @can('module-create')
                                <a class="btn btn-success" href="{{ route('modules.create') }}"> Create New Module</a>
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
                                        <?php if(request()->query('pid')){ ?>
                                            <th>Route</th>
                                        <?php }else { ?>
                                            <th>Sub Module</th>
                                        <?php } ?>
                                        <th width="280px">Action</th>
                                        
                                    </tr>
                                    @foreach ($modules as $module)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $module->name }}</td>
                                        <?php if(request()->query('pid')){ ?>
                                            <td>{{ $module->action }}</td>
                                        <?php }else { ?>
                                        <td><a class="btn btn-info" href="{{ route('modules.index',['pid'=>$module->id]) }}">Sub Module</a></td>
                                        <?php } ?>
                                        <td>
                                            <form action="{{ route('modules.destroy',$module->id) }}" method="POST">
                                                <a class="btn btn-info"
                                                    href="{{ route('modules.show',$module->id) }}">Show</a>
                                                @can('module-edit')
                                                <a class="btn btn-primary"
                                                    href="{{ route('modules.edit',$module->id) }}">Edit</a>
                                                @endcan


                                                @csrf
                                                @method('DELETE')
                                                @can('module-delete')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                @endcan
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                                <br/>
                                {!! $modules->withQueryString()->links() !!}
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