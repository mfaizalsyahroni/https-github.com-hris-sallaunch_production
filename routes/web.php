<?php

use App\Http\Controllers\CertificationController;
use App\Http\Controllers\OvertimeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\CctvController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\EmployeeSuggestionController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\LearningplanController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\IdeaController;



Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routes for Attendance page
    Route::get('/dashboard/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/clockin', [AttendanceController::class, 'clockIn'])->name('attendance.clockin');
    Route::post('/attendance/clockout', [AttendanceController::class, 'clockOut'])->name('attendance.clockout');
    Route::post('/attendance/logout', [AttendanceController::class, 'logout'])->name('attendance.logout');

    // Routes for employee management

    Route::get('/workers/create', [WorkerController::class, 'create'])->name('workers.create');
});


Route::get('new_employee/verify', [WorkerController::class, 'verify'])
    ->name('new_employee.verify');

Route::post('new_employee/verify', [WorkerController::class, 'verifyWorker'])
    ->name('new_employee.verifyWorker');



Route::get('new_employee/create', [WorkerController::class, 'create'])
    ->name('new_employee.create');

Route::post('/workers', [WorkerController::class, 'store'])
    ->name('workers.store');

Route::get('new_employee/profile/{worker}', [WorkerController::class, 'profile'])
    ->name('new_employee.profile');

Route::post('new_employee/allemployee', [WorkerController::class, 'allemployee'])
    ->name('view.allemployee');

Route::get('new_employee/allemployee', [WorkerController::class, 'allemployee'])
    ->name('view.allemployee');

Route::put('/workers/{employee_id}', [WorkerController::class, 'update'])
    ->name('workers.update');

Route::delete('/workers/{employee_id}', [WorkerController::class, 'destroy'])
    ->name('workers.destroy');    

Route::post('new_employee/logout', [WorkerController::class, 'logout'])
    ->name('new_employee.logout');

// 1. HRIS ADMIN - without auth middleware (direct access)
Route::get('/leave/admin', [LeaveController::class, 'adminIndex'])->name('leave.admin');
Route::post('/leave/{id}/approve', [LeaveController::class, 'approveLeave'])->name('leave.approve');
Route::post('/leave/{id}/reject', [LeaveController::class, 'rejectLeave'])->name('leave.reject');

// 2. USER LEAVE - independent verification (without auth)
Route::get('/leave/create', [LeaveController::class, 'create'])->name('leave.create');
Route::post('/leave/verify', [LeaveController::class, 'verifyWorker'])->name('leave.verify');
Route::get('/leave/next', [LeaveController::class, 'next'])->name('leave.next');
Route::post('/leave/store', [LeaveController::class, 'store'])->name('leave.store');

Route::get('/leave/logout', [LeaveController::class, 'logout'])
    ->name('leave.logout');

// 3. CLEAR SESSION - must be above dynamic routes to avoid conflict
Route::get('/leave/clear-session', function () {
    session()->forget('verified_worker');
    return redirect()->route('home')->with('message', 'Session successfully cleared.');
})->name('leave.clearSession');

// 4. LEAVE PDF - make sure this is above dynamic {id} route
Route::get('/leave/{id}/pdf', [LeaveController::class, 'generatePDF'])->name('leave.pdf');

// 5. LEAVE DETAIL - place at the bottom to avoid collision
Route::get('/leave/{id}', [LeaveController::class, 'show'])->name('leave.show');



Route::prefix('news')->group(function () {
    // Worker verification
    Route::get('/verify', [NewsController::class, 'verifyForm'])->name('news.verifyForm');
    Route::post('/verify', [NewsController::class, 'verifyWorker'])->name('news.verifyWorker');

    // List all news
    Route::get('/list', [NewsController::class, 'allNewsList'])->name('news.list');
    Route::post('/logout', [NewsController::class, 'logout'])->name('news.logout');


    // Admin
    Route::prefix('admin')->group(function () {
        Route::get('/', [NewsController::class, 'adminDashboard'])->name('news.admin.dashboard');
        Route::get('/create', [NewsController::class, 'create'])->name('news.admin.create');
        Route::post('/store', [NewsController::class, 'store'])->name('news.admin.store');
        Route::get('/edit/{id}', [NewsController::class, 'edit'])->name('news.admin.edit');
        Route::post('/update/{id}', [NewsController::class, 'update'])->name('news.admin.update');
        Route::delete('/delete/{id}', [NewsController::class, 'destroy'])->name('news.admin.delete');
    });
});




