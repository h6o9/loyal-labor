@extends('errors::minimal')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', __('We’re doing some maintenance or the server is overloaded. Please try again later.'))
