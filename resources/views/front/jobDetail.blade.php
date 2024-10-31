@extends('front.layouts.app')

@section('main')
<section class="section-4 bg-2">    
    <div class="container pt-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('jobs') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Quay lại</a></li>
                    </ol>
                </nav>
            </div>
        </div> 
    </div>
    <div class="container job_details_area">
        <div class="row pb-5">
            <div class="col-md-8">
                @include('front.message')
                <div class="card shadow border-0">
                    <div class="job_details_header">
                        <div class="single_jobs white-bg d-flex justify-content-between">
                            <div class="jobs_left d-flex align-items-center">
                                <div class="jobs_conetent">
                                    <a href="#">
                                        <h4>{{ $job->title }}</h4>
                                    </a>
                                    <div class="links_locat d-flex align-items-center">
                                        <div class="location">
                                            <p> <i class="fa fa-map-marker"></i> {{ $job->location }}</p>
                                        </div>
                                        <div class="location">
                                            <p> <i class="fa fa-clock-o"></i> {{ $job->jobType->name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="jobs_right">
                                <div class="apply_now {{ ($count == 1) ? 'saved-job' : '' }}">
                                    <a class="heart_mark " href="javascript:void(0);" onclick="saveJob({{ $job->id }})"> <i class="fa fa-heart-o" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="descript_wrap white-bg">
                        <div class="single_wrap">
                            <h4>Mô tả công việc</h4>
                            {!! nl2br($job->description) !!}
                        </div>
                        @if (!empty($job->responsibility))
                        <div class="single_wrap">
                            <h4>Trách nhiệm với công việc</h4>
                            {!! nl2br($job->responsibility) !!}
                        </div>
                        @endif
                        @if (!empty($job->qualifications))
                        <div class="single_wrap">
                            <h4>Trình độ chuyên môn</h4>
                            {!! nl2br($job->qualifications) !!}
                        </div>
                        @endif
                        @if (!empty($job->benefits))
                        <div class="single_wrap">
                            <h4>Những lợi ích</h4>
                            {!! nl2br($job->benefits) !!}
                        </div>
                        @endif
                        <div class="border-bottom"></div>
                        <div class="pt-3 text-end">
                            @if (Auth::check())
                                <a href="#" onclick="saveJob({{ $job->id }});" class="btn btn-secondary">Lưu</a>  
                            @else
                                <a href="javascript:void(0);" class="btn btn-secondary disabled">Login to Save</a>
                            @endif

                            @if (Auth::check())
                                <a href="#" onclick="applyJob({{ $job->id }})" class="btn btn-primary">Áp dụng</a>
                            @else
                                <a href="javascript:void(0);" class="btn btn-primary disabled">Login to Apply</a>
                            @endif
                        </div>
                    </div>
                </div>

                @if (Auth::user())
                   @if (Auth::user()->id == $job->user_id)
                    <div class="card shadow border-0 mt-4">
                        <div class="job_details_header">
                            <div class="single_jobs white-bg d-flex justify-content-between">
                                <div class="jobs_left d-flex align-items-center">
                                    <div class="jobs_conetent">                                    
                                        <h4>Ứng viên</h4>                                    
                                    </div>
                                </div>
                                <div class="jobs_right"></div>
                            </div>
                        </div>
                        <div class="descript_wrap white-bg">
                            <table class="table table-striped">
                                <tr>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Ngày nộp dơn</th>
                                    <th>Mô tả cá nhân</th>
                                </tr>
                                @if ($applications->isNotEmpty())
                                    @foreach ($applications as $application)
                                    <tr>
                                        <td>{{ $application->user->name  }}</td>
                                        <td>{{ $application->user->email  }}</td>
                                        <td>{{ $application->user->mobile  }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($application->applied_date)->format('d M, Y') }}
                                        </td>
                                        <td>{{  $application->user->benefits }}</td>
                                    </tr> 
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="3">Không tìm thấy ứng viên</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    @endif 
                @endif
            </div>
            <div class="col-md-4">
                <div class="card shadow border-0">
                    <div class="job_sumary">
                        <div class="summery_header pb-1 pt-4">
                            <h3>Tóm tắt công việc</h3>
                        </div>
                        <div class="job_content pt-3">
                            <ul>
                                <li>Xuất bản: <span>{{ \Carbon\Carbon::parse($job->created_at)->format('d M, Y') }}</span></li>
                                <li>Số lượng tuyển dụng: <span>{{ $job->vacancy }}</span></li>
                                @if (!empty($job->salary))
                                <li>Lương: <span>{{ $job->salary }}</span></li>
                                @endif
                                <li>Vị trí: <span>{{ $job->location }}</span></li>
                                <li>Bản chất công việc: <span> {{ $job->jobType->name }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card shadow border-0 my-4">
                    <div class="job_sumary">
                        <div class="summery_header pb-1 pt-4">
                            <h3>Chi tiết công ty</h3>
                        </div>
                        <div class="job_content pt-3">
                            <ul>
                                <li>Tên Công Ty: <span>{{ $job->company_name }}</span></li>
                                @if (!empty($job->company_location))
                                <li>Vị trí: <span>{{ $job->company_location }}</span></li>
                                @endif
                                @if (!empty($job->company_website))
                                <li>Webite: <span><a href="{{ $job->company_website }}">{{ $job->company_website }}</a></span></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thêm khung chat ở đây -->
        <div class="chat_area" id="chat_area">
            <h2>Chat</h2>
            <div class="chat_body">
                <div class="message" id="message_area"></div>
                <input type="text" id="message_input" class="chat_input" placeholder="Nhập tin nhắn của bạn...">
                <button id="send_button">Gửi</button>
            </div>
        </div>
        <button id="toggle_chat_button">Chat</button>
        <!-- Kết thúc khung chat -->

    </div>
</section>

@endsection

@section('customJs')
<script type="text/javascript">
function applyJob(id){
    if (confirm("Are you sure you want to apply on this job?")) {
        $.ajax({
            url : '{{ route("applyJob") }}',
            type: 'post',
            data: {id:id},
            dataType: 'json',
            success: function(response) {
                window.location.href = "{{ url()->current() }}";
            } 
        });
    }
}

function saveJob(id){
    $.ajax({
        url : '{{ route("saveJob") }}',
        type: 'post',
        data: {id:id},
        dataType: 'json',
        success: function(response) {
            window.location.href = "{{ url()->current() }}";
        } 
    });
}

// Khai báo các hàm xử lý chat
const chatArea = document.getElementById('chat_area');
const toggleChatButton = document.getElementById('toggle_chat_button');
const messageInput = document.getElementById('message_input');
const messageArea = document.getElementById('message_area');
const sendButton = document.getElementById('send_button');

// Ẩn khung chat theo mặc định
chatArea.style.display = 'none';

// Xử lý sự kiện khi nhấn nút ẩn/hiện chat
toggleChatButton.onclick = function() {
    if (chatArea.style.display === 'none') {
        chatArea.style.display = 'block';
    } else {
        chatArea.style.display = 'none';
    }
};

// Gửi tin nhắn
sendButton.onclick = function() {
    const message = messageInput.value;
    const jobId = {{ $job->id }};
    const receiverId = {{ $job->user_id }}; // Người nhận là người đăng công việc

    if (message) {
        $.ajax({
            url: '{{ route("messages.send") }}', // Đảm bảo route này đúng
            type: 'POST',
            data: {
                job_id: jobId,
                receiver_id: receiverId,
                content: message,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Thêm tin nhắn vào giao diện
                    messageArea.innerHTML += `<div class="sent">${message}</div>`;
                    messageInput.value = ''; // Xóa nội dung ô nhập
                } else {
                    alert('Gửi tin nhắn thất bại. Vui lòng thử lại.');
                }
            },
            error: function() {
                alert('Đã xảy ra lỗi. Vui lòng thử lại.');
            }
        });
    }
};

///// 
function loadMessages() {
    const jobId = {{ $job->id }};

    $.ajax({
        url: `/messages/${jobId}`,
        type: 'GET',
        success: function(messages) {
            messageArea.innerHTML = ''; // Xóa nội dung cũ
            messages.forEach(function(message) {
                const cssClass = message.sender_id === {{ Auth::id() }} ? 'sent' : 'received';
                messageArea.innerHTML += `<div class="${cssClass}">${message.content}</div>`;
            });
        },
        error: function() {
            alert('Không thể tải tin nhắn. Vui lòng thử lại.');
        }
    });
}

// Gọi hàm loadMessages để tải tin nhắn khi trang được tải
loadMessages();


</script>
@endsection
