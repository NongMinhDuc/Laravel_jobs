@extends('front.layouts.app')

@section('main')
<section class="section-0 lazy d-flex bg-image-style dark align-items-center" data-bg="{{ asset('assets/images/banner7.jpg') }}">
    <div class="overlay"></div>
    <div class="container text-white text-center">
        <div class="row">
            <div class="col-12 col-xl-8 mx-auto">
                <h1 class="display-4">Tìm công việc mong muốn của bạn</h1>
                <p class="lead">Có rất nhiều công việc bạn mơ ước ở đây</p>
                <div class="banner-btn mt-5">
                    <a href="#" class="btn btn-primary btn-lg">Khám phá ngay</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-1 py-5 bg-light"> 
    <div class="container">
        <div class="card border-0 shadow-lg p-4 rounded">
            <form action="{{ route('jobs') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="text" class="form-control" name="keyword" placeholder="Từ khóa">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                            <input type="text" class="form-control" name="location" placeholder="Vị trí">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">Danh mục công việc</option>
                            @if ($newCategories->isNotEmpty())
                                @foreach ($newCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>  
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                    </div>
                </div> 
            </form>           
        </div>
    </div>
</section>

<section class="section-2 bg-white py-5">
    <div class="container">
        <h2 class="text-center mb-5">Danh mục phổ biến</h2>
        <div class="row g-4">
            @if ($categories->isNotEmpty())
                @foreach ($categories as $category)
                    <div class="col-lg-4 col-xl-3 col-md-6">
                        <div class="card border-0 shadow-sm p-4 text-center category-card h-100">
                            <a href="{{ route('jobs').'?category='.$category->id }}" class="text-decoration-none">
                                <h4 class="text-primary">{{ $category->name }}</h4>
                            </a>
                            <p class="text-muted"> <span>0</span> Vị trí sẵn có</p>
                        </div>
                    </div>
                @endforeach                
            @endif
        </div>
    </div>
</section>

<section class="section-3 py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Công việc nổi bật</h2>
        <div class="row g-4">
            @if ($featuredJobs->isNotEmpty())
                @foreach ($featuredJobs as $featuredJob)
                    <div class="col-md-4">
                        <div class="card border-0 shadow-lg p-4 h-100">
                            <h3 class="fs-5">{{ $featuredJob->title }}</h3>
                            <p class="text-muted">{{ Str::words(strip_tags($featuredJob->description), 5) }}</p>
                            <div class="bg-light p-3 rounded border mt-3">
                                <p class="mb-2">
                                    <i class="fa fa-map-marker text-primary"></i> {{ $featuredJob->location }}
                                </p>
                                <p class="mb-2">
                                    <i class="fa fa-clock-o text-primary"></i> {{ $featuredJob->jobType->name }}
                                </p>
                                @if (!is_null($featuredJob->salary))
                                    <p class="mb-0">
                                        <i class="fa fa-usd text-primary"></i> {{ $featuredJob->salary }}
                                    </p>
                                @endif
                            </div>
                            <a href="{{ route('jobDetail', $featuredJob->id) }}" class="btn btn-primary mt-4 w-100">Chi tiết</a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

<section class="section-4 py-5">
    <div class="container">
        <h2 class="text-center mb-5">Việc làm mới nhất</h2>
        <div class="row g-4">
            @if ($latestJobs->isNotEmpty())
                @foreach ($latestJobs as $latestJob)
                    <div class="col-md-4">
                        <div class="card border-0 shadow-lg p-4 h-100">
                            <h3 class="fs-5">{{ $latestJob->title }}</h3>
                            <p class="text-muted">{{ Str::words(strip_tags($latestJob->description), 5) }}</p>
                            <div class="bg-light p-3 rounded border mt-3">
                                <p class="mb-2">
                                    <i class="fa fa-map-marker text-primary"></i> {{ $latestJob->location }}
                                </p>
                                <p class="mb-2">
                                    <i class="fa fa-clock-o text-primary"></i> {{ $latestJob->jobType->name }}
                                </p>
                                @if (!is_null($latestJob->salary))
                                    <p class="mb-0">
                                        <i class="fa fa-usd text-primary"></i> {{ $latestJob->salary }}
                                    </p>
                                @endif
                            </div>
                            <a href="{{ route('jobDetail', $latestJob->id) }}" class="btn btn-primary mt-4 w-100">Chi tiết</a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

<style>
    .bg-image-style {
        position: relative;
        background-size: cover;
        background-position: center;
        min-height: 400px;
    }
    .bg-image-style .overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
    }
    .category-card:hover {
        background: #f8f9fa;
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }
    .btn-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #0056b3, #003580);
    }
</style>
@endsection
