<div class="space-y-3" x-data="mapPicker()" x-init="init()">
    <!-- Custom animations and styles -->
    <style>
        @keyframes map-picker-spin {
            to { transform: rotate(360deg); }
        }
        @keyframes map-picker-pulse {
            50% { opacity: .5; }
        }
        .map-picker-spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid currentColor;
            border-top-color: transparent;
            border-radius: 50%;
            animation: map-picker-spin 0.8s linear infinite;
        }
        .map-picker-spinner-large {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #0284c7;
            border-top-color: transparent;
            border-radius: 50%;
            animation: map-picker-spin 0.8s linear infinite;
        }
        .map-picker-pulse {
            animation: map-picker-pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>

    <!-- Maplibre assets -->
    <link href="https://unpkg.com/maplibre-gl@4.0.0/dist/maplibre-gl.css" rel="stylesheet" />
    <script src="https://unpkg.com/maplibre-gl@4.0.0/dist/maplibre-gl.js"></script>

    <!-- Trigger button and current state display -->
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1.25rem; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); box-sizing: border-box;">
        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
            <div style="padding: 0.75rem; background-color: #f0f9ff; color: #0284c7; border-radius: 0.75rem; border: 1px solid #e0f2fe; display: flex; align-items: center; justify-content: center; flex-shrink: 0; width: 44px; height: 44px; box-sizing: border-box;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 24px; height: 24px; display: block;" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.446 6-1.912a1.859 1.859 0 0 0 1.297-1.76V3.007c0-.115-.08-.21-.193-.23a48.574 48.574 0 0 0-4.553-.36 1.859 1.859 0 0 0-1.902 1.43l-1.94 7.23a1.86 1.86 0 0 1-1.902 1.43l-2.072-.183a1.86 1.86 0 0 0-1.902 1.43l-1.94 7.23a1.86 1.86 0 0 1-1.902 1.43 48.555 48.555 0 0 0-1.804-.254A1.859 1.859 0 0 0 0 19.253V4.993c0-1.07.828-1.924 1.842-2.1l4.816-.838A1.855 1.855 0 0 1 8.5 2.85l1.94 7.23a1.86 1.86 0 0 0 1.902 1.43l2.072.183Z" />
                </svg>
            </div>
            <div>
                <h4 style="margin: 0; font-size: 0.875rem; font-weight: 700; color: #1e293b;">Định vị tọa độ trên bản đồ</h4>
                <p style="margin: 4px 0 0 0; font-size: 0.75rem; color: #64748b; line-height: 1.4;">
                    Định vị tọa độ GPS chính xác giúp khách hàng dễ dàng quét xe ở khu vực xung quanh họ.
                </p>
                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem; margin-top: 8px;">
                    <template x-if="hasCoords()">
                        <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 4px 8px; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 700; background-color: #ecfdf5; color: #047857; border: 1px solid #d1fae5;">
                            <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background-color: #10b981;" class="map-picker-pulse"></span>
                            Đã định vị: <span x-text="getCoordsText()"></span>
                        </span>
                    </template>
                    <template x-if="!hasCoords()">
                        <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 4px 8px; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 700; background-color: #fffbeb; color: #b45309; border: 1px solid #fef3c7;">
                            <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background-color: #f59e0b;"></span>
                            Chưa chọn vị trí cụ thể (Sẽ tự động dò theo địa chỉ)
                        </span>
                    </template>
                </div>
            </div>
        </div>
        <button type="button" @click="openMap()" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background-color: #0284c7; color: #ffffff; font-weight: 700; font-size: 0.875rem; border-radius: 0.75rem; border: none; cursor: pointer; transition: background-color 0.2s; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); flex-shrink: 0; outline: none; box-sizing: border-box;" onmouseover="this.style.backgroundColor='#0369a1'" onmouseout="this.style.backgroundColor='#0284c7'">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px; display: block;" width="16" height="16">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
            </svg>
            <span>Ghim vị trí bản đồ</span>
        </button>
    </div>

    <!-- Map Modal Panel -->
    <div x-show="open" 
         style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100vw; height: 100vh; z-index: 99999; align-items: center; justify-content: center; padding: 1rem; box-sizing: border-box;"
         :style="{ display: open ? 'flex' : 'none' }"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Backdrop with blur -->
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);" @click="closeMap()"></div>
        
        <!-- Modal Card Container -->
        <div style="position: relative; background-color: #ffffff; border-radius: 1.5rem; width: 100%; max-width: 85rem; height: 90vh; max-height: 850px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid #e2e8f0; z-index: 10; box-sizing: border-box;">
            
            <!-- Modal Header -->
            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; background-color: #f8fafc; box-sizing: border-box; flex-shrink: 0;">
                <div>
                    <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#0284c7" style="width: 20px; height: 20px; display: block;" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        <span>Ghim vị trí xe trên bản đồ</span>
                    </h3>
                    <p style="margin: 2px 0 0 0; font-size: 0.75rem; color: #64748b;">
                        Tìm kiếm địa chỉ, click hoặc kéo thả marker để định vị chính xác.
                    </p>
                </div>
                <button type="button" @click="closeMap()" style="padding: 0.5rem; color: #94a3b8; border: none; background: transparent; cursor: pointer; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; outline: none;" onmouseover="this.style.color='#475569'" onmouseout="this.style.color='#94a3b8'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px; display: block;" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Search Address Bar -->
            <div style="padding: 1rem; border-bottom: 1px solid #f1f5f9; display: flex; gap: 0.5rem; align-items: center; background-color: #f8fafc; box-sizing: border-box; flex-shrink: 0;">
                <div style="position: relative; flex: 1; display: flex; align-items: center;">
                    <span style="position: absolute; left: 0.875rem; color: #94a3b8; display: flex; align-items: center; pointer-events: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px; display: block;" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                        </svg>
                    </span>
                    <input type="text" x-model="searchQuery" @keydown.enter.prevent="geocodeSearch()" placeholder="Nhập địa chỉ để tìm vị trí nhanh (ví dụ: 222 Lê Văn Sỹ, Quận 3)..." style="width: 100%; padding: 0.625rem 1rem 0.625rem 2.5rem; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 0.75rem; font-size: 0.875rem; color: #0f172a; outline: none; box-sizing: border-box;" onfocus="this.style.borderColor='#0284c7'; this.style.boxShadow='0 0 0 2px rgba(2, 132, 199, 0.1)'" onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'" />
                </div>
                <button type="button" @click="geocodeSearch()" style="padding: 0.625rem 1.25rem; background-color: #0284c7; color: #ffffff; font-weight: 700; font-size: 0.875rem; border-radius: 0.75rem; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.375rem; flex-shrink: 0; outline: none; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#0369a1'" onmouseout="this.style.backgroundColor='#0284c7'">
                    <span x-show="!searching">Tìm kiếm</span>
                    <span x-show="searching" style="display: none;" class="map-picker-spinner"></span>
                </button>
            </div>

            <!-- Map View Container -->
            <div style="position: relative; flex: 1; background-color: #f1f5f9; min-height: 280px; display: flex; flex-direction: column; box-sizing: border-box;">
                <div id="map-picker-container" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" wire:ignore></div>
                
                <!-- Loading indicator overlay -->
                <div x-show="loadingAddress" style="display: none; position: absolute; top: 1rem; left: 1rem; background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); padding: 0.5rem 0.75rem; border-radius: 0.75rem; border: 1px solid rgba(226, 232, 240, 0.8); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 700; color: #334155; z-index: 20;">
                    <span class="map-picker-spinner-large"></span>
                    <span>Đang giải mã vị trí...</span>
                </div>
            </div>

            <!-- Location Meta and Sync Options -->
            <div style="padding: 1rem; border-top: 1px solid #f1f5f9; background-color: #f8fafc; display: flex; flex-direction: column; gap: 0.75rem; box-sizing: border-box; flex-shrink: 0;">
                <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center; gap: 0.5rem; flex-wrap: wrap; box-sizing: border-box;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                        <span style="font-size: 0.6875rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Tọa độ đã chọn:</span>
                        <span style="font-family: monospace; font-size: 0.75rem; font-weight: 700; background-color: rgba(226, 232, 240, 0.6); padding: 2px 6px; border-radius: 4px; color: #334155;" x-text="tempLat ? tempLat.toFixed(6) : 'N/A'"></span>
                        <span style="font-family: monospace; font-size: 0.75rem; font-weight: 700; background-color: rgba(226, 232, 240, 0.6); padding: 2px 6px; border-radius: 4px; color: #334155;" x-text="tempLng ? tempLng.toFixed(6) : 'N/A'"></span>
                    </div>
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; user-select: none; font-size: 0.75rem; font-weight: 700; color: #475569;">
                        <input type="checkbox" x-model="updateAddressField" style="width: 1rem; height: 1rem; border-radius: 0.25rem; border: 1px solid #cbd5e1; color: #0284c7; cursor: pointer; margin: 0; box-sizing: border-box;" />
                        <span>Cập nhật Địa chỉ chữ vào form bằng vị trí ghim</span>
                    </label>
                </div>
                
                <div style="background-color: #ffffff; padding: 0.75rem; border-radius: 1rem; border: 1px solid #e2e8f0; display: flex; gap: 0.75rem; align-items: flex-start; box-sizing: border-box; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                    <span style="color: #0284c7; flex-shrink: 0; display: flex; align-items: center; margin-top: 2px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 18px; height: 18px; display: block;" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                    </span>
                    <div>
                        <div style="font-size: 0.625rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; line-height: 1; margin-bottom: 4px;">Địa chỉ tương ứng</div>
                        <div style="font-size: 0.875rem; font-weight: 700; color: #1e293b;" x-text="resolvedAddress || 'Vui lòng ghim vị trí hoặc click chọn trên bản đồ...'"></div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer Buttons -->
            <div style="padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 0.75rem; background-color: #ffffff; box-sizing: border-box; flex-shrink: 0;">
                <button type="button" @click="closeMap()" style="padding: 0.625rem 1.25rem; border: 1px solid #cbd5e1; background-color: #ffffff; color: #334155; font-weight: 700; font-size: 0.875rem; border-radius: 0.75rem; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='#ffffff'">
                    Hủy bỏ
                </button>
                <button type="button" @click="saveLocation()" style="padding: 0.625rem 1.25rem; background-color: #0284c7; color: #ffffff; font-weight: 700; font-size: 0.875rem; border-radius: 0.75rem; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.375rem; outline: none; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#0369a1'" onmouseout="this.style.backgroundColor='#0284c7'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px; display: block;" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span>Lưu tọa độ & Đóng</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function mapPicker() {
        return {
            open: false,
            map: null,
            marker: null,
            latInput: null,
            lngInput: null,
            locationInput: null,
            
            // Alpine Reactive States
            latitude: '',
            longitude: '',
            tempLat: null,
            tempLng: null,
            searchQuery: '',
            searching: false,
            loadingAddress: false,
            resolvedAddress: '',
            updateAddressField: true,
            
            init() {
                // Periodically check if elements are present in the DOM
                const checkInterval = setInterval(() => {
                    if (typeof maplibregl !== 'undefined') {
                        clearInterval(checkInterval);
                        this.findInputs();
                        this.syncFromDOM();
                    }
                }, 150);
            },
            
            findInputs() {
                this.latInput = document.querySelector('[id$="latitude"]') || document.querySelector('input[name*="latitude"]');
                this.lngInput = document.querySelector('[id$="longitude"]') || document.querySelector('input[name*="longitude"]');
                this.locationInput = document.querySelector('[id$="location"]') || document.querySelector('input[name*="location"]');
            },
            
            syncFromDOM() {
                this.findInputs();
                if (this.latInput && this.lngInput) {
                    this.latitude = this.latInput.value || '';
                    this.longitude = this.lngInput.value || '';
                }
            },
            
            hasCoords() {
                return this.latitude && this.longitude;
            },
            
            getCoordsText() {
                if (!this.latitude || !this.longitude) return '';
                return `${parseFloat(this.latitude).toFixed(5)}, ${parseFloat(this.longitude).toFixed(5)}`;
            },
            
            openMap() {
                this.syncFromDOM();
                this.open = true;
                
                // Read current values to init temp states
                if (this.latitude && this.longitude) {
                    this.tempLat = parseFloat(this.latitude);
                    this.tempLng = parseFloat(this.longitude);
                } else {
                    this.tempLat = 10.7904;
                    this.tempLng = 106.6713;
                }
                
                // Resolve address if we have coordinates
                if (this.latitude && this.longitude) {
                    this.reverseGeocode(this.tempLat, this.tempLng);
                }
                
                // Sync searchQuery with location text if available
                if (this.locationInput && this.locationInput.value) {
                    this.searchQuery = this.locationInput.value;
                } else {
                    this.searchQuery = '';
                }

                this.$nextTick(() => {
                    if (!this.map) {
                        this.initMap();
                    } else {
                        this.map.resize();
                        this.marker.setLngLat([this.tempLng, this.tempLat]);
                        this.map.setCenter([this.tempLng, this.tempLat]);
                    }
                });
            },
            
            closeMap() {
                this.open = false;
            },
            
            initMap() {
                this.map = new maplibregl.Map({
                    container: 'map-picker-container',
                    style: 'https://basemaps.cartocdn.com/gl/voyager-gl-style/style.json',
                    center: [this.tempLng, this.tempLat],
                    zoom: 14
                });

                // Add navigation controls
                this.map.addControl(new maplibregl.NavigationControl(), 'top-right');

                this.marker = new maplibregl.Marker({
                    draggable: true,
                    color: '#0284c7'
                })
                .setLngLat([this.tempLng, this.tempLat])
                .addTo(this.map);

                // Drag end events
                this.marker.on('dragend', () => {
                    const lngLat = this.marker.getLngLat();
                    this.tempLat = lngLat.lat;
                    this.tempLng = lngLat.lng;
                    this.reverseGeocode(lngLat.lat, lngLat.lng);
                });

                // Map click events
                this.map.on('click', (e) => {
                    this.marker.setLngLat(e.lngLat);
                    this.tempLat = e.lngLat.lat;
                    this.tempLng = e.lngLat.lng;
                    this.reverseGeocode(e.lngLat.lat, e.lngLat.lng);
                });
            },
            
            geocodeSearch() {
                if (!this.searchQuery) return;
                this.searching = true;
                
                fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(this.searchQuery)}&format=json&limit=1`, {
                    headers: {
                        'User-Agent': 'NKS-Car-Sharing-App/1.0'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        this.tempLat = lat;
                        this.tempLng = lon;
                        this.resolvedAddress = data[0].display_name;
                        
                        this.marker.setLngLat([lon, lat]);
                        this.map.flyTo({ center: [lon, lat], zoom: 16 });
                    } else {
                        alert('Không tìm thấy địa điểm trên bản đồ. Vui lòng ghi chi tiết hơn hoặc ghim thủ công.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Lỗi kết nối khi tìm kiếm vị trí.');
                })
                .finally(() => {
                    this.searching = false;
                });
            },
            
            reverseGeocode(lat, lng) {
                this.loadingAddress = true;
                
                fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`, {
                    headers: {
                        'User-Agent': 'NKS-Car-Sharing-App/1.0'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data && data.display_name) {
                        // Keep a polished clean display address
                        this.resolvedAddress = data.display_name;
                    } else {
                        this.resolvedAddress = 'Không thể xác định địa chỉ chính xác tại điểm ghim này.';
                    }
                })
                .catch(err => {
                    console.error(err);
                    this.resolvedAddress = 'Lỗi kết nối khi xác định địa chỉ.';
                })
                .finally(() => {
                    this.loadingAddress = false;
                });
            },
            
            saveLocation() {
                this.findInputs();
                if (this.tempLat && this.tempLng) {
                    if (this.latInput && this.lngInput) {
                        this.latInput.value = this.tempLat.toFixed(6);
                        this.lngInput.value = this.tempLng.toFixed(6);
                        
                        // Dispatch Events for Livewire Model Syncing
                        this.latInput.dispatchEvent(new Event('input', { bubbles: true }));
                        this.latInput.dispatchEvent(new Event('change', { bubbles: true }));
                        this.lngInput.dispatchEvent(new Event('input', { bubbles: true }));
                        this.lngInput.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    
                    if (this.updateAddressField && this.resolvedAddress && this.locationInput) {
                        this.locationInput.value = this.resolvedAddress;
                        this.locationInput.dispatchEvent(new Event('input', { bubbles: true }));
                        this.locationInput.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    
                    // Sync to reactive Alpine State
                    this.latitude = this.tempLat.toFixed(6);
                    this.longitude = this.tempLng.toFixed(6);
                }
                this.closeMap();
            }
        };
    }
</script>
