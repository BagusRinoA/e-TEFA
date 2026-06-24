@extends('layouts.app')

@section('title', 'Contact - e-TEFA Kompeni')

@section('content')
    <div class="min-h-screen bg-secondary py-12">
        <div class="container mx-auto px-4 max-w-5xl">
            <div class="bg-white shadow-xl rounded-3xl border border-gray-200 overflow-hidden">
                <div class="grid lg:grid-cols-[1.3fr,0.9fr] gap-8 p-8">
                    <div class="space-y-6">
                        <div>
                            <h1 class="text-4xl font-bold text-foreground">Hubungi Kami</h1>
                            <p class="mt-3 text-gray-600">Butuh bantuan atau ingin datang langsung? Berikut alamat dan lokasi kantor kami.</p>
                        </div>

                        <div class="space-y-4">
                            <div class="rounded-3xl border border-gray-200 bg-gray-50 p-6">
                                <h2 class="text-lg font-semibold text-foreground mb-3">Lokasi</h2>
                                <p class="text-sm text-muted-foreground">Gedung Agroindustri Politeknik Negeri Subang, Kampus Utama Cibogo</p>
                                <p class="text-sm text-muted-foreground">Jalan Ir. H. Juanda No. 1, Cibogo, Subang, Jawa Barat</p>
                                <a href="https://www.bing.com/maps/search?name=Politeknik+Negeri+Subang%2C+Kampus+Utama+Cibogo&trfc=&FORM=MPSRPL&style=r&q=Gedung+Agroindustri+Politeknik%E2%80%A6&ss=id.ypid%3A78564550C587746&cp=-6.559501~107.822387&lvl=14.2"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="mt-4 inline-flex items-center gap-2 rounded-full border border-green-500 bg-green-50 px-4 py-2 text-sm font-semibold text-green-700 hover:bg-green-100 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 6-9 13-9 13S3 16 3 10a9 9 0 0118 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    Buka di Bing Maps
                                </a>
                            </div>

                            <div class="rounded-3xl border border-gray-200 bg-gray-50 p-6">
                                <h2 class="text-lg font-semibold text-foreground mb-3">Kontak</h2>
                                <p class="text-sm text-muted-foreground"><strong>Email:</strong> support@e-tefa.id</p>
                                <p class="text-sm text-muted-foreground"><strong>Telepon:</strong> (0260) 123-456</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-gray-200 bg-gray-50 p-6">
                        <h2 class="text-lg font-semibold text-foreground mb-4">Informasi Tambahan</h2>
                        <p class="text-sm text-muted-foreground leading-relaxed">Temukan lokasi kami dengan mudah melalui Bing Maps. Klik tombol "Buka di Bing Maps" untuk melihat rute dan detail alamat secara langsung.</p>
                        <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm">
                            <p class="text-sm font-semibold text-foreground mb-3">Alamat:</p>
                            <p class="text-sm text-muted-foreground">Gedung Agroindustri Politeknik Negeri Subang</p>
                            <p class="text-sm text-muted-foreground">Kampus Utama Cibogo, Subang</p>
                            <p class="text-sm text-muted-foreground">Jawa Barat, Indonesia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