Route::prefix('company/admin')->group(function () {

    Route::get('/verify', [CompanyController::class, 'verifyForm'])->name('company.verifyForm');
    Route::post('/verify', [CompanyController::class, 'verifyWorker'])->name('company.verifyWorker');

    // Admin dashboard — DISPLAY ALL COMPANIES
    Route::get('/dashboard', [CompanyController::class, 'adminDashboard'])
        ->name('company.admin.dashboard');

    // Create company manually
    Route::get('/create', [CompanyController::class, 'create'])
        ->name('company.create');

    // Store new company
    Route::post('/store', [CompanyController::class, 'store'])
        ->name('company.store');

    // Edit company
    Route::get('/{id}/edit', [CompanyController::class, 'edit'])
        ->name('company.edit');

    // Update company (PUT)
    Route::put('/{id}', [CompanyController::class, 'update'])
        ->name('company.update');

    // Delete company
    Route::delete('/{id}', [CompanyController::class, 'destroy'])
        ->name('company.destroy');

    // UPDATE STOCK DATA (employee_count, branch, stock_value, etc.)
    Route::post('/update-stock', [CompanyController::class, 'updateStock'])
        ->name('company.updateStock');

    // Add instant company (+ New Company)
    Route::post('/store-instant', [CompanyController::class, 'storeInstant'])
        ->name('company.storeInstant');

    Route::get('/list', [CompanyController::class, 'allList'])
        ->name('company.list');
});



// LOGIN / VERIFY
Route::get('/personal/verify', [PersonalController::class, 'verifyForm'])
    ->name('personal.verifyForm');
Route::post('/personal/verify', [PersonalController::class, 'verifyWorker'])
    ->name('personal.verifyWorker');

// ADMIN AREA (CRUD in dashboard)
Route::get('/personal/admin/dashboard', [PersonalController::class, 'index'])
    ->name('personal.admin.dashboard');

Route::post('/personal/store', [PersonalController::class, 'store'])
    ->name('personal.store');

Route::put('/personal/update/{id}', [PersonalController::class, 'update'])
    ->name('personal.update');

Route::delete('/personal/delete/{id}', [PersonalController::class, 'destroy'])
    ->name('personal.destroy');

// LIST VIEW for regular workers (Read Only)
Route::get('/personal/list', [PersonalController::class, 'allList'])
    ->name('personal.list');




// LOGIN / VERIFY CCTV
Route::get('/cctv/verify', [CctvController::class, 'verifyForm'])
    ->name('cctv.verifyForm');

Route::post('/cctv/verify', [CctvController::class, 'verifyWorker'])
    ->name('cctv.verifyWorker');

// ADMIN CCTV DASHBOARD
Route::get('/cctv/admin/dashboard', [CctvController::class, 'adminDashboard'])
    ->name('cctv.admin.dashboard');

// CCTV CRUD
Route::post('/cctv/store', [CctvController::class, 'store'])
    ->name('cctv.store');

Route::put('/cctv/update/{id}', [CctvController::class, 'update'])
    ->name('cctv.update');

Route::delete('/cctv/delete/{id}', [CctvController::class, 'destroy'])
    ->name('cctv.destroy');

// CCTV PUBLIC (for regular workers)
Route::get('/cctv/list', [CctvController::class, 'publicList'])
    ->name('cctv.list');







// AUTH SURVEY
Route::get('/survey/verify', [SurveyController::class, 'verifyForm'])
    ->name('survey.verifyForm');

Route::post('/survey/verify', [SurveyController::class, 'verifyWorker'])
    ->name('survey.verifyWorker');

Route::get('/survey/logout', [SurveyController::class, 'logout'])
    ->name('survey.logout');

// ADMIN SURVEY

Route::get('/survey/admin/dashboard', [SurveyController::class, 'dashboard'])
    ->name('survey.dashboard'); // done

Route::post('/survey/admin/create', [SurveyController::class, 'createSurvey'])
    ->name('admin.survey.create'); // done

Route::get('/survey/admin/{id}/questions', [SurveyController::class, 'questions'])
    ->name('admin.survey.questions'); // done

Route::delete('/survey/admin/delete/{id}', [SurveyController::class, 'deleteSurvey'])
    ->name('admin.survey.delete'); // done

Route::post('/survey/admin/question/add', [SurveyController::class, 'addQuestion'])
    ->name('admin.question.add'); // done



