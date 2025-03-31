<?php
// This file is generated. Do not modify it manually.
return array(
	'location-map' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'create-block/location-map',
		'version' => '0.1.0',
		'title' => 'Location Map',
		'category' => 'widgets',
		'icon' => 'location',
		'description' => 'A block to display a Google Map with a marker at specified coordinates.',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'latitude' => array(
				'type' => 'string',
				'default' => ''
			),
			'longitude' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'textdomain' => 'location-map',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js'
	)
);
