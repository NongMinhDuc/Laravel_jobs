@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Trang Chủ</a></li>
                        <li class="breadcrumb-item active">Cài Đặt Tài Khoản</li>
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
                <div class="card border-0 shadow mb-4 p-3">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">Công việc đã ứng tuyển</h3>
                            </div>                           
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Tiêu đề</th>
                                        <th scope="col">Ngày nộp dơn</th>
                                        <th scope="col">Ứng viên</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Hoạt động</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if ($jobApplications->isNotEmpty())
                                        @foreach ($jobApplications as $jobApplication)
                                        <tr class="active">
                                            <td>
                                                <div class="job-name fw-500">{{ $jobApplication->job->title }}</div>
                                                <div class="info1">{{ $jobApplication->job->jobType->name }} . {{ $jobApplication->job->location }}</div>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($jobApplication->applied_date)->format('d M, Y') }}</td>
                                            <td>{{ $jobApplication->job->applications->count() }} Ứng viên</td>
                                            <td>
                                                @if ($jobApplication->job->status == 1)
                                                <div class="job-status text-capitalize">Hoạt động</div>
                                                @else
                                                <div class="job-status text-capitalize">Khóa</div>
                                                @endif                                    
                                            </td>
                                            <td>
                                                <div class="action-dots float-end">
                                                    <button href="#" class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="{{ route("jobDetail",$jobApplication->job_id) }}"> <i class="fa fa-eye" aria-hidden="true"></i> Xem</a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="removeJob({{ $jobApplication->id }})" ><i class="fa fa-trash" aria-hidden="true"></i> Xóa</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="5">Không có đơn xin việc</td>
                                    </tr>
                                    @endif
                                    
                                    
                                </tbody>                                
                            </table>
                        </div>
                        <div>
                            {{ $jobApplications->links() }}
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</section>
@endsection
@section('customJs')
<script type="text/javascript">   
function removeJob(id) {
    if (confirm("Are you sure you want to remove?")) {
        $.ajax({
            url : '{{ route("account.removeJobs") }}',
            type: 'post',
            data: {id: id},
            dataType: 'json',
            success: function(response) {
                window.location.href='{{ route("account.myJobApplications") }}';
            }
        });
    } 
}
</script>
@endsection