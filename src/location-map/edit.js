/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { useState, useEffect } from '@wordpress/element';
import { TextControl, Button, Notice } from '@wordpress/components';
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
	const [latitude, setLatitude] = useState(attributes.latitude || '');
	const [longitude, setLongitude] = useState(attributes.longitude || '');
	const [mapLoaded, setMapLoaded] = useState(false);
	const [apiKey, setApiKey] = useState('');

	const blockProps = useBlockProps();

	useEffect(() => {
		// Get API key from WordPress settings
		wp.apiFetch({ path: '/wp/v2/location-map/settings' }).then(response => {
			setApiKey(response.google_maps_api_key);
		});
	}, []);

	useEffect(() => {
		if (!window.google && !mapLoaded && apiKey) {
			const script = document.createElement('script');
			script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places`;
			script.async = true;
			script.defer = true;
			script.onload = () => setMapLoaded(true);
			document.head.appendChild(script);
		} else if (window.google) {
			setMapLoaded(true);
		}
	}, [mapLoaded, apiKey]);

	const handleSubmit = () => {
		setAttributes({ latitude, longitude });
	};

	if (!apiKey) {
		return (
			<div {...blockProps}>
				<Notice status="warning">
					{__(
						'Please configure your Google Maps API key in the Location Map settings.',
						'location-map'
					)}
				</Notice>
			</div>
		);
	}

	return (
		<div {...blockProps}>
			{!attributes.latitude ? (
				<div className="location-map-placeholder">
					<TextControl
						label={__('Latitude', 'location-map')}
						value={latitude}
						onChange={value => setLatitude(value)}
					/>
					<TextControl
						label={__('Longitude', 'location-map')}
						value={longitude}
						onChange={value => setLongitude(value)}
					/>
					<Button variant="primary" onClick={handleSubmit}>
						{__('Add Map', 'location-map')}
					</Button>
				</div>
			) : (
				<div
					id="map"
					style={{ height: '400px', width: '100%' }}
					ref={el => {
						if (el && window.google && !el.dataset.initialized) {
							setTimeout(() => {
								if (window.google && window.google.maps) {
									const map = new window.google.maps.Map(el, {
										center: {
											lat: parseFloat(latitude),
											lng: parseFloat(longitude),
										},
										zoom: 12,
									});
									new window.google.maps.Marker({
										position: {
											lat: parseFloat(latitude),
											lng: parseFloat(longitude),
										},
										map,
									});
									el.dataset.initialized = 'true';
								}
							}, 500); // Delay of 500ms to ensure API is ready
						}
					}}
				/>
			)}
		</div>
	);
}
