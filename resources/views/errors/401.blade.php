@extends('errors::minimal')

@section('title', __('Unauthorized Access'))
@section('code', '401')
@section('message', __('You’re not allowed to view this page. Please log in or contact support.'))
