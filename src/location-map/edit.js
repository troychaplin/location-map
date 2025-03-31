/**
 * WordPress dependencies
*/
import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { useState, useEffect } from '@wordpress/element';
import { TextControl, Button } from '@wordpress/components';
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
	const [latitude, setLatitude] = useState(attributes.latitude || '');
	const [longitude, setLongitude] = useState(attributes.longitude || '');
	const [mapLoaded, setMapLoaded] = useState(false);

	const blockProps = useBlockProps();

	useEffect(() => {
		// Load Google Maps script
		if (!window.google && !mapLoaded) {
			const script = document.createElement('script');
			script.src = `https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY`;
			script.async = true;
			script.defer = true;
			script.onload = () => setMapLoaded(true);
			document.head.appendChild(script);
		}
	}, [mapLoaded]);

	const handleSubmit = () => {
		setAttributes({ latitude, longitude });
	};

	return (
		<div {...blockProps}>
			{!attributes.latitude ? (
				<div className="location-map-placeholder">
					<TextControl
						label={__('Latitude', 'location-map')}
						value={latitude}
						onChange={(value) => setLatitude(value)}
					/>
					<TextControl
						label={__('Longitude', 'location-map')}
						value={longitude}
						onChange={(value) => setLongitude(value)}
					/>
					<Button
						isPrimary
						onClick={handleSubmit}
					>
						{__('Add Map', 'location-map')}
					</Button>
				</div>
			) : (
				<div 
					id="map" 
					style={{ height: '400px', width: '100%' }}
					ref={(el) => {
						if (el && window.google && !el.dataset.initialized) {
							const map = new window.google.maps.Map(el, {
								center: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
								zoom: 12,
							});
							new window.google.maps.Marker({
								position: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
								map: map,
							});
							el.dataset.initialized = 'true';
						}
					}}
				/>
			)}
		</div>
	);
}
