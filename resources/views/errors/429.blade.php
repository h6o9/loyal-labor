@extends('errors::minimal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', __('You’ve made too many requests in a short time. Please wait and try again.'))
