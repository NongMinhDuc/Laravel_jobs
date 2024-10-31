<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    // Hiển thị trang đăng kí người dùng
    public function registration() {
        return view('front.account.registration');
    }

    // Phương thức đăng kí tài khoản
    public function processRegistration(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required',
        ]);

        if ($validator->passes()) {

            $user = new User();     // Tạo 1 đối tượng mới 
            $user->name = $request->name;       // Gán giá trị từ form vào các thuộc tính 
            $user->email = $request->email;     // Gán giá trị từ form vào các thuộc tính 
            $user->password = Hash::make($request->password);
            $user->name = $request->name;
            $user->save();

            session()->flash('success','You have registerd successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    // Hiển thị trang đăng nhập
    public function login() {
        return view('front.account.login');
    }
    // Kiểm tra thông tin đăng nhập và đăng nhập người dùng nếu thông tin đăng nhập hợp lệ
    public function authenticate(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->passes()) {
            
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('account.profile');
            } else {
                return redirect()->route('account.login')->with('error','Either Email/Password is incorrect');
            }
        } else {
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }
    // Hiển thị trang thông tin cá nhân
    public function profile() {

        
        $id = Auth::user()->id;

        $user = User::where('id',$id)->first();

        return view('front.account.profile',[
            'user' => $user
        ]);
    }
    // xử lý yêu cầu cập nhật thông tin cá nhân
    public function updateProfile(Request $request) {

        $id = Auth::user()->id;

        $validator = Validator::make($request->all(),[
            'name' => 'required|min:5|max:20',
            'email' => 'required|email|unique:users,email,'.$id.',id',
            'benefits' => 'nullable|string|max:1000'
        ]);


        if ($validator->passes()) {

            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->designation = $request->designation;
            $user->benefits = $request->benefits;
            $user->save();

            session()->flash('success','Profile updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }
    // phương thức đăng xuất người dùng
    public function logout() {
        Auth::logout();
        return redirect()->route('account.login');
    }
    // Phương thức cho phép người dùng cập nhật ảnh đại diện
    public function updateProfilePic(Request $request) {
        //dd($request->all());

        $id = Auth::user()->id;

        $validator = Validator::make($request->all(),[
            'image' => 'required|image'
        ]);

        if ($validator->passes()) {

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id.'-'.time().'.'.$ext;
            $image->move(public_path('/profile_pic/'), $imageName);


            // Create a small thumbnail
            $sourcePath = public_path('/profile_pic/'.$imageName);
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($sourcePath);

            // crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
            $image->cover(150, 150);
            $image->toPng()->save(public_path('/profile_pic/thumb/'.$imageName));

            // Delete Old Profile Pic
            File::delete(public_path('/profile_pic/thumb/'.Auth::user()->image));
            File::delete(public_path('/profile_pic/'.Auth::user()->image));

            User::where('id',$id)->update(['image' => $imageName]);

            session()->flash('success','Profile picture updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    // Phương thức hiển thị trang tạo công việc 
    public function createJob() {

        $categories = Category::orderBy('name','ASC')->where('status',1)->get();

        $jobTypes = JobType::orderBy('name','ASC')->where('status',1)->get();
        
        return view('front.account.job.create',[
            'categories' =>  $categories,
            'jobTypes' =>  $jobTypes,
        ]);
    }
    // Phương thức lưu công việc vào CSDL
    public function saveJob(Request $request) {

        $rules = [
            'title' => 'required|min:5|max:200',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'company_name' => 'required|min:3|max:75',          

        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {

            $job = new Job();
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id  = $request->jobType;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->website;
            $job->save();

            session()->flash('success','Job added successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    // Phương thức trả về danh sách công việc mà người dùng đã đăng
    public function myJobs() {    
        $jobs = Job::where('user_id',Auth::user()->id)->with('jobType')

                    ->orderBy('created_at','DESC')->paginate(10);        
        return view('front.account.job.my-jobs',[
            'jobs' => $jobs
        ]);
    }  
    // Hiển thị trang chỉnh sửa công việc và chỉnh sửa công việc
    public function editJob(Request $request, $id) {
        
        $categories = Category::orderBy('name','ASC')->where('status',1)->get();
        $jobTypes = JobType::orderBy('name','ASC')->where('status',1)->get();

        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $id
        ])->first();

        if ($job == null) {
            abort(404);
        }

        return view('front.account.job.edit',[
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'job' => $job,
        ]);
    }
    // Xử lý cập nhật thông tin công việc đã đăng 
    public function updateJob(Request $request, $id) {

        $rules = [
            'title' => 'required|min:5|max:200',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'company_name' => 'required|min:3|max:75',          

        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {

            $job = Job::find($id);
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id  = $request->jobType;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->website;
            $job->save();

            session()->flash('success','Job updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    // Phương thức xử lý yêu cầu xóa công việc 
    public function deleteJob(Request $request) {

        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $request->jobId
        ])->first();


        if ($job == null) {
            session()->flash('error','Either job deleted or not found.');
            return response()->json([
                'status' => true
            ]);
        }

        Job::where('id',$request->jobId)->delete();
        session()->flash('success','Job deleted successfully.');
        return response()->json([
            'status' => true
        ]);

    }
    // Phương thức trả về danh sách các công việc mà người dùng đã ứng tuyển 
    public function myJobApplications(){
        $jobApplications = JobApplication::where('user_id',Auth::user()->id)
                ->with(['job','job.jobType','job.applications'])
                ->orderBy('created_at','DESC')
                ->paginate(10);

        return view('front.account.job.my-job-applications',[
            'jobApplications' => $jobApplications
        ]);
    }
    // Phương thức cho phép người dùng xóa đơn ứng tuyển 
    public function removeJobs(Request $request){
        $jobApplication = JobApplication::where([
                                    'id' => $request->id, 
                                    'user_id' => Auth::user()->id]
                                )->first();
        
        if ($jobApplication == null) {
            session()->flash('error','Job application not found');
            return response()->json([
                'status' => false,                
            ]);
        }

        JobApplication::find($request->id)->delete();
        session()->flash('success','Job application removed successfully.');

        return response()->json([
            'status' => true,                
        ]);

    }
    // Trả về danh sách công việc mà người dùng đã lưu
    public function savedJobs(){
        // $jobApplications = JobApplication::where('user_id',Auth::user()->id)
        //         ->with(['job','job.jobType','job.applications'])
        //         ->paginate(10);

        $savedJobs = SavedJob::where([
            'user_id' => Auth::user()->id
        ])->with(['job','job.jobType','job.applications'])
        ->orderBy('created_at','DESC')
        ->paginate(10);

        return view('front.account.job.saved-jobs',[
            'savedJobs' => $savedJobs
        ]);
    }
    // Phương thức cho phép người dùng xóa công việc đã lưu
    public function removeSavedJob(Request $request){
        $savedJob = SavedJob::where([
                                    'id' => $request->id, 
                                    'user_id' => Auth::user()->id]
                                )->first();
        
        if ($savedJob == null) {
            session()->flash('error','Job not found');
            return response()->json([
                'status' => false,                
            ]);
        }

        SavedJob::find($request->id)->delete();
        session()->flash('success','Job removed successfully.');

        return response()->json([
            'status' => true,                
        ]);

    }
    // Phương thức đổi mật khẩu
    public function updatePassword(Request $request){
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        if (Hash::check($request->old_password, Auth::user()->password) == false){
            session()->flash('error','Your old password is incorrect.');
            return response()->json([
                'status' => true                
            ]);
        }


        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($request->new_password);  
        $user->save();

        session()->flash('success','Password updated successfully.');
        return response()->json([
            'status' => true                
        ]);

    }
    // Phương thức hiển thị trang quên mật khẩu 
    public function forgotPassword() {
        return view('front.account.forgot-password');
    }
    // Phương thức xử lý yêu cầu lấy lại mật khẩu bằng cách sử dụng email
    public function processForgotPassword(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.forgotPassword')->withInput()->withErrors($validator);
        }

        $token = Str::random(60);

        \DB::table('password_reset_tokens')->where('email',$request->email)->delete();

        \DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        // Send Email here
        $user = User::where('email',$request->email)->first();
        $mailData =  [
            'token' => $token,
            'user' => $user,
            'subject' => 'You have requested to change your password.'
        ];

        Mail::to($request->email)->send(new ResetPasswordEmail($mailData));

        return redirect()->route('account.forgotPassword')->with('success','Reset password email has been sent to your inbox.');
        
    }
    // Phương thức đặt lại mật khẩu
    public function resetPassword($tokenString) {
        $token = \DB::table('password_reset_tokens')->where('token',$tokenString)->first();

        if ($token == null) {
            return redirect()->route('account.forgotPassword')->with('error','Invalid token.');
        }

        return view('front.account.reset-password',[
            'tokenString' => $tokenString
        ]);
    }
    // Phương thức đặt lại mật khẩu 
    public function processResetPassword(Request $request) {

        $token = \DB::table('password_reset_tokens')->where('token',$request->token)->first();

        if ($token == null) {
            return redirect()->route('account.forgotPassword')->with('error','Invalid token.');
        }
        
        $validator = Validator::make($request->all(),[
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.resetPassword',$request->token)->withErrors($validator);
        }

        User::where('email',$token->email)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('account.login')->with('success','You have successfully changed your password.');

    }
}
