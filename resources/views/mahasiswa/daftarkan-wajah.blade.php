@extends('layouts.app')

@section('title', 'Daftarkan Wajah Mahasiswa')

@push('scripts')
    {{-- Memuat script face-api.js. 'defer' berarti script akan dijalankan setelah HTML selesai dimuat --}}
    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const videoElement = document.getElementById('webcam');
            const webcamContainer = document.getElementById('webcam-container');
            const tombolAmbilGambar = document.getElementById('tombolAmbilGambar');
            const statusPesan = document.getElementById('status-pesan');
            
            const MODEL_URL = '{{ asset('weights') }}';

            let stream;

            async function loadModels() {
                statusPesan.innerText = "Memuat Model AI...";
                try {
                    await Promise.all([
                        faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                        faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                        faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
                    ]);
                    statusPesan.innerText = "Model siap. Silakan buka kamera.";
                    tombolAmbilGambar.disabled = false;
                } catch (error) {
                    statusPesan.innerText = "Gagal memuat model AI. Cek koneksi internet.";
                    console.error("Gagal memuat model: ", error);
                }
            }

            loadModels();

            tombolAmbilGambar.addEventListener('click', async () => {
                if (!stream) {
                    // Buka Kamera
                    try {
                        stream = await navigator.mediaDevices.getUserMedia({ video: true });
                        videoElement.srcObject = stream;
                        tombolAmbilGambar.innerText = 'Ambil & Simpan Gambar Wajah';
                        statusPesan.innerText = 'Posisikan wajah Anda di tengah, lalu klik tombol.';
                    } catch (error) {
                        alert("Anda perlu mengizinkan akses kamera.");
                    }
                } else {
                    // Ambil Gambar dan Simpan
                    tombolAmbilGambar.disabled = true;
                    statusPesan.innerText = "Mendeteksi wajah...";

                    const detection = await faceapi.detectSingleFace(videoElement, new faceapi.TinyFaceDetectorOptions())
                                                    .withFaceLandmarks()
                                                    .withFaceDescriptor();
                    
                    if (detection) {
                        statusPesan.innerText = "Wajah terdeteksi! Menyimpan data...";
                        const descriptor = detection.descriptor;
                        
                        // Kirim descriptor ke backend Laravel
                        fetch("{{ route('mahasiswa.simpan-wajah', $user->id) }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ face_descriptor: descriptor })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                statusPesan.innerText = data.message;
                                alert(data.message);
                                window.location.href = "{{ route('mahasiswa.index') }}"; // Kembali ke daftar mahasiswa
                            }
                        })
                        .catch(error => {
                            statusPesan.innerText = "Gagal menyimpan data.";
                            console.error('Error:', error);
                            tombolAmbilGambar.disabled = false;
                        });

                    } else {
                        statusPesan.innerText = "Wajah tidak terdeteksi. Coba lagi.";
                        tombolAmbilGambar.disabled = false;
                    }
                }
            });
        });
    </script>
@endpush


@section('content')
<style>
    #webcam-container {
        position: relative;
        width: 640px;
        height: 480px;
        margin: auto;
        border: 5px solid #333;
    }
    video { width: 100%; height: 100%; }
</style>

<div class="container text-center">
    <h2>Daftarkan Wajah untuk: {{ $user->nama }}</h2>
    <p>Posisikan wajah Anda di depan kamera lalu klik tombol di bawah.</p>
    
    <div id="webcam-container" class="mb-3">
        <video id="webcam" autoplay muted playsinline></video>
    </div>
    
    <button id="tombolAmbilGambar" class="btn btn-primary btn-lg" disabled>Memuat Model AI...</button>
    <p id="status-pesan" class="mt-3"></p>
</div>
@endsection