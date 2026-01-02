@extends('admin.layouts.adminmaster')

@section('content')
    <div class="page-heading">
    <section class="section">
        <div class="page-content">
            <section class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                         <div class="col-5 d-flex justify-content-start">
                                            <div class="stats-icon blue mb-2">
                                                <i class="iconly-boldBookmark"></i>
                                            </div>
                                        </div>
                                        <div class="col-7">
                                            <h6 class="text-muted font-semibold">Jurusan</h6>
                                            <h6 class="font-extrabold mb-0">{{ $totaljurusan }}</h6>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card"> 
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                    <div class="col-5 d-flex justify-content-start">
                                            <div class="stats-icon purple mb-2">
                                                <i class="iconly-boldWork"></i>
                                            </div>
                                        </div>
                                        <div class="col-7">
                                            <h6 class="text-muted font-semibold">Kelas</h6>
                                            <h6 class="font-extrabold mb-0">{{ $totalkelas }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                    <div class="col-5 d-flex justify-content-start">
                                            <div class="stats-icon green mb-2">
                                                <i class="iconly-boldStar"></i>
                                            </div>
                                        </div>
                                        <div class="col-7">
                                            <h6 class="text-muted font-semibold">Murid</h6>
                                            <h6 class="font-extrabold mb-0">{{ $totalmurid }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                    <div class="col-5 d-flex justify-content-start">
                                            <div class="stats-icon red mb-2">
                                                <i class="iconly-boldCalendar"></i>
                                            </div>
                                        </div>
                                        <div class="col-7">
                                            <h6 class="text-muted font-semibold">Mata Pelajaran</h6>
                                            <h6 class="font-extrabold mb-0">{{ $totalpel }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div class="col-5 d-flex justify-content-start">
                                            <div class="stats-icon red mb-2">
                                                <i class="iconly-boldUser"></i>
                                            </div>
                                        </div>
                                        <div class="col-7">
                                            <h6 class="text-muted font-semibold">Guru</h6>
                                            <h6 class="font-extrabold mb-0">{{ $totalguru }}</h6>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card"> 
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div class="col-5 d-flex justify-content-start">
                                            <div class="stats-icon green mb-2">
                                                <i class="iconly-boldPaper-Plus"></i>
                                            </div>
                                        </div>
                                        <div class="col-7">
                                            <h6 class="text-muted font-semibold">Mengajar</h6>
                                            <h6 class="font-extrabold mb-0">{{ $totalmengajar }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div class="col-5 d-flex justify-content-start">
                                            <div class="stats-icon purple mb-2">
                                                <i class="iconly-boldWork"></i>
                                            </div>
                                        </div>
                                        <div class="col-7">
                                            <h6 class="text-muted font-semibold">Hari</h6>
                                            <h6 class="font-extrabold mb-0">{{ $totalhari }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div class="col-5 d-flex justify-content-start">
                                            <div class="stats-icon blue mb-2">
                                                <i class="iconly-boldTime-Circle"></i>
                                            </div>
                                        </div>
                                        <div class="col-7">
                                            <h6 class="text-muted font-semibold">Jam</h6>
                                            <h6 class="font-extrabold mb-0">{{ $totaljam }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>
@endsection
