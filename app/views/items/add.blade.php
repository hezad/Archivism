@extends('layout')

@section('content')
	
	{{ HTML::ul($errors->all()) }}
	
	<ol class="item-add-steps">
		<li class="step-1">
			<div class="center-container vertical-center-please">
				<h2>Paste your link here</h2>
				<input type="text" class="full-input paste-link-input" id="item-link-input" style="margin-bottom: 10%;" />

				<h2>And give it a name</h2>
				<input type="text" class="full-input item-name-input" id="item-name-input" />
			</div>
		</li>
		<li class="step-2">
			<div class="center-container vertical-center-please">
				<h2></h2>

				<ul class="item-add-categories">
					@foreach($itemKinds as $kindSlug => $kindLabel)
						<li class="item-kind item-kind-{{ $kindSlug }} @if($kindSlug == 'default') selected @endif">
							<a href="#" data-kind="{{ $kindSlug }}"><span>{{ $kindLabel }}</span></a>
						</li>
					@endforeach
					<li class="clear-float-helper"></li>
				</ul>

				{{ Form::open(array('url' => 'items'), array('class' => 'add-item-form')) }}

					<input type="hidden" id="item-kind" name="item-kind" value="default" />
					<input type="hidden" id="item-provider" name="item-provider" value="default" />
					<input type="hidden" id="item-link" name="item-href" />
					<input type="hidden" id="item-name" name="item-name" />
					<input type="hidden" id="item-provider_unique_id" name="item-provider_unique_id" />
					
					<input type="submit" value="Save" class="submit-button" />
					
				{{ Form::close() }}
			</div>
		</li>
	</ol>
@stop