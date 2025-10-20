@extends('backend.layouts.app')

@section('title', 'View Consent')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="card-title">{{ $module_action }}</h4>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-right">
                            <a href="{{ route('backend.consents.edit', $consent->id) }}" class="btn btn-warning">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('backend.consents.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">ID:</th>
                                <td>{{ $consent->id }}</td>
                            </tr>
                            <tr>
                                <th>Name:</th>
                                <td>{{ $consent->name }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($consent->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Required:</th>
                                <td>
                                    @if($consent->is_required)
                                        <span class="badge badge-warning">Required</span>
                                    @else
                                        <span class="badge badge-secondary">Optional</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Sort Order:</th>
                                <td>{{ $consent->sort_order }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Created At:</th>
                                <td>{{ $consent->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At:</th>
                                <td>{{ $consent->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($consent->description)
                <div class="row mt-3">
                    <div class="col-12">
                        <h5>Description</h5>
                        <div class="card">
                            <div class="card-body">
                                {{ $consent->description }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($consent->content)
                <div class="row mt-3">
                    <div class="col-12">
                        <h5>Content</h5>
                        <div class="card">
                            <div class="card-body">
                                {!! nl2br(e($consent->content)) !!}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
