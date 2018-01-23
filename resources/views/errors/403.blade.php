@extends('layouts.app')
@section('content')
<section class="top-bar">
	<div class="row">
		<div class="columns medium-12">
			<div class="top-bar-nav">
				<div class="top-bar-heading">
					<h1>Unauthorized</h1>
				</div>
				
			</div>
		</div>
	</div>
</section>
<section class="p-tb40">
	<div class="row">
		<div class="columns medium-6 text-center">
			<img src="/images/error-img.png">
		</div>
		<div class="columns medium-6">
			<p class="error-content">
				403
			</p>
			<p class="page-not-found">You are not authorized to view this page.</p>
			<ul class="medium-block-grid-1">
				<li><a href="{{ url('customer') }}" class="btn btn-purple">Go to home</a></li>
			</ul>
		</div>
	</div>
</section>

@endsection