@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title-container">
                    <h1 class="mb-0 pb-0 display-4" id="title">Analytic Dashboard</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="Dashboards.Default.html">Home</a></li>
                            <li class="breadcrumb-item"><a href="Dashboards.html">Dashboards</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-6">
                <!-- Stats Start -->
                <div class="d-flex">
                    <div class="dropdown-as-select me-3" data-setActive="false" data-childSelector="span">
                        <a class="pe-0 pt-0 align-top lh-1 dropdown-toggle" href="#" data-bs-toggle="dropdown"
                            aria-expanded="false" aria-haspopup="true">
                            <span class="small-title"></span>
                        </a>
                        <div class="dropdown-menu font-standard">
                            <div class="nav flex-column" role="tablist">
                                <a class="active dropdown-item text-medium" href="#" aria-selected="true"
                                    role="tab">Today's</a>
                                <a class="dropdown-item text-medium" href="#" aria-selected="false"
                                    role="tab">Weekly</a>
                                <a class="dropdown-item text-medium" href="#" aria-selected="false"
                                    role="tab">Monthly</a>
                                <a class="dropdown-item text-medium" href="#" aria-selected="false"
                                    role="tab">Yearly</a>
                            </div>
                        </div>
                    </div>
                    <h2 class="small-title">Stats</h2>
                </div>

                <div class="mb-5">
                    <div class="row g-2">

                        <div class="col-12 col-sm-6 col-lg-6">
                            <div class="card sh-11 hover-scale-up cursor-pointer">
                                <a href="/barang">
                                    <div class="h-100 row g-0 card-body align-items-center py-3">
                                        <div class="col-auto pe-3">
                                            <div
                                                class="bg-gradient-light sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                                                <i data-acorn-icon="navigate-diagonal" class="text-white"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row gx-2 d-flex align-content-center">
                                                <div class="col-12 col-xl d-flex">
                                                    <div class="d-flex align-items-center lh-1-25">Total Barang</div>
                                                </div>
                                                <div class="col-12 col-xl-auto">
                                                    <div class="cta-2 text-primary">{{ $totalBarang ?? 0 }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>


                        <div class="col-12 col-sm-6 col-lg-6">
                            <div class="card sh-11 hover-scale-up cursor-pointer">
                                <a href="/vendor">
                                    <div class="h-100 row g-0 card-body align-items-center py-3">
                                        <div class="col-auto pe-3">
                                            <div
                                                class="bg-gradient-light sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                                                <i data-acorn-icon="check" class="text-white"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row gx-2 d-flex align-content-center">
                                                <div class="col-12 col-xl d-flex">
                                                    <div class="d-flex align-items-center lh-1-25">Total Vendor</div>
                                                </div>
                                                <div class="col-12 col-xl-auto">
                                                    <div class="cta-2 text-primary">{{ $totalVendor ?? 0 }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-6">
                            <div class="card sh-11 hover-scale-up cursor-pointer">
                                <a href="/user">
                                    <div class="h-100 row g-0 card-body align-items-center py-3">
                                        <div class="col-auto pe-3">
                                            <div
                                                class="bg-gradient-light sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                                                <i data-acorn-icon="alarm" class="text-white"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row gx-2 d-flex align-content-center">
                                                <div class="col-12 col-xl d-flex">
                                                    <div class="d-flex align-items-center lh-1-25">Total User</div>
                                                </div>
                                                <div class="col-12 col-xl-auto">
                                                    <div class="cta-2 text-primary">{{ $totalUser ?? 0 }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-6">
                            <div class="card sh-11 hover-scale-up cursor-pointer">
                                <a href="/role">
                                    <div class="h-100 row g-0 card-body align-items-center py-3">
                                        <div class="col-auto pe-3">
                                            <div
                                                class="bg-gradient-light sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                                                <i data-acorn-icon="sync-horizontal" class="text-white"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row gx-2 d-flex align-content-center">
                                                <div class="col-12 col-xl d-flex">
                                                    <div class="d-flex align-items-center lh-1-25">Role</div>
                                                </div>
                                                <div class="col-12 col-xl-auto">
                                                    <div class="cta-2 text-primary">{{ $totalRole ?? 0 }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-6">
                            <div class="card sh-11 hover-scale-up cursor-pointer">
                                <a href="/margin-penjualan">
                                    <div class="h-100 row g-0 card-body align-items-center py-3">
                                        <div class="col-auto pe-3">
                                            <div
                                                class="bg-gradient-light sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                                                <i data-acorn-icon="navigate-diagonal" class="text-white"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row gx-2 d-flex align-content-center">
                                                <div class="col-12 col-xl d-flex">
                                                    <div class="d-flex align-items-center lh-1-25">Total Margin Penjualan</div>
                                                </div>
                                                <div class="col-12 col-xl-auto">
                                                    <div class="cta-2 text-primary">{{ $totalMarginPenjualan ?? 0 }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Stats End -->

                <!-- Sales Start -->
                <h2 class="small-title">Sales</h2>
                <div class="card mb-5 sh-40">
                    <div class="card-body">
                        <div class="custom-legend-container mb-3 pb-3 d-flex flex-row"></div>
                        <!-- Custom legend template used by js -->
                        <template class="custom-legend-item">
                            <a href="#" class="d-flex flex-row g-0 align-items-center me-5">
                                <div class="pe-2">
                                    <div
                                        class="icon-container border sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                                        <i class="icon"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="text p mb-0 d-flex align-items-center text-small text-muted">Title</div>
                                    <div class="value cta-4">Value</div>
                                </div>
                            </a>
                        </template>
                        <!-- Custom Legend Template End -->
                        <div class="sh-25">
                            <canvas id="customLegendBarChart"></canvas>
                            <!-- Custom tooltip template used by js -->
                            <div
                                class="custom-tooltip position-absolute bg-foreground rounded-md border border-separator pe-none p-3 d-flex z-index-1 align-items-center opacity-0 basic-transform-transition">
                                <div
                                    class="icon-container border d-flex align-middle align-items-center justify-content-center align-self-center rounded-xl sh-5 sw-5 rounded-xl me-3">
                                    <span class="icon"></span>
                                </div>
                                <div>
                                    <span
                                        class="text d-flex align-middle text-muted align-items-center text-small">Bread</span>
                                    <span class="value d-flex align-middle align-items-center cta-4">300</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sales End -->
            </div>

            <!-- Products Start -->
            <div class="col-12 col-lg-6 mb-5">
                <div class="d-flex justify-content-between">
                    <h2 class="small-title">Barang</h2>
                    <button class="btn btn-icon btn-icon-end btn-xs btn-background-alternate p-0 text-small"
                        type="button">
                        <a href="{{ route('barang.index') }}" class="align-bottom">View More</a>
                        <i data-acorn-icon="chevron-right" class="align-middle" data-acorn-size="12"></i>
                    </button>
                </div>
                <div class="scroll-out">
                    @foreach ($latestBarang as $barang)
                        <div class="scroll-by-count" data-count="8">
                            <div class="card mb-2 sh-10 sh-md-8">
                                <div class="card-body pt-0 pb-0 h-100">
                                    <div class="row g-0 h-100 align-content-center">
                                        <div class="col-12 col-md-5 d-flex align-items-center mb-2 mb-md-0">
                                            <a href="{{ route('barang.index', ['q' => $barang->nama_barang]) }}"
                                                class="body-link text-truncate fw-bold">{{ $barang->nama_barang }}</a>
                                        </div>
                                        <div
                                            class="col-4 col-md-3 d-flex align-items-center text-medium text-primary justify-content-center">
                                            <span class="text-medium">{{ $barang->jenis_barang }}</span>
                                        </div>

                                        <div
                                            class="col-4 col-md-2 d-flex align-items-center text-muted text-medium mb-1 mb-md-0">
                                            @if ($barang->status_text == 'Aktif')
                                                <span class="badge bg-success px-3 py-2 fs-7">Aktif</span>
                                            @else
                                                <span class="badge bg-danger px-3 py-2 fs-7">Tidak Aktif</span>
                                            @endif

                                        </div>
                                        <div
                                            class="col-4 col-md-2 d-flex align-items-center justify-content-end text-muted text-medium">
                                            <span>RP.{{ $barang->harga }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Products End -->
        </div>

        <div class="row">
            <!-- Logs Start -->
            <div class="col-xl-6 mb-5">
                <h2 class="small-title">Logs</h2>
                <div class="card sh-35 h-xl-100-card">
                    <div class="card-body scroll-out h-100">
                        <div class="scroll h-100">
                            <div class="row g-0 mb-2">
                                <div class="col-auto">
                                    <div class="sw-3 d-inline-block d-flex justify-content-start align-items-center h-100">
                                        <div class="sh-3">
                                            <i data-acorn-icon="circle" class="text-primary align-top"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div
                                        class="card-body d-flex flex-column pt-0 pb-0 ps-3 pe-4 h-100 justify-content-center">
                                        <div class="d-flex flex-column">
                                            <div class="text-alternate mt-n1 lh-1-25">New user registiration</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-inline-block d-flex justify-content-end align-items-center h-100">
                                        <div class="text-muted ms-2 mt-n1 lh-1-25">18 Dec</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0 mb-2">
                                <div class="col-auto">
                                    <div class="sw-3 d-inline-block d-flex justify-content-start align-items-center h-100">
                                        <div class="sh-3">
                                            <i data-acorn-icon="circle" class="text-primary align-top"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div
                                        class="card-body d-flex flex-column pt-0 pb-0 ps-3 pe-4 h-100 justify-content-center">
                                        <div class="d-flex flex-column">
                                            <div class="text-alternate mt-n1 lh-1-25">3 new product added</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-inline-block d-flex justify-content-end align-items-center h-100">
                                        <div class="text-muted ms-2 mt-n1 lh-1-25">18 Dec</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0 mb-2">
                                <div class="col-auto">
                                    <div class="sw-3 d-inline-block d-flex justify-content-start align-items-center h-100">
                                        <div class="sh-3">
                                            <i data-acorn-icon="square" class="text-secondary align-top"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div
                                        class="card-body d-flex flex-column pt-0 pb-0 ps-3 pe-4 h-100 justify-content-center">
                                        <div class="d-flex flex-column">
                                            <div class="text-alternate mt-n1 lh-1-25">
                                                Product out of stock:
                                                <a href="#" class="alternate-link underline-link">Breadstick</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-inline-block d-flex justify-content-end align-items-center h-100">
                                        <div class="text-muted ms-2 mt-n1 lh-1-25">16 Dec</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0 mb-2">
                                <div class="col-auto">
                                    <div class="sw-3 d-inline-block d-flex justify-content-start align-items-center h-100">
                                        <div class="sh-3">
                                            <i data-acorn-icon="square" class="text-secondary align-top"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div
                                        class="card-body d-flex flex-column pt-0 pb-0 ps-3 pe-4 h-100 justify-content-center">
                                        <div class="d-flex flex-column">
                                            <div class="text-alternate mt-n1 lh-1-25">Category page returned an error</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-inline-block d-flex justify-content-end align-items-center h-100">
                                        <div class="text-muted ms-2 mt-n1 lh-1-25">15 Dec</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0 mb-2">
                                <div class="col-auto">
                                    <div class="sw-3 d-inline-block d-flex justify-content-start align-items-center h-100">
                                        <div class="sh-3">
                                            <i data-acorn-icon="circle" class="text-primary align-top"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div
                                        class="card-body d-flex flex-column pt-0 pb-0 ps-3 pe-4 h-100 justify-content-center">
                                        <div class="d-flex flex-column">
                                            <div class="text-alternate mt-n1 lh-1-25">14 products added</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-inline-block d-flex justify-content-end align-items-center h-100">
                                        <div class="text-muted ms-2 mt-n1 lh-1-25">15 Dec</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0 mb-2">
                                <div class="col-auto">
                                    <div class="sw-3 d-inline-block d-flex justify-content-start align-items-center h-100">
                                        <div class="sh-3">
                                            <i data-acorn-icon="hexagon" class="text-tertiary align-top"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div
                                        class="card-body d-flex flex-column pt-0 pb-0 ps-3 pe-4 h-100 justify-content-center">
                                        <div class="d-flex flex-column">
                                            <div class="text-alternate mt-n1 lh-1-25">
                                                New sale:
                                                <a href="#" class="alternate-link underline-link">Steirer Brot</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-inline-block d-flex justify-content-end align-items-center h-100">
                                        <div class="text-muted ms-2 mt-n1 lh-1-25">15 Dec</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0 mb-2">
                                <div class="col-auto">
                                    <div class="sw-3 d-inline-block d-flex justify-content-start align-items-center h-100">
                                        <div class="sh-3">
                                            <i data-acorn-icon="hexagon" class="text-tertiary align-top"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div
                                        class="card-body d-flex flex-column pt-0 pb-0 ps-3 pe-4 h-100 justify-content-center">
                                        <div class="d-flex flex-column">
                                            <div class="text-alternate mt-n1 lh-1-25">
                                                New sale:
                                                <a href="#" class="alternate-link underline-link">Soda Bread</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-inline-block d-flex justify-content-end align-items-center h-100">
                                        <div class="text-muted ms-2 mt-n1 lh-1-25">15 Dec</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0 mb-2">
                                <div class="col-auto">
                                    <div class="sw-3 d-inline-block d-flex justify-content-start align-items-center h-100">
                                        <div class="sh-3">
                                            <i data-acorn-icon="triangle" class="text-warning align-top"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div
                                        class="card-body d-flex flex-column pt-0 pb-0 ps-3 pe-4 h-100 justify-content-center">
                                        <div class="d-flex flex-column">
                                            <div class="text-alternate mt-n1 lh-1-25">Recived a support ticket</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-inline-block d-flex justify-content-end align-items-center h-100">
                                        <div class="text-muted ms-2 mt-n1 lh-1-25">14 Dec</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0 mb-2">
                                <div class="col-auto">
                                    <div class="sw-3 d-inline-block d-flex justify-content-start align-items-center h-100">
                                        <div class="sh-3">
                                            <i data-acorn-icon="hexagon" class="text-tertiary align-top"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div
                                        class="card-body d-flex flex-column pt-0 pb-0 ps-3 pe-4 h-100 justify-content-center">
                                        <div class="d-flex flex-column">
                                            <div class="text-alternate mt-n1 lh-1-25">
                                                New sale:
                                                <a href="#" class="alternate-link underline-link">Cholermüs</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-inline-block d-flex justify-content-end align-items-center h-100">
                                        <div class="text-muted ms-2 mt-n1 lh-1-25">13 Dec</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0 mb-2">
                                <div class="col-auto">
                                    <div class="sw-3 d-inline-block d-flex justify-content-start align-items-center h-100">
                                        <div class="sh-3">
                                            <i data-acorn-icon="hexagon" class="text-tertiary align-top"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div
                                        class="card-body d-flex flex-column pt-0 pb-0 ps-3 pe-4 h-100 justify-content-center">
                                        <div class="d-flex flex-column">
                                            <div class="text-alternate mt-n1 lh-1-25">
                                                New sale:
                                                <a href="#" class="alternate-link underline-link">Bazlama</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-inline-block d-flex justify-content-end align-items-center h-100">
                                        <div class="text-muted ms-2 mt-n1 lh-1-25">13 Dec</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0 mb-2">
                                <div class="col-auto">
                                    <div class="sw-3 d-inline-block d-flex justify-content-start align-items-center h-100">
                                        <div class="sh-3">
                                            <i data-acorn-icon="triangle" class="text-warning align-top"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div
                                        class="card-body d-flex flex-column pt-0 pb-0 ps-3 pe-4 h-100 justify-content-center">
                                        <div class="d-flex flex-column">
                                            <div class="text-alternate mt-n1 lh-1-25">Recived a comment</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-inline-block d-flex justify-content-end align-items-center h-100">
                                        <div class="text-muted ms-2 mt-n1 lh-1-25">13 Dec</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0 mb-2">
                                <div class="col-auto">
                                    <div class="sw-3 d-inline-block d-flex justify-content-start align-items-center h-100">
                                        <div class="sh-3">
                                            <i data-acorn-icon="triangle" class="text-warning align-top"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div
                                        class="card-body d-flex flex-column pt-0 pb-0 ps-3 pe-4 h-100 justify-content-center">
                                        <div class="d-flex flex-column">
                                            <div class="text-alternate mt-n1 lh-1-25">Recived an email</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-inline-block d-flex justify-content-end align-items-center h-100">
                                        <div class="text-muted ms-2 mt-n1 lh-1-25">13 Dec</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0 mb-2">
                                <div class="col-auto">
                                    <div class="sw-3 d-inline-block d-flex justify-content-start align-items-center h-100">
                                        <div class="sh-3">
                                            <i data-acorn-icon="hexagon" class="text-tertiary align-top"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div
                                        class="card-body d-flex flex-column pt-0 pb-0 ps-3 pe-4 h-100 justify-content-center">
                                        <div class="d-flex flex-column">
                                            <div class="text-alternate mt-n1 lh-1-25">
                                                New sale:
                                                <a href="#" class="alternate-link underline-link">Bazlama</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-inline-block d-flex justify-content-end align-items-center h-100">
                                        <div class="text-muted ms-2 mt-n1 lh-1-25">12 Dec</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0 mb-2">
                                <div class="col-auto">
                                    <div class="sw-3 d-inline-block d-flex justify-content-start align-items-center h-100">
                                        <div class="sh-3">
                                            <i data-acorn-icon="triangle" class="text-warning align-top"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div
                                        class="card-body d-flex flex-column pt-0 pb-0 ps-3 pe-4 h-100 justify-content-center">
                                        <div class="d-flex flex-column">
                                            <div class="text-alternate mt-n1 lh-1-25">Recived a comment</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-inline-block d-flex justify-content-end align-items-center h-100">
                                        <div class="text-muted ms-2 mt-n1 lh-1-25">12 Dec</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Logs End -->

            <!-- Progress Start -->
            <div class="col-xl-6 mb-5">
                <h2 class="small-title">Progress</h2>
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <div class="card sh-13">
                            <div class="card-body py-0 d-flex">
                                <div class="row g-0 d-flex w-100 align-items-center">
                                    <div
                                        class="col sh-6 d-flex flex-column justify-content-center custom-legend-container">
                                    </div>
                                    <template class="custom-legend-item">
                                        <div class="text-small text-muted text mt-2"></div>
                                        <div class="cta-3 text-primary value"></div>
                                    </template>
                                    <div class="col-auto">
                                        <canvas id="smallDoughnutChart1" class="sw-7 sh-7"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card sh-13">
                            <div class="card-body py-0 d-flex">
                                <div class="row g-0 d-flex w-100 align-items-center">
                                    <div
                                        class="col sh-7 d-flex flex-column justify-content-center custom-legend-container">
                                    </div>
                                    <template class="custom-legend-item">
                                        <div class="text-small text-muted text mt-2"></div>
                                        <div class="cta-3 text-primary value"></div>
                                    </template>
                                    <div class="col-auto">
                                        <canvas id="smallDoughnutChart2" class="sw-7 sh-7"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card sh-13">
                            <div class="card-body py-0 d-flex">
                                <div class="row g-0 d-flex w-100 align-items-center">
                                    <div
                                        class="col sh-7 d-flex flex-column justify-content-center custom-legend-container">
                                    </div>
                                    <template class="custom-legend-item">
                                        <div class="text-small text-muted text mt-2"></div>
                                        <div class="cta-3 text-primary value"></div>
                                    </template>
                                    <div class="col-auto">
                                        <canvas id="smallDoughnutChart3" class="sw-7 sh-7"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card sh-13">
                            <div class="card-body py-0 d-flex">
                                <div class="row g-0 d-flex w-100 align-items-center">
                                    <div
                                        class="col sh-7 d-flex flex-column justify-content-center custom-legend-container">
                                    </div>
                                    <template class="custom-legend-item">
                                        <div class="text-small text-muted text mt-2"></div>
                                        <div class="cta-3 text-primary value"></div>
                                    </template>
                                    <div class="col-auto">
                                        <canvas id="smallDoughnutChart4" class="sw-7 sh-7"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card sh-13">
                            <div class="card-body py-0 d-flex">
                                <div class="row g-0 d-flex w-100 align-items-center">
                                    <div
                                        class="col sh-7 d-flex flex-column justify-content-center custom-legend-container">
                                    </div>
                                    <template class="custom-legend-item">
                                        <div class="text-small text-muted text mt-2"></div>
                                        <div class="cta-3 text-primary value"></div>
                                    </template>
                                    <div class="col-auto">
                                        <canvas id="smallDoughnutChart5" class="sw-7 sh-7"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card sh-13">
                            <div class="card-body py-0 d-flex">
                                <div class="row g-0 d-flex w-100 align-items-center">
                                    <div
                                        class="col sh-7 d-flex flex-column justify-content-center custom-legend-container">
                                    </div>
                                    <template class="custom-legend-item">
                                        <div class="text-small text-muted text mt-2"></div>
                                        <div class="cta-3 text-primary value"></div>
                                    </template>
                                    <div class="col-auto">
                                        <canvas id="smallDoughnutChart6" class="sw-7 sh-7"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Progress End -->
        </div>


    </div>
@endsection
