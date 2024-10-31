@extends('front.layouts.app')

@section('main')
<section class="section-3 py-5 bg-2 ">
    <div class="container">     
        <div class="row">
            <div class="col-6 col-md-10 ">
                <h2>Tìm việc làm</h2>  
            </div>
            <div class="col-6 col-md-2">
                <div class="align-end">
                    <select name="sort" id="sort" class="form-control">
                        <option value="1" {{ (Request::get('sort') == '1') ? 'selected' : '' }}>Mới nhất</option>
                        <option value="0" {{ (Request::get('sort') == '0') ? 'selected' : '' }}>Cũ nhất</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row pt-5">
            
            <div class="col-md-4 col-lg-3 sidebar mb-4">
                <form action="" name="searchForm" id="searchForm">
                    <div class="card border-0 shadow p-4">
                        <div class="mb-4">
                            <h2>Từ khóa</h2>
                            <input value="{{ Request::get('keyword') }}" type="text" name="keyword" id="keyword" placeholder="Từ khóa công việc" class="form-control">
                        </div>

                        <div class="mb-4">
                            <h2>Vị trí công việc</h2>
                            <input value="{{ Request::get('location') }}" type="text" name="location" id="location" placeholder="Location" class="form-control">
                        </div>

                        <div class="mb-4">
                            <h2>Loại</h2>
                            <select name="category" id="category" class="form-control">
                                <option value="">Lựa chọn danh mục</option>
                                @if ($categories)
                                    @foreach ($categories as $category)
                                    <option {{ (Request::get('category') == $category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>  
                                    @endforeach
                                @endif                            
                            </select>
                        </div>                   

                        <div class="mb-4">
                            <h2>Thời gian công việc</h2>
                            
                            @if ($jobTypes->isNotEmpty())
                                @foreach ($jobTypes as $jobType)
                                <div class="form-check mb-2"> 
                                    <input {{ (in_array($jobType->id,$jobTypeArray)) ? 'checked' : ''}} class="form-check-input " name="job_type" type="checkbox" value="{{ $jobType->id }}" id="job-type-{{ $jobType->id }}">    
                                    <label class="form-check-label " for="job-type-{{ $jobType->id }}">{{ $jobType->name }}</label>
                                </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="mb-4">
                            <h2>Kinh nghiệm</h2>
                            <select name="experience" id="experience" class="form-control">
                                <option value="">Kinh Nghiệm</option>
                                <option value="1" {{ (Request::get('experience') == 1) ? 'selected' : '' }}>1 Năm</option>
                                <option value="2" {{ (Request::get('experience') == 2) ? 'selected' : ''  }}>2 Năm</option>
                                <option value="3" {{ (Request::get('experience') == 3) ? 'selected' : ''  }}>3 Năm</option>
                                <option value="4" {{ (Request::get('experience') == 4) ? 'selected' : ''  }}>4 Năm</option>
                                <option value="5" {{ (Request::get('experience') == 5) ? 'selected' : ''  }}>5 Năm</option>
                                <option value="6" {{ (Request::get('experience') == 6) ? 'selected' : ''  }}>6 Năm</option>
                                <option value="7" {{ (Request::get('experience') == 7) ? 'selected' : ''  }}>7 Năm</option>
                                <option value="8" {{ (Request::get('experience') == 8) ? 'selected' : '' }}>8 Năm</option>
                                <option value="9" {{ (Request::get('experience') == 9)  ? 'selected' : '' }}>9 Năm</option>
                                <option value="10" {{ (Request::get('experience') == 10) ? 'selected' : ''  }}>10 Năm</option>
                                <option value="10_plus" {{ (Request::get('experience') == '10_plus') ? 'selected' : ''  }}>Trên 10 Năm</option>
                            </select>
                        </div> 
                        
                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        <a href="{{ route("jobs") }}" class="btn btn-secondary mt-3">Reset</a>
                    </div>
                </form>
            </div>
            <div class="col-md-8 col-lg-9 ">
                <div class="job_listing_area">                    
                    <div class="job_lists">
                        <div class="row">
                            @if ($jobs->isNotEmpty())
                                @foreach ($jobs as $job)
                                <div class="col-md-4">
                                    <div class="card border-0 p-3 shadow mb-4">
                                        <div class="card-body">
                                            <h3 class="border-0 fs-5 pb-2 mb-0">{{ $job->title }}</h3>
                                            
                                            <p>{{ Str::words(strip_tags($job->description), $words=10, '...') }}</p>

                                            <div class="bg-light p-3 border">
                                                <p class="mb-0">
                                                    <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                                    <span class="ps-1">{{ $job->location }}</span>
                                                </p>
                                                <p class="mb-0">
                                                    <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                    <span class="ps-1">{{ $job->jobType->name }}</span>
                                                </p>
                                                {{-- <p>Keywords: {{ $job->keywords }}</p>
                                                <p>Category: {{ $job->category->name }}</p>
                                                <p>Experience: {{ $job->experience }}</p> --}}
                                                @if (!is_null($job->salary))
                                                <p class="mb-0">
                                                    <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                    <span class="ps-1">{{ $job->salary }}</span>
                                                </p> 
                                                @endif                                                
                                            </div>
    
                                            <div class="d-grid mt-3">
                                                <a href="{{ route('jobDetail',$job->id) }}" class="btn btn-primary btn-lg">Chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <div class="col-md-12">
                                    {{ $jobs->withQueryString()->links() }}
                                </div>
                            @else
                            <div class="col-md-12">Không tìm thấy việc làm</div>                                
                            @endif                           
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>
    $("#searchForm").submit(function(e){
        e.preventDefault();

        var url = '{{ route("jobs") }}?';

        var keyword = $("#keyword").val();
        var location = $("#location").val();
        var category = $("#category").val();
        var experience = $("#experience").val();
        var sort = $("#sort").val();

        var checkedJobTypes = $("input:checkbox[name='job_type']:checked").map(function(){
            return $(this).val();
        }).get();

        // If keyword has a value
        if (keyword != "") {
            url += '&keyword='+keyword;
        }

        // If location has a value
        if (location != "") {
            url += '&location='+location;
        }

        // If category has a value
        if (category != "") {
            url += '&category='+category;
        }

        // If experience has a value
        if (experience != "") {
            url += '&experience='+experience;
        }

        // If user has checked job types
        if (checkedJobTypes.length > 0) {
            url += '&jobType='+checkedJobTypes;
        }

        url += '&sort='+sort;

        window.location.href=url;
        
    });
    // Recomment cho từ khóa
    $(document).ready(function() {
        $("#keyword").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('job.suggestions') }}", // Sử dụng route để gọi controller
                    dataType: "json",
                    data: {
                        term: request.term, // Từ khóa người dùng nhập
                        type: 'keyword' // Xác định là từ khóa
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
        });
    // Recomment cho vị trí 
        $("#location").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: '{{ route('job.suggestions') }}',
                    dataType: 'json',
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        // Tạo danh sách gợi ý
                        response($.map(data, function(item) {
                            return {
                                label: item.location, // hiển thị vị trí công việc
                                value: item.location  // giá trị khi chọn gợi ý
                            };
                        }));
                    }
                });
            },
            minLength: 1, // Bắt đầu gợi ý sau khi nhập ít nhất 2 ký tự
        });
    
    $("#sort").change(function(){
        $("#searchForm").submit();
    });

</script>
@endsection