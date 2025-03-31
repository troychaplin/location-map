import { useBlockProps } from '@wordpress/block-editor';

export default function save({ attributes }) {
	const blockProps = useBlockProps.save();

	return (
		<div {...blockProps}>
			<div
				className="location-map-container"
				data-latitude={attributes.latitude}
				data-longitude={attributes.longitude}
			/>
		</div>
	);
}
