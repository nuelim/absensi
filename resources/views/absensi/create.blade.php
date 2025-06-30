@extends('layouts.app')

@section('title', 'Ambil Absen dengan Wajah')

@section('content')
{{-- Wadah untuk notifikasi yang akan muncul di pojok kanan atas --}}
<div id="notification-container"></div>

<style>
    /* Style untuk menumpuk canvas dan pop-up di atas video */
    #webcam-container {
        position: relative;
        width: 100%;
        max-width: 640px; /* Batasi lebar maksimum */
        margin: auto;
        aspect-ratio: 4 / 3; /* Menjaga rasio aspek 4:3 */
        border: 5px solid #333;
        background-color: #000;
        border-radius: 10px;
        overflow: hidden; /* Agar video tidak keluar dari border-radius */
    }
    video, canvas { 
        position: absolute;
        top: 0;
        left: 0;
        width: 100%; 
        height: 100%;
    }

    /* Style untuk Notifikasi Toast */
    #notification-container {
        position: fixed;
        top: 90px;
        right: 20px;
        z-index: 1050;
        width: 300px;
    }
    .toast-notification {
        background-color: #28a745;
        color: white;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        opacity: 0;
        animation: slideInFadeIn 0.5s forwards, slideOutFadeOut 0.5s 4.5s forwards;
    }
    @keyframes slideInFadeIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutFadeOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
</style>

