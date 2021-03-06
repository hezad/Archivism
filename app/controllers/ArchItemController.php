<?php

class ArchItemController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$items = ArchItem::listing()->get();
		return View::make('items.index', array('items' => $items) );
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('items.add', array('itemKinds' => ArchItemKind::$kinds));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$rules = array(
			'item-kind'     => 'required',
			'item-provider' => 'required',
			'item-name'     => 'required',
			'item-provider_unique_id' => 'required',
			'item-href'     => 'required|url'
		);
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::to('items/create')
			    ->withErrors($validator)
			    ->withInput(Input::all());
		} else {

			// store
			$item = new ArchItem;
			$item->kind     = Input::get('item-kind');
			$item->provider = Input::get('item-provider');
			$item->provider_unique_id = Input::get('item-provider_unique_id');
			$item->href     = Input::get('item-href');
			$item->name     = Input::get('item-name');
			$item->save();

			// redirect
			Session::flash('message', 'Ok, <em>'.htmlentities($item->name).'</em> has been saved successfully.');
			return Redirect::to('items/index');
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


	/**
	 * AJAX: Check provider type of the content
	 *
	 * @POST Expected data
	 * 	+ url 	 => String
	 *  + _token => CSRF Protection Token.
	 *
	 * TODO: Put _token verification in a pre-filter
	 * 
	 * @return JSON Response
	 */
	public function guessProviderAndKind()
	{
		if ( Session::token() !== Input::get( '_token' ) ) {
            return Response::json( array(
            	'status' => 'error',
                'msg'    => 'Unauthorized'
            ) );
        }

        $url = Input::get('url');

		$itemProvider = new ArchProvider($url);
		$itemKind = ArchItemKind::getDefaultKindFromProvider($itemProvider->slug);

		if( $itemKind != 'default' ) {
			$providerGuessingMessage = 'It looks like your item comes from <i>'.$itemProvider->name.'</i>.';
		} else {
			$providerGuessingMessage = 'Hum ... I don\'t know this website. But don\'t worry, I\'ll save it anyway.';
		}

		$providerGuessingMessage .= '<br />What kind of stuff is it?';

		return Response::json( array(
        	'status' 	=> 'ok',
            'provider'	=> $itemProvider->slug,
            'kind'	 	=> $itemKind,
            'kindGuessMessage' => $providerGuessingMessage,
            'url' 		=> $url,
            'nameSuggestion' => $itemProvider->provider_name_suggestion,
            'itemUniqueId'  => $itemProvider->unique_id
        ) );
	}
}