Route::get('/survey/admin/question/{id}/edit', [SurveyController::class, 'editQuestion'])
    ->name('admin.question.edit'); // done

Route::put('/survey/admin/question/{id}', [SurveyController::class, 'updateQuestion'])
    ->name('admin.question.update'); // done

Route::delete('/survey/admin/question/{id}', [SurveyController::class, 'deleteQuestion'])
    ->name('admin.question.delete'); // done

Route::get('/survey/admin/results', [SurveyController::class, 'listResult'])
    ->name('admin.list');

Route::get('/survey/admin/results/{id}', [SurveyController::class, 'resultDetail'])
    ->name('admin.results');

// WORKER
Route::get('/survey/form/{id}', [SurveyController::class, 'form'])
    ->name('survey.form');

Route::post('/survey/{id}/publish', [SurveyController::class, 'publish'])
    ->name('survey.publish');

Route::post('/survey/submit', [SurveyController::class, 'submit'])
    ->name('survey.submit');

Route::get('/admin/survey/results', [SurveyController::class, 'listResult'])
    ->name('admin.survey.results');

Route::get('/admin/survey/results/{id}', [SurveyController::class, 'resultDetail'])
    ->name('admin.survey.results.detail');




// SUGGESTIONS AUTH
Route::get('/suggestions/verify', [EmployeeSuggestionController::class, 'verifyForm'])
    ->name('suggestions.verifyForm');

Route::post('/suggestions/verify', [EmployeeSuggestionController::class, 'verifyWorker'])
    ->name('suggestions.verifyWorker');

// WORKER
Route::get('/suggestions', [EmployeeSuggestionController::class, 'index'])
    ->name('suggestions.index');

Route::post('/suggestions', [EmployeeSuggestionController::class, 'store'])
    ->name('suggestions.store');

// ADMIN
Route::get('/suggestions/admin', [EmployeeSuggestionController::class, 'adminIndex'])
    ->name('suggestions.admin.index');

Route::post('/suggestions/{id}/feedback', [EmployeeSuggestionController::class, 'feedback'])
    ->name('suggestions.feedback');


// OVERTIME

// VERIFY
Route::get('/overtime/verify', [OvertimeController::class, 'verify'])->name('overtime.verify');
Route::post('/overtime/verify', [OvertimeController::class, 'verifyWorker'])->name('overtime.verifyWorker');

Route::get('/overtime/admin/dashboard', [OvertimeController::class, 'adminDashboard'])
    ->name('overtime.admin.dashboard');

Route::get('/overtime/list', [OvertimeController::class, 'listOvertime'])
    ->name('overtime.list');

Route::post('/overtime/start', [OvertimeController::class, 'start'])
    ->name('overtime.start');

Route::post('/overtime/finish', [OvertimeController::class, 'finish'])
    ->name('overtime.finish');

Route::post('/overtime/logout', [OvertimeController::class, 'logout'])
    ->name('overtime.logout');






// PAYROLL

// VERIFY
Route::get('/payroll/verify', [PayrollController::class, 'verify'])->name('payroll.verify');
Route::post('/payroll/verify', [PayrollController::class, 'verifyWorker'])->name('payroll.verifyWorker');


// ADMIN AREA (CRUD in dashboard)

Route::get('/payroll/admin', [PayrollController::class, 'adminDashboard'])
    ->name('payroll.admin.dashboard');

// Worker
Route::get('/payroll/salary', [PayrollController::class, 'salary'])
    ->name('payroll.salary');


//  Selected Month 
Route::post('/payroll/generate', [PayrollController::class, 'generatePayroll'])
    ->name('payroll.generate');

Route::post('/payroll/logout', [PayrollController::class, 'logout'])
    ->name('payroll.logout');


// //closeMonth
// Route::post('/payroll/close-month', [PayrollController::class, 'closeMonth'])
//     ->name('payroll.closeMonth');    
// //ReopenMonth
// Route::post('/payroll/reopen', [PayrollController::class, 'reopenMonth'])
//     ->name('payroll.reopenMonth');



// LEARNING PLAN

//VERIFY
Route::get('/learningplan/verify', [LearningplanController::class, 'verify'])
    ->name('learningplan.verify');
Route::post('/learningplan/verify', [LearningplanController::class, 'verifyWorker'])
    ->name('learningplan.verifyWorker');


//STAFF
Route::get('/learningplan/staff', [LearningplanController::class, 'staff'])
    ->name('learningplan.staff');

