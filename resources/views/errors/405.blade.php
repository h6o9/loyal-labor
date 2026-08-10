@extends('errors::minimal')

@section('title', __('Method Not Allowed'))
@section('code', '405')
@section('message', __('The request method is not supported for this resource.'))
