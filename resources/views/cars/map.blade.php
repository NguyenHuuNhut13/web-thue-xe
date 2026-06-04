@extends('layouts.app')

@section('title', 'Bản đồ xe cho thuê tự lái - NKS')

@section('styles')
    <!-- Maplibre GL JS Styles -->
    <link href="https://unpkg.com/maplibre-gl@4.0.0/dist/maplibre-gl.css" rel="stylesheet" />
    <style>
        /* Custom price tag style for markers */
        .price-marker {
            background-color: white;
            color: #0077bb;
            font-weight: 800;
            font-size: 13px;
            padding: 5px 10px;
            border-radius: 9999px; /* Capsule shape */
            border: 2px solid #0077bb;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            width: max-content;
        }
        .price-marker:hover {
            transform: scale(1.08);
            z-index: 999;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.18);
        }
        
        /* Maplibre Popup customization */
        .maplibregl-popup-content {
            border-radius: 20px !important;
            padding: 0 !important;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15) !important;
            border: 1px solid #f1f5f9;
            width: 240px;
        }
        .maplibregl-popup-close-button {
            background-color: rgba(0,0,0,0.5) !important;
            color: white !important;
            border-radius: 50% !important;
            width: 24px !important;
            height: 24px !important;
            top: 8px !important;
            right: 8px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            font-size: 14px !important;
            line-height: 1 !important;
        }
        
        /* Cluster marker style */
        .cluster-marker {
            background-color: #0077bb;
            color: white;
            font-weight: 800;
            font-size: 14px;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 0 0 6px rgba(0, 119, 187, 0.25), 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .cluster-marker:hover {
            transform: scale(1.1);
            background-color: #005a91;
            box-shadow: 0 0 0 8px rgba(0, 90, 145, 0.3), 0 6px 16px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection

@section('content')
    <div class="flex h-[calc(100vh-80px)] overflow-hidden relative">
        
        <!-- Left Panel: Cars List -->
        <div class="w-full md:w-[420px] bg-white border-r border-slate-100 shadow-xl flex flex-col z-20 h-full">
            
            <!-- List Header -->
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-black text-slate-900 flex items-center">
                        <i class="fa-solid fa-map-location-dot text-brand mr-2"></i> Bản đồ tìm xe
                    </h1>
                    <span class="text-xs font-bold bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full">
                        {{ $cars->count() }} xe hoạt động
                    </span>
                </div>
                
                <!-- Quick Filter indicator -->
                <p class="text-xs text-slate-400 font-medium">
                    Nhấp vào bong bóng giá tiền trên bản đồ để xem ảnh xe và thông tin chi tiết.
                </p>
            </div>

            <!-- Scrollable list of cars -->
            <div class="flex-grow overflow-y-auto divide-y divide-slate-100 p-4 space-y-4">
                @forelse($cars as $car)
                    <div class="bg-slate-50 hover:bg-slate-100/60 p-4 rounded-2xl border border-slate-100 hover:border-brand/20 transition-all duration-300 cursor-pointer flex gap-4"
                         onclick="focusCar({{ $car->longitude }}, {{ $car->latitude }}, {{ $car->id }})">
                        <!-- Car Image -->
                        <div class="w-24 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-slate-200">
                            <img src="{{ $car->thumbnail_url }}" 
                                 alt="{{ $car->title }}" class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Content -->
                        <div class="flex flex-col justify-between flex-grow">
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">{{ $car->brand }}</span>
                                <h3 class="text-sm font-bold text-slate-800 line-clamp-1 -mt-0.5">{{ $car->title }}</h3>
                                <p class="text-[10px] text-slate-500 mt-1 line-clamp-1">
                                    <i class="fa-solid fa-location-dot text-brand mr-1"></i> {{ $car->location }}
                                </p>
                            </div>
                            
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs font-black text-brand">
                                    {{ number_format($car->price_per_day, 0, ',', '.') }}đ<span class="text-[9px] text-slate-400 font-normal">/ngày</span>
                                </span>
                                <span class="text-[10px] bg-white border border-slate-200 text-slate-600 px-2 py-0.5 rounded font-bold uppercase tracking-wider">
                                    {{ $car->transmission === 'automatic' ? 'Tự động' : 'Số sàn' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400 font-medium">
                        Không có xe nào đang hiển thị.
                    </div>
                @endforelse
            </div>
            
            <!-- Bottom stats -->
            <div class="p-4 border-t border-slate-100 bg-slate-50 text-center">
                <a href="{{ route('cars.index') }}" class="text-xs font-bold text-brand hover:underline">
                    Chuyển sang dạng Danh sách <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Right Panel: Full Screen Map -->
        <div id="map" class="flex-grow h-full w-full z-10"></div>
    </div>
@endsection

@section('scripts')
    <!-- Maplibre GL JS Library -->
    <script src="https://unpkg.com/maplibre-gl@4.0.0/dist/maplibre-gl.js"></script>
    <!-- Supercluster for client-side HTML marker clustering -->
    <script src="https://unpkg.com/supercluster@8.0.1/dist/supercluster.min.js"></script>
    <script>
        // Array of car markers
        const cars = @json($cars);
        const markersMap = {};
        let currentMarkers = {};
        let activePopup = null;

        // Initialize Maplibre Map
        const map = new maplibregl.Map({
            container: 'map',
            // Using CartoDB Voyager style - 100% Free, Beautiful, No API Key needed!
            style: 'https://basemaps.cartocdn.com/gl/voyager-gl-style/style.json',
            center: [106.6713, 10.7904], // Coordinates for Lê Văn Sỹ HCMC
            zoom: 12.5
        });

        // Add Zoom & Navigation controls
        map.addControl(new maplibregl.NavigationControl());

        // Prepare GeoJSON points for Supercluster
        const points = cars
            .filter(car => car.latitude && car.longitude)
            .map(car => ({
                type: 'Feature',
                properties: {
                    id: car.id,
                    title: car.title,
                    brand: car.brand,
                    price_per_day: car.price_per_day,
                    slug: car.slug,
                    images: car.images,
                    thumbnail_url: car.thumbnail_url,
                    location: car.location,
                    transmission: car.transmission
                },
                geometry: {
                    type: 'Point',
                    coordinates: [parseFloat(car.longitude), parseFloat(car.latitude)]
                }
            }));

        // Initialize Supercluster index
        const index = new Supercluster({
            radius: 60, // cluster radius in pixels
            maxZoom: 16 // max zoom to cluster points on
        });
        index.load(points);

        // Render clusters and single markers on view change
        function updateMarkers() {
            const zoom = Math.floor(map.getZoom());
            // Query clusters globally so that markers do not redraw, flicker, or shift while panning
            const bbox = [-180, -90, 180, 90];
            
            // Get clusters visible in current viewport bounding box and zoom level
            const clusters = index.getClusters(bbox, zoom);
            const newMarkers = {};

            clusters.forEach(cluster => {
                const [lng, lat] = cluster.geometry.coordinates;
                const isCluster = cluster.properties.cluster;
                const id = isCluster ? `cluster-${cluster.properties.cluster_id}` : `car-${cluster.properties.id}`;

                if (currentMarkers[id]) {
                    // Marker already exists, keep it
                    newMarkers[id] = currentMarkers[id];
                    delete currentMarkers[id];
                } else {
                    const el = document.createElement('div');

                    if (isCluster) {
                        // Cú pháp cụm (Circle với số lượng)
                        el.className = 'cluster-marker';
                        el.innerText = cluster.properties.point_count;

                        el.addEventListener('click', () => {
                            const expansionZoom = index.getClusterExpansionZoom(cluster.properties.cluster_id);
                            map.easeTo({
                                center: [lng, lat],
                                zoom: Math.min(expansionZoom + 1, 17)
                            });
                        });
                    } else {
                        // Cú pháp đơn lẻ (Bong bóng giá tiền dạng nhộng giống hình mẫu)
                        el.className = 'price-marker';
                        const car = cluster.properties;

                        let priceText = '';
                        if (car.price_per_day >= 1000000) {
                            priceText = (car.price_per_day / 1000000).toFixed(1).replace('.0', '') + 'M';
                        } else {
                            priceText = (car.price_per_day / 1000).toFixed(0) + 'K';
                        }
                        // Thêm dấu tích xanh lá ở bên trái giá tiền
                        el.innerHTML = `<i class="fa-solid fa-circle-check text-emerald-500 mr-1 text-[11px]"></i><span>${priceText}</span>`;

                        // Hover Popup
                        const imagePath = car.thumbnail_url || 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=250&q=80';
                        const detailUrl = `{{ url('/cars') }}/${car.slug}`;
                        const formattedPrice = new Intl.NumberFormat('vi-VN').format(car.price_per_day) + 'đ';

                        const popupHTML = `
                            <div class="flex flex-col">
                                <img src="${imagePath}" alt="${car.title}" class="w-full h-28 object-cover">
                                <div class="p-3">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">${car.brand}</span>
                                    <h4 class="text-xs font-bold text-slate-900 line-clamp-1 -mt-0.5">${car.title}</h4>
                                    <div class="mt-2.5 pt-2 border-t border-slate-100">
                                        <span class="text-xs font-black text-brand">${formattedPrice} <span class="text-[9px] text-slate-400 font-normal">/ngày</span></span>
                                    </div>
                                </div>
                            </div>
                        `;

                        const popup = new maplibregl.Popup({ offset: 25, closeButton: false, closeOnClick: false })
                            .setLngLat([lng, lat])
                            .setHTML(popupHTML);

                        el.addEventListener('mouseenter', () => {
                            popup.addTo(map);
                        });
                        el.addEventListener('mouseleave', () => {
                            popup.remove();
                        });
                        el.addEventListener('click', (e) => {
                            e.stopPropagation();
                            window.location.href = detailUrl;
                        });

                        // Register coordinates and popup to show from sidebar list click
                        markersMap[car.id] = {
                            popup: popup,
                            coords: [lng, lat]
                        };
                    }

                    // Sử dụng neo ở chính giữa (anchor: 'center') do Marker hình nhộng đối xứng tròn không mũi trỏ dưới
                    const marker = new maplibregl.Marker({ element: el, anchor: 'center' })
                        .setLngLat([lng, lat])
                        .addTo(map);

                    newMarkers[id] = marker;
                }
            });

            // Remove markers that are no longer visible in current clusters
            for (const id in currentMarkers) {
                currentMarkers[id].remove();
            }

            currentMarkers = newMarkers;
        }

        // Load and register events
        map.on('load', () => {
            updateMarkers();
            map.on('moveend', updateMarkers);
        });

        // Function to fly to a car location from list click
        function focusCar(lng, lat, carId) {
            map.flyTo({
                center: [lng, lat],
                zoom: 15.5,
                essential: true
            });

            // Close active popup if open
            if (activePopup) {
                activePopup.remove();
            }

            // Open popup for the focused car once map movement stops and markers are redrawn
            map.once('moveend', () => {
                const target = markersMap[carId];
                if (target) {
                    target.popup.addTo(map);
                    activePopup = target.popup;
                }
            });
        }
    </script>
@endsection
