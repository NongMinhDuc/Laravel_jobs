<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobsController extends Controller
{
    // Phương pháp này sẽ hiển thị trang việc làm
    public function index(Request $request) {
        $categories = Category::where('status',1)->get();
        $jobTypes = JobType::where('status',1)->get();

        $jobs = Job::where('status',1);

        // Tìm kiếm bằng từ khóa
        if (!empty($request->keyword)) {
            $jobs = $jobs->where(function($query) use ($request) {
                $query->orWhere('title','like','%'.$request->keyword.'%');
                $query->orWhere('keywords','like','%'.$request->keyword.'%');
            });
        }

        // Tìm kiếm bằng vị trí     
        if(!empty($request->location)) {
            $jobs = $jobs->where('location',$request->location);
        }

        // Tìm kiếm bằng danh mục
        if(!empty($request->category)) {
            $jobs = $jobs->where('category_id',$request->category);
        }

        $jobTypeArray = [];
        // Tìm kiếm loại công việc
        if(!empty($request->jobType)) {
            $jobTypeArray = explode(',',$request->jobType);

            $jobs = $jobs->whereIn('job_type_id',$jobTypeArray);
        }

        // Tìm kiếm theo kinh nghiệm
        if(!empty($request->experience)) {
            $jobs = $jobs->where('experience',$request->experience);
        }


        $jobs = $jobs->with(['jobType','category']);

        if($request->sort == '0') {
            $jobs = $jobs->orderBy('created_at','ASC');
        } else {
            $jobs = $jobs->orderBy('created_at','DESC');
        }
        

        $jobs = $jobs->paginate(9);


        return view('front.jobs',[
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'jobs' => $jobs,
            'jobTypeArray' => $jobTypeArray
        ]);
    }

    // Hiển thị trang chi tiết công việc
    public function detail($id) {

        $job = Job::where([
                            'id' => $id, 
                            'status' => 1
                        ])->with(['jobType','category'])->first();
        
        if ($job == null) {
            abort(404);
        }

        $count = 0;
        if (Auth::user()) {
            $count = SavedJob::where([
                'user_id' => Auth::user()->id,
                'job_id' => $id
            ])->count();
        }
        

        // lấy ứng viên

        $applications = JobApplication::where('job_id',$id)->with('user')->get();


        return view('front.jobDetail',[ 'job' => $job,
                                        'count' => $count,
                                        'applications' => $applications
                                    ]);
    }

    public function applyJob(Request $request) {
        $id = $request->id;

        $job = Job::where('id',$id)->first();

        // Không tìm thấy công việc
        if ($job == null) {
            $message = 'Job does not exist.';
            session()->flash('error',$message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }

        // phương thức không cho phép nộp đơn vào công việc cá nhân
        $employer_id = $job->user_id;

        if ($employer_id == Auth::user()->id) {
            $message = 'You can not apply on your own job.';
            session()->flash('error',$message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }

        // Không thể nộp đơn xin việc 2 lần
        $jobApplicationCount = JobApplication::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id
        ])->count();
        
        if ($jobApplicationCount > 0) {
            $message = 'You already applied on this job.';
            session()->flash('error',$message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }

        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $employer_id;
        $application->applied_date = now();
        $application->save();


        // Gửi email thông báo cho nhà tuyển dụng
        $employer = User::where('id',$employer_id)->first();
        
        $mailData = [
            'employer' => $employer,
            'user' => Auth::user(),
            'job' => $job,
        ];

        Mail::to($employer->email)->send(new JobNotificationEmail($mailData));

        $message = 'You have successfully applied.';

        session()->flash('success',$message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    // Lưu công việc
    public function saveJob(Request $request) {

        $id = $request->id;

        $job = Job::find($id);

        if ($job == null) {
            session()->flash('error','Job not found');

            return response()->json([
                'status' => false,
            ]);
        }

        // Kiểm tra người dùng đã lưu thông công việc chưa
        $count = SavedJob::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id
        ])->count();

        if ($count > 0) {
            session()->flash('error','You already saved this job.');

            return response()->json([
                'status' => false,
            ]);
        }

        $savedJob = new SavedJob;
        $savedJob->job_id = $id;
        $savedJob->user_id = Auth::user()->id;
        $savedJob->save();

        session()->flash('success','You have successfully saved the job.');

        return response()->json([
            'status' => true,
        ]);

    }

    public function getSuggestions(Request $request)
{
    $keyword = $request->get('term'); // 'term' là tham số được gửi bởi jQuery UI Autocomplete

    // Lấy danh sách công việc từ bảng 'jobs' theo tiêu chí từ khóa và vị trí
    $jobs = Job::select('title', 'location')
                ->where('title', 'LIKE', '%' . $keyword . '%')
                ->orWhere('location', 'LIKE', '%' . $keyword . '%')
                ->limit(10) // Giới hạn kết quả trả về
                ->get();

    // Trả về dữ liệu theo định dạng JSON để Autocomplete có thể sử dụng
    return response()->json($jobs);
}

}
