document.addEventListener('DOMContentLoaded', function() {
    const mapContainers = document.querySelectorAll('.wp-block-create-block-location-map .location-map-container');
    
    if (!mapContainers.length) return;

    // Load Google Maps API
    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=${locationMapSettings.apiKey}&libraries=places&v=weekly`;
    script.async = true;
    script.defer = true;
    
    script.onload = function() {
        mapContainers.forEach(container => {
            if (!container.dataset.initialized) {
                const lat = parseFloat(container.dataset.latitude);
                const lng = parseFloat(container.dataset.longitude);

                if (isNaN(lat) || isNaN(lng)) {
                    console.error('Invalid coordinates');
                    return;
                }

                try {
                    const map = new google.maps.Map(container, {
                        center: { lat, lng },
                        zoom: 12,
                        mapTypeId: 'roadmap',
                        disableDefaultUI: false,
                        zoomControl: true,
                        streetViewControl: false,
                        mapTypeControl: false,
                        fullscreenControl: false,
                        styles: [
                            {
                                featureType: 'poi',
                                elementType: 'labels',
                                stylers: [{ visibility: 'off' }]
                            }
                        ]
                    });

                    new google.maps.Marker({
                        position: { lat, lng },
                        map: map,
                        title: 'Selected Location'
                    });

                    container.dataset.initialized = 'true';
                } catch (error) {
                    console.error('Error initializing map:', error);
                }
            }
        });
    };

    document.head.appendChild(script);
});