Route::post('/learningplan/upload-feedback', [LearningplanController::class, 'uploadFeedback'])
    ->name('learningplan.uploadFeedback');

Route::get('/learnigplan/logout', [LearningplanController::class, 'Logout'])
    ->name('learningplan.logout');


//ADMIN    
Route::match(['get', 'post'], 'admin', [LearningplanController::class, 'adminDashboard'])->name('learningplan.admin.dashboard');
Route::get('admin/delete/{id}', [LearningplanController::class, 'deleteModule'])->name('learningplan.admin.delete');


//CERTIFICATION



Route::prefix('certification')->group(function () {
    // Login
    Route::get('/verify', [CertificationController::class, 'verify'])->name('certification.verify');
    Route::post('/verify', [CertificationController::class, 'verifyWorker'])->name('certification.verifyWorker');
    Route::post('/logout', [CertificationController::class, 'logout'])->name('certification.logout');

    // Dashboard per Role
    Route::get('/staff', [CertificationController::class, 'staff'])->name('certification.staff');
    Route::get('/mt', [CertificationController::class, 'mtView'])->name('certification.mt');
    Route::get('/admin', [CertificationController::class, 'adminDashboard'])->name('certification.admin.dashboard');

    // Action
    Route::post('/store', [CertificationController::class, 'store'])->name('certification.store');
    Route::get('/{id}/download', [CertificationController::class, 'download'])->name('certification.download');
});


//VERIFY
Route::get('/achievements/verify', [AchievementController::class, 'verify'])
    ->name('achievement.verify');
Route::post('achievements/verify', [AchievementController::class, 'verifyWorker'])
    ->name('achievement.verifyWorker');





//VERIFY
Route::get('/idea/verify', [IdeaController::class, 'verify'])
    ->name('idea.verify');
Route::post('idea/verify', [IdeaController::class, 'verifyWorker'])
    ->name('idea.verify.worker');

Route::get('/staff/dashboard', [IdeaController::class, 'staffDashboard'])
    ->name('idea.staff');

Route::get('/admin/dashboard', [IdeaController::class, 'adminDashboard'])
    ->name('idea.admin.dashboard');

Route::get('/lead/dashboard', [IdeaController::class, 'leadDashboard'])
    ->name('idea.lead.dashboard'); 

Route::post('/idea/store', [IdeaController::class, 'store'])
    ->name('idea.store');

Route::post('/idea/{idea}/publish', [IdeaController::class, 'publish'])
    ->name('idea.publish');


Route::post('/idea/{idea}/vote', [IdeaController::class, 'vote'])
    ->name('idea.vote');

Route::post('/idea/{idea}/review', [IdeaController::class, 'review'])
    ->name('idea.review');

Route::get('/idea/{idea}/result', [IdeaController::class, 'result'])
    ->name('idea.result');    

Route::get('/winner', [IdeaController::class, 'winner'])
    ->name('idea.winner');


Route::post('/editor/upload', [IdeaController::class, 'upload']);    

Route::post('/idea/logout', [IdeaController::class, 'logout'])
    ->name('idea.logout');    
    
    


// Other static routes
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about', ['name' => 'faisal syahroni']);
});

Route::get('/blog', function () {
    return view('blog');
});

// Personal information page
Route::get('/personal-info', function () {
    return view('personal-info');
});

// Company information page
Route::get('/company-info', function () {
    return view('company-info');
});

// News page
Route::get('/news', function () {
    return view('news');
});

// Redirect attendance to login for consistency
Route::get('/attendance', function () {
    return redirect()->route('login');
});

// Employee suggestions page
Route::get('/suggestions', function () {
    return view('suggestions');
});

// Learning map page
Route::get('/learning-map', function () {
    return view('learning-map');
});

// idea page
Route::get('/idea', function () {
    return view('achievements');
});

// Leaderboard page
Route::get('/leaderboard', function () {
    return view('leaderboard');
});

// Learning plan page
Route::get('/learning-plan', function () {
    return view('learning-plan');
});

// Certificates page
Route::get('/certificates', function () {
    return view('certificates');
});

// Offline training page
Route::get('/offline-training', function () {
    return view('offline-training');
});

// Latest updates page
Route::get('/latest', function () {
    return view('latest');
});

// Menu page
Route::get('/menu', function () {
    return view('menu');
});

// Messages page
Route::get('/messages', function () {
    return view('messages');
});

// User profile page
Route::get('/profile', function () {
    return view('profile');
});
