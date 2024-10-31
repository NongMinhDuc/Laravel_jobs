@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Account Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('front.account.sidebar')
            </div>
            <div class="col-lg-9">
                @include('front.message')

                <form action="" method="post" id="createJobForm" name="createJobForm">
                    <div class="card border-0 shadow mb-4 ">
                        <div class="card-body card-form p-4">
                            <h3 class="fs-4 mb-1">Chi Tiết Công Việc</h3>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="" class="mb-2">Tiêu đề <span class="req">*</span></label>
                                    <input type="text" placeholder="Chức danh công việc" id="title" name="title" class="form-control">
                                    <p></p>
                                </div>
                                <div class="col-md-6  mb-4">
                                    <label for="" class="mb-2">Danh mục công việc<span class="req">*</span></label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Chọn một danh mục</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="" class="mb-2">Loại công việc<span class="req">*</span></label>
                                    <select name="jobType" id="jobType" class="form-select">
                                        <option value="">Chọn loại công việc</option>
                                        @if ($jobTypes->isNotEmpty())
                                            @foreach ($jobTypes as $jobType)
                                            <option value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                                <div class="col-md-6  mb-4">
                                    <label for="" class="mb-2">Vị trí còn trống<span class="req">*</span></label>
                                    <input type="number" min="1" placeholder="Số lượng vị trí trống" id="vacancy" name="vacancy" class="form-control">
                                    <p></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Lương</label>
                                    <input type="text" placeholder="Lương" id="salary" name="salary" class="form-control">
                                </div>

                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Vị trí<span class="req">*</span></label>
                                    <input type="text" placeholder="Vị trí" id="location" name="location" class="form-control">
                                    <p></p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-2">Miêu tả công việc<span class="req">*</span></label>
                                <textarea class="textarea" name="description" id="description" cols="5" rows="5" placeholder="Description"></textarea>
                                <p></p>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Lợi ích khi bạn tham gia</label>
                                <textarea class="textarea" name="benefits" id="benefits" cols="5" rows="5" placeholder="Benefits"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Trách nhiệm với công việc</label>
                                <textarea class="textarea" name="responsibility" id="responsibility" cols="5" rows="5" placeholder="Responsibility"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Bằng cấp</label>
                                <textarea class="textarea" name="qualifications" id="qualifications" cols="5" rows="5" placeholder="Qualifications"></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-2">Kinh nghiệm<span class="req">*</span></label>
                                <select name="experience" id="experience" class="form-control">
                                    <option value="1">Chưa có kinh nghiệm</option>
                                    <option value="1">1 Năm</option>
                                    <option value="2">2 Năm</option>
                                    <option value="3">3 Năm</option>
                                    <option value="4">4 Năm</option>
                                    <option value="5">5 Năm</option>
                                    <option value="6">6 Năm</option>
                                    <option value="7">7 Năm</option>
                                    <option value="8">8 Năm</option>
                                    <option value="9">9 Năm</option>
                                    <option value="10">10 Năm</option>
                                    <option value="10_plus">Trên 10 Năm</option>
                                </select>
                                <p></p>
                            </div>
                            
                            

                            <div class="mb-4">
                                <label for="" class="mb-2">Từ khóa</label>
                                <input type="text" placeholder="Từ khóa công việc" id="keyword" name="keyword" class="form-control">
                            </div>

                            <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Chi tiết công ty</h3>

                            <div class="row">
                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Tên Công Ty<span class="req">*</span></label>
                                    <input type="text" placeholder="Tên công ty" id="company_name" name="company_name" class="form-control">
                                    <p></p>
                                </div>

                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Vị trí Công Ty</label>
                                    <input type="text" placeholder="Vị trí công việc" id="company_location" name="company_location" class="form-control">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-2">Website</label>
                                <input type="text" placeholder="Website công ty" id="website" name="website" class="form-control">
                            </div>
                        </div> 
                        <div class="card-footer  p-4">
                            <button type="submit" class="btn btn-primary">Lưu công việc</button>
                        </div>               
                    </div>
                </form>
                               
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script type="text/javascript">
$("#createJobForm").submit(function(e){
    e.preventDefault();
    $("button[type='submit']").prop('disabled',true);

    $.ajax({
        url: '{{ route("account.saveJob") }}',
        type: 'POST',
        dataType: 'json',
        data: $("#createJobForm").serializeArray(),
        success: function(response) {
            $("button[type='submit']").prop('disabled',false);

            if(response.status == true) {

                $("#title").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                $("#category").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                $("#jobType").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                $("#vacancy").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                $("#location").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')


                $("#description").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                $("#company_name").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                window.location.href="{{ route('account.myJobs') }}";

            } else {
                var errors = response.errors;

                if (errors.title) {
                    $("#title").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.title)
                } else {
                    $("#title").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.category) {
                    $("#category").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.category)
                } else {
                    $("#category").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.jobType) {
                    $("#jobType").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.jobType)
                } else {
                    $("#jobType").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.vacancy) {
                    $("#vacancy").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.vacancy)
                } else {
                    $("#vacancy").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.location) {
                    $("#location").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.location)
                } else {
                    $("#location").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.description) {
                    $("#description").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.description)
                } else {
                    $("#description").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.company_name) {
                    $("#company_name").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.company_name)
                } else {
                    $("#company_name").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }
            }

        }
    });
});
</script>
@endsection

