<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GIS Cuaca Kabupaten Sambas</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        #map { height: 400px; }
        .container { max-width: 600px; margin: auto; text-align: center; padding: 20px; }
    </style>
</head>
<body>

    <div class="container">
        <h2>GIS Cuaca Kabupaten Sambas</h2>
        <p>Sumber Data: BMKG</p>
        <h3 id="lokasi">Memuat lokasi...</h3>
        <p id="cuaca">Memuat cuaca...</p>
        <div id="map"></div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <script>
        let map = L.map('map').setView([1.381055365, 109.2613646903], 12);
    
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
    
        let marker, circle;

        // api BMKG
        // https://data.bmkg.go.id/prakiraan-cuaca/
    
        function getWeatherData() {
            fetch("https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=61.01.01.2012")
                .then(response => response.json())
                .then(data => {
                    console.log("Data dari API:", data);
    
                    if (!data || !data.data || data.data.length === 0) {
                        console.error("Error: Data cuaca tidak ditemukan!");
                        document.getElementById("lokasi").innerText = "Lokasi: Tidak ditemukan";
                        document.getElementById("cuaca").innerText = "Cuaca sekarang: Tidak tersedia";
                        return;
                    }
    
                    // Ambil lokasi
                    let lokasiData = data.data[0].lokasi;
                    let lokasi = `${lokasiData.kecamatan}, ${lokasiData.desa}`;
                    let lon = parseFloat(lokasiData.lon);
                    let lat = parseFloat(lokasiData.lat);
    
                    if (isNaN(lat) || isNaN(lon)) {
                        console.error("Error: Latitude dan Longitude tidak valid!");
                        document.getElementById("lokasi").innerText = "Lokasi: Tidak ditemukan";
                        return;
                    }
    
                    // Gabungkan semua data cuaca
                    let semuaCuaca = [].concat(...data.data[0].cuaca);
                    
                    let sekarang = new Date().toISOString();
                    let cuacaMasaDepan = semuaCuaca.filter(item => item.datetime >= sekarang);
                    let cuacaTerdekat = cuacaMasaDepan.length > 0 ? cuacaMasaDepan[0] : semuaCuaca[0];
    
                    if (!cuacaTerdekat) {
                        console.error("Error: Data cuaca terbaru tidak ditemukan!");
                        document.getElementById("cuaca").innerText = "Cuaca sekarang: Tidak tersedia";
                        return;
                    }
    
                    let kondisiCuaca = cuacaTerdekat.weather_desc || "Tidak tersedia";
                    let suhu = cuacaTerdekat.t || "N/A";
                    let kelembaban = cuacaTerdekat.hu || "N/A";
                    let iconCuaca = cuacaTerdekat.image || "";
                    let waktuCuaca = new Date(cuacaTerdekat.datetime).toLocaleString("id-ID", { timeZone: "Asia/Pontianak" });
    
                    // **Menampilkan vs_text (jarak pandang)**
                    let jarakPandang = cuacaTerdekat.vs || 10000; // Default 10 km jika data tidak ada
                    let vsText = cuacaTerdekat.vs_text || "> 10 km";
    
                    // Update tampilan
                    document.getElementById("lokasi").innerText = `Lokasi: ${lokasi}`;
                    document.getElementById("cuaca").innerHTML = `
                        <img src="${iconCuaca}" alt="${kondisiCuaca}" style="width:50px;">
                        <br>Cuaca (${waktuCuaca}): ${kondisiCuaca} 
                        <br>Suhu: ${suhu}°C 
                        <br>Kelembaban: ${kelembaban}%
                        <br>Jarak Pandang: ${vsText}
                    `;
    
                    // **Hapus marker lama sebelum menambahkan yang baru**
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    if (circle) {
                        map.removeLayer(circle);
                    }
    
                    // **Tambahkan marker baru ke peta**
                    marker = L.marker([lat, lon]).addTo(map)
                        .bindPopup(`<b>Cuaca di ${lokasi}:</b> ${kondisiCuaca} <br> Suhu: ${suhu}°C <br> Kelembaban: ${kelembaban}% <br> Jarak Pandang: ${vsText}`)
                        .openPopup();
    
                    // **Tambahkan lingkaran (circle) berdasarkan jarak pandang**
                    circle = L.circle([lat, lon], {
                        color: 'blue',
                        fillColor: '#add8e6',
                        fillOpacity: 0.3,
                        radius: jarakPandang // Radius dalam meter
                    }).addTo(map);
                })
                .catch(error => console.error("Error mengambil data cuaca:", error));
        }
    
        // **Panggil pertama kali**
        getWeatherData();
    
        // **Auto-refresh setiap 5 menit**
        setInterval(getWeatherData, 300000);
    </script>
    

</body>
</html>