<div class="container">
    <h2 class="mb-4">Ambil Absen dengan Wajah (Real-time)</h2>
    
    <div class="row">
        {{-- Kolom Kiri: Tampilan Kamera --}}
        <div class="col-lg-7 mb-4">
            <h5 class="text-center">Area Kamera</h5>
            <div id="webcam-container">
                <video id="webcam" width="640" height="480" autoplay muted playsinline></video>
            </div>
            <p id="status-pesan" class="text-center mt-3 fw-bold text-primary"></p>
        </div>

        {{-- Kolom Kanan: Form Data dan Daftar Mahasiswa --}}
        <div class="col-lg-5">
            <h5>Data Kelas</h5>
            <div class="mb-3">
                <label for="mata_kuliah_id" class="form-label">Mata Kuliah</label>
                <select name="mata_kuliah_id" id="mata_kuliah_id" class="form-select">
                    @foreach($mataKuliahs as $mk)
                        <option value="{{ $mk->id }}">{{ $mk->nama_mk }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="tanggal_absensi" class="form-label">Tanggal</label>
                <input type="date" name="tanggal_absensi" id="tanggal_absensi" class="form-control" value="{{ date('Y-m-d') }}">
            </div>

            <h5 class="mt-4">Status Kehadiran</h5>
            <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                <table class="table table-bordered table-sm table-hover">
                    <thead class="table-light position-sticky top-0">
                        <tr>
                            <th>Nama Mahasiswa</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mahasiswas as $mhs)
                        <tr id="row-mhs-{{ $mhs->id }}">
                            <td>{{ $mhs->nama }}</td>
                            <td class="text-center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="absensi[{{ $mhs->id }}]" id="hadir_{{ $mhs->id }}" value="Hadir" checked>
                                    <label class="form-check-label" for="hadir_{{ $mhs->id }}">Hadir</label>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Memuat script face-api.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const videoElement = document.getElementById('webcam');
            const webcamContainer = document.getElementById('webcam-container');
            const statusPesan = document.getElementById('status-pesan');
            const mahasiswaTerdaftar = @json($mahasiswas);
            const MODEL_URL = '{{ asset('weights') }}';
            
            let faceMatcher;
            let sudahDiabsen = [];

            async function start() {
                statusPesan.innerText = "Memuat Model AI...";
                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                    faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                    faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
                ]);

                statusPesan.innerText = "Mempersiapkan data wajah...";
                faceMatcher = await createFaceMatcher();
                
                statusPesan.innerText = "Siap, silakan buka kamera.";
                startCamera();
            }
            start();

            function startCamera() {
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(stream => {
                        videoElement.srcObject = stream;
                    })
                    .catch(err => {
                        console.error("Error membuka kamera:", err);
                        statusPesan.innerText = "Gagal membuka kamera. Pastikan Anda memberikan izin.";
                    });
            }

            async function createFaceMatcher() {
                const labeledFaceDescriptors = await Promise.all(
                    mahasiswaTerdaftar.map(async mahasiswa => {
                        const descriptor = new Float32Array(Object.values(JSON.parse(mahasiswa.face_descriptor)));
                        return new faceapi.LabeledFaceDescriptors(mahasiswa.id.toString(), [descriptor]);
                    })
                );
                return new faceapi.FaceMatcher(labeledFaceDescriptors, 0.5);
            }

            function showNotification(message) {
                const container = document.getElementById('notification-container');
                const notification = document.createElement('div');
                notification.className = 'toast-notification';
                notification.innerText = message;
                container.appendChild(notification);
                setTimeout(() => {
                    notification.remove();
                }, 5000);
            }
            
            // FUNGSI BARU UNTUK MENAMPILKAN TANDA CENTANG
            function showCheckmark(box) {
                const checkmark = document.createElement('div');
                checkmark.innerHTML = '<svg viewBox="0 0 512 512" style="width:60px; height:60px; fill: #28a745; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.997 26.206-9.997 36.204 0L192 347.606l136.796-166.398c9.997-9.997 26.206-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-166.4 166.4c-9.999 9.998-26.207 9.998-36.204-.001z"/></svg>';
                checkmark.style.position = 'absolute';
                checkmark.style.left = `${box.x + (box.width / 2) - 30}px`;
                checkmark.style.top = `${box.y + (box.height / 2) - 30}px`;
                checkmark.style.zIndex = '1001';
                webcamContainer.appendChild(checkmark);

                checkmark.animate([
                    { opacity: 0, transform: 'scale(0.5)' },
                    { opacity: 1, transform: 'scale(1.2)' },
                    { opacity: 1, transform: 'scale(1)' },
                    { opacity: 0, transform: 'scale(1.5)' }
                ], {
                    duration: 1500,
                    easing: 'ease-in-out'
                }).finished.then(() => {
                    checkmark.remove();
                });
            }

            function kirimAbsensi(mahasiswaId, matchedMahasiswa, box) {
                const mataKuliahId = document.getElementById('mata_kuliah_id').value;
                const tanggalAbsensi = document.getElementById('tanggal_absensi').value;
                
                fetch("{{ route('absensi.otomatis') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        mahasiswa_id: mahasiswaId,
                        mata_kuliah_id: mataKuliahId,
                        tanggal_absensi: tanggalAbsensi
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Server Response:', data.message);
                    const row = document.querySelector(`#row-mhs-${mahasiswaId}`);
                    if (row) {
                        row.style.backgroundColor = '#d1e7dd';
                        row.style.fontWeight = 'bold';
                        row.querySelectorAll('input[type="radio"]').forEach(radio => radio.disabled = true);
                        if (data.message.includes('berhasil')) {
                            showNotification(`${matchedMahasiswa.nama} berhasil diabsen!`);
                            showCheckmark(box);
                        }
                    }
                })
                .catch(error => console.error('Error saat mengirim absensi:', error));
            }

            videoElement.addEventListener('play', () => {
                const canvas = faceapi.createCanvasFromMedia(videoElement);
                webcamContainer.append(canvas);
                const displaySize = { width: videoElement.width, height: videoElement.height };
                faceapi.matchDimensions(canvas, displaySize);
                
                statusPesan.innerText = "Deteksi berjalan... Arahkan wajah ke kamera.";

                setInterval(async () => {
                    const detections = await faceapi.detectAllFaces(videoElement, new faceapi.TinyFaceDetectorOptions())
                                                    .withFaceLandmarks()
                                                    .withFaceDescriptors();
                    const resizedDetections = faceapi.resizeResults(detections, displaySize);
                    
                    canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);

                    resizedDetections.forEach(detection => {
                        const bestMatch = faceMatcher.findBestMatch(detection.descriptor);
                        const matchedMahasiswa = mahasiswaTerdaftar.find(m => m.id.toString() === bestMatch.label);
                        const label = matchedMahasiswa ? `${matchedMahasiswa.nama}` : `Tidak Dikenal`;
                        
                        const drawBox = new faceapi.draw.DrawBox(detection.detection.box, { label: label });
                        drawBox.draw(canvas);

                        if (bestMatch.label !== 'unknown') {
                            const mahasiswaId = parseInt(bestMatch.label);
                            if (!sudahDiabsen.includes(mahasiswaId) && matchedMahasiswa) {
                                sudahDiabsen.push(mahasiswaId);
                                kirimAbsensi(mahasiswaId, matchedMahasiswa, detection.detection.box);
                            }
                        }
                    });
                }, 500);
            });
        });
    </script>
@endpush