<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GIS Cuaca Kabupaten Sambas</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        #map { height: 400px; }
        .container { max-width: 900px; margin: auto; text-align: center; padding: 20px; }
    </style>
</head>
<body>

    <div class="container">
        <h2>GIS Cuaca Kabupaten Sambas</h2>
        <p>Sumber Data: <a href="https://data.bmkg.go.id/">BMKG</a> </p>

        <div style="margin-top: 50px;">
            <label for="desaSelect">Pilih Desa:</label>
            <select id="desaSelect">
                <option value="">-- Pilih Desa --</option>
                
                <!-- Kecamatan 61.01.01 -->
                <optgroup label="Kecamatan Sambas">
                    <option value="61.01.01.2001">Dalam Kaum</option>
                    <option value="61.01.01.2002">Lubuk Dagang</option>
                    <option value="61.01.01.2003">Tanjung Bugis</option>
                    <option value="61.01.01.2004">Pendawan</option>
                    <option value="61.01.01.2005">Pasar Melayu</option>
                    <option value="61.01.01.2006">Durian</option>
                    <option value="61.01.01.2007">Lorong</option>
                    <option value="61.01.01.2008">Jagur</option>
                    <option value="61.01.01.2009">Tumuk Manggis</option>
                    <option value="61.01.01.2010">Tanjung Mekar</option>
                    <option value="61.01.01.2011">Sebayan</option>
                    <option value="61.01.01.2012">Kartiasa</option>
                    <option value="61.01.01.2013">Saing Rambi</option>
                    <option value="61.01.01.2014">Lumbang</option>
                    <option value="61.01.01.2015">Sungai Rambah</option>
                    <option value="61.01.01.2023">Gapura</option>
                    <option value="61.01.01.2024">Sumber Harapan</option>
                    <option value="61.01.01.2029">Semangau</option>
                </optgroup>

                <!-- Kecamatan 61.01.02 -->
                <optgroup label="Kecamatan Teluk Keramat">
                    <option value="61.01.02.2001">Sungai Kumpai</option>
                    <option value="61.01.02.2002">Sekura</option>
                    <option value="61.01.02.2003">Tri Mandayan</option>
                    <option value="61.01.02.2004">Pedada</option>
                    <option value="61.01.02.2005">Lela</option>
                    <option value="61.01.02.2006">Puringan</option>
                    <option value="61.01.02.2007">Berlimang</option>
                    <option value="61.01.02.2008">Sungai Baru</option>
                    <option value="61.01.02.2009">Sengawang</option>
                    <option value="61.01.02.2010">Teluk Kaseh</option>
                    <option value="61.01.02.2011">Sepadu</option>
                    <option value="61.01.02.2012">Tambatan</option>
                    <option value="61.01.02.2013">Kubangga</option>
                    <option value="61.01.02.2020">Sungai Serabek</option>
                    <option value="61.01.02.2021">Sayang Sedayu</option>
                    <option value="61.01.02.2022">Pipit Teja</option>
                    <option value="61.01.02.2024">Matang Segantar</option>
                    <option value="61.01.02.2025">Mulia</option>
                    <option value="61.01.02.2026">Teluk Kumbang</option>
                    <option value="61.01.02.2027">Samustida</option>
                    <option value="61.01.02.2028">Tanjung Kerucut</option>
                    <option value="61.01.02.2029">Sebagu</option>
                    <option value="61.01.02.2030">Mekar Sekuntum</option>
                    <option value="61.01.02.2031">Kuala Pangkalan Keramat</option>
                    <option value="61.01.02.2032">Sabing</option>
                </optgroup>

                <!-- Kecamatan 61.01.03 -->
                <optgroup label="Kecamatan Jawai">
                    <option value="61.01.03.2001">Sarang Burung Danau</option>
                    <option value="61.01.03.2002">Sungai Nilam</option>
                    <option value="61.01.03.2003">Sarang Burung Kolam</option>
                    <option value="61.01.03.2004">Sarang Burung Usrat</option>
                    <option value="61.01.03.2005">Sarang Burung Kuala</option>
                    <option value="61.01.03.2006">Pelimpaan</option>
                    <option value="61.01.03.2007">Parit Setia</option>
                    <option value="61.01.03.2008">Bakau</option>
                    <option value="61.01.03.2009">Sungai Nyirih</option>
                    <option value="61.01.03.2010">Sentebang</option>
                    <option value="61.01.03.2011">Dungun Laut</option>
                    <option value="61.01.03.2021">Lambau</option>
                    <option value="61.01.03.2022">Mutus Darussalam</option>
                </optgroup>
            </select>



            <h3 id="lokasi">Memuat lokasi...</h3>
            <p id="cuaca">Memuat cuaca...</p>
            <p id="suhu"></p>
            <p id="kelembaban"></p>
            <p id="arahAngin"></p>
            <p id="kecepatanAngin"></p>
            <img id="ikonCuaca" src="" alt="" style="display: none; width: 50px;">
            <div id="map"></div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <script>
        let map = L.map('map').setView([1.381055365, 109.2613646903], 12);
    
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
    
        let marker, circle;

        function getWeatherData(adm4 = "61.01.01.2012") {
            fetch(`https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=${adm4}`)
                .then(response => response.json())
                .then(data => {

                    if (!data || !data.data || data.data.length === 0) {
                        console.error("Error: Data cuaca tidak ditemukan!");
                        document.getElementById("lokasi").innerText = "Lokasi: Tidak ditemukan";
                        document.getElementById("cuaca").innerText = "Cuaca sekarang: Tidak tersedia";
                        return;
                    }

                    let lokasiData = data.data[0].lokasi;
                    let lokasi = `${lokasiData.kecamatan}, ${lokasiData.desa}`;
                    let lon = parseFloat(lokasiData.lon);
                    let lat = parseFloat(lokasiData.lat);

                    if (isNaN(lat) || isNaN(lon)) {
                        console.error("Error: Latitude dan Longitude tidak valid!");
                        document.getElementById("lokasi").innerText = "Lokasi: Tidak ditemukan";
                        return;
                    }

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

                    let jarakPandang = cuacaTerdekat.vs || 10000;
                    let vsText = cuacaTerdekat.vs_text || "> 10 km";

                    document.getElementById("lokasi").innerText = `Lokasi: ${lokasi} - prediksi 3 jam kedepan (${waktuCuaca})`;
                    document.getElementById("cuaca").innerHTML = `
                        <img src="${iconCuaca}" alt="${kondisiCuaca}" style="width:50px;">
                        <br>Cuaca Sekarang: ${kondisiCuaca} 
                        <br>Suhu: ${suhu}°C 
                        <br>Kelembaban: ${kelembaban}%
                        <br>Jarak Pandang: ${vsText}
                    `;

                    if (marker) {
                        map.removeLayer(marker);
                    }
                    if (circle) {
                        map.removeLayer(circle);
                    }

                    // Tambahkan marker baru ke peta
                    marker = L.marker([lat, lon]).addTo(map)
                        .bindPopup(`<b>Cuaca di ${lokasi}:</b> ${kondisiCuaca} <br> Suhu: ${suhu}°C <br> Kelembaban: ${kelembaban}% <br> Jarak Pandang: ${vsText}`)
                        .openPopup();

                    // Tambahkan lingkaran (circle) berdasarkan jarak pandang
                    circle = L.circle([lat, lon], {
                        color: 'green',
                        fillColor: '#72c490',
                        fillOpacity: 0.3,
                        radius: jarakPandang // Radius dalam meter
                    }).addTo(map)
                    .bindPopup(`<b>Jarak Pandang:</b> ${vsText}`) // Tambahkan popup
                    .on('click', function (e) {
                        this.openPopup(); // Buka popup saat circle diklik
                    });
                })
                .catch(error => console.error("Error mengambil data cuaca:", error));
        }

        // Event listener untuk dropdown desa
        document.getElementById("desaSelect").addEventListener("change", function () {
            let selectedAdm4 = this.value;
            if (selectedAdm4) {
                getWeatherData(selectedAdm4);
            }
        });

        // Panggil pertama kali dengan default
        getWeatherData();
    
        // **Auto-refresh setiap 5 menit**
        setInterval(getWeatherData, 300000);
    </script>

</body>
</html>
