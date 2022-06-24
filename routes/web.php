<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/SA', [App\Http\Controllers\SuperAdminController::class, 'index']);
Route::post('/SA/import', [App\Http\Controllers\SuperAdminController::class, 'import']);

Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');
Route::get('/admin/create', [App\Http\Controllers\AdminController::class, 'create'])->name('admin.create');
Route::post('/admin/store', [App\Http\Controllers\AdminController::class, 'store'])->name('admin.store');
Route::delete('/admin/delete', [App\Http\Controllers\AdminController::class, 'delete'])->name('admin.delete');
Route::post('/admin/getProgramoptions', [App\Http\Controllers\AdminController::class, 'getProgramoptions']);
Route::post('/admin/getProgramoptions2', [App\Http\Controllers\AdminController::class, 'getProgramoptions2']);
Route::get('/admin/{id}/edit', [App\Http\Controllers\AdminController::class, 'edit'])->name('admin.edit');
Route::patch('/admin/{id}', [App\Http\Controllers\AdminController::class, 'update'])->name('admin.update');

Route::get('/KP', [App\Http\Controllers\KP_Controller::class, 'index'])->name('ketua_program');
Route::get('/KP/create', [App\Http\Controllers\KP_Controller::class, 'create'])->name('kp.create');
Route::post('/KP/store', [App\Http\Controllers\KP_Controller::class, 'store'])->name('kp.store');
Route::delete('/KP/delete', [App\Http\Controllers\KP_Controller::class, 'delete'])->name('kp.delete');
Route::any('/KP/students', [App\Http\Controllers\KP_Controller::class, 'student'])->name('kp.student');
Route::get('/KP/{course}/assessment', [App\Http\Controllers\KP_Controller::class, 'assessment'])->name('kp.assessment');
Route::get('/KP/{group}/edit', [App\Http\Controllers\KP_Controller::class, 'edit'])->name('kp.edit');
Route::get('/KP/{group}/editgroup', [App\Http\Controllers\KP_Controller::class, 'editgroup'])->name('kp.editgroup');
Route::patch('/KP/{group}/updategroup/{id}', [App\Http\Controllers\KP_Controller::class, 'updategroup'])->name('kp.updategroup');
Route::patch('/KP/{group}', [App\Http\Controllers\KP_Controller::class, 'update'])->name('kp.update');
Route::patch('/KP/{course}/update/marks', [App\Http\Controllers\KP_Controller::class, 'update_marks'])->name('kp.update.marks');
Route::get('/KP/create/group', [App\Http\Controllers\KP_Controller::class, 'create_group'])->name('kp.group');
Route::post('/KP/group/getStudentTable', [App\Http\Controllers\KP_Controller::class, 'getStudentTable']);
Route::post('KP/group/getcourseoptions', [App\Http\Controllers\KP_Controller::class, 'getCourse']);
Route::post('KP/group/getlectureroptions', [App\Http\Controllers\KP_Controller::class, 'getLecturer']);
Route::patch('KP/group/update', [App\Http\Controllers\KP_Controller::class, 'update_group'])->name('kp.group.update');
Route::get('/KP/lecturer', [App\Http\Controllers\KP_Controller::class, 'lecturerindex'])->name('kp.lecturer');
Route::post('/KP/lecturer/filter', [App\Http\Controllers\KP_Controller::class, 'getLecturerTable']);
Route::get('/KP/lecturer/report/{id}', [App\Http\Controllers\KP_Controller::class, 'lecturer_report'])->name('kp.lecturer.report');
Route::get('/KP/marks', [App\Http\Controllers\KP_Controller::class, 'courseMark'])->name('kp.coursemark');

Route::get('/AO', [App\Http\Controllers\AO_Controller::class, 'index'])->name('pegawai_takbir');

Route::get('/pendaftar', [App\Http\Controllers\PendaftarController::class, 'index'])->name('pendaftar');
Route::get('/pendaftar/create', [App\Http\Controllers\PendaftarController::class, 'create'])->name('pendaftar.create');
Route::get('/pendaftar/create', [App\Http\Controllers\PendaftarController::class, 'create'])->name('pendaftar.create');
Route::post('/pendaftar/store', [App\Http\Controllers\PendaftarController::class, 'store'])->name('pendaftar.store');
Route::delete('/pendaftar/delete', [App\Http\Controllers\PendaftarController::class, 'delete'])->name('pendaftar.delete');
Route::post('/pendaftar/group/getSubject', [App\Http\Controllers\PendaftarController::class, 'getSubjectOption']);
Route::post('/pendaftar/group/getStudentTableIndex', [App\Http\Controllers\PendaftarController::class, 'getStudentTableIndex']);
Route::post('/pendaftar/group/getGroupOption', [App\Http\Controllers\PendaftarController::class, 'getGroupOption']);

Route::get('/lecturer', [App\Http\Controllers\LecturerController::class, 'index'])->name('lecturer');
Route::post('/lecturer/course/filter', [App\Http\Controllers\LecturerController::class, 'getCourseList']);
Route::delete('/lecturer/content/delete', [App\Http\Controllers\LecturerController::class, 'deleteContent']);
Route::delete('/lecturer/content/folder/delete', [App\Http\Controllers\LecturerController::class, 'deleteFolder']);
Route::delete('/lecturer/content/folder/subfolder/delete', [App\Http\Controllers\LecturerController::class, 'deleteSubfolder']);
Route::delete('/lecturer/content/folder/subfolder/material/delete', [App\Http\Controllers\LecturerController::class, 'deleteMaterial']);
Route::get('/lecturer/content/{id}', [App\Http\Controllers\LecturerController::class, 'courseContent'])->name('lecturer.content');
Route::get('/lecturer/content/{id}/create', [App\Http\Controllers\LecturerController::class, 'createContent']);
Route::post('/lecturer/content/{id}/store', [App\Http\Controllers\LecturerController::class, 'storeContent']);
Route::get('/lecturer/content/material/{dir}', [App\Http\Controllers\LecturerController::class, 'courseDirectory'])->name('lecturer.directory');
Route::get('/lecturer/content/material/prev/{dir}', [App\Http\Controllers\LecturerController::class, 'prevcourseDirectory'])->name('lecturer.directory.prev');
Route::get('/lecturer/content/material/create/{dir}', [App\Http\Controllers\LecturerController::class, 'createDirectory']);
Route::post('/lecturer/content/material/store/{dir}', [App\Http\Controllers\LecturerController::class, 'storeDirectory']);
Route::post('/lecturer/content/material/password/{dir}', [App\Http\Controllers\LecturerController::class, 'passwordDirectory']);
Route::get('/lecturer/content/material/sub/{dir}', [App\Http\Controllers\LecturerController::class, 'courseSubDirectory'])->name('lecturer.subdirectory');
Route::get('/lecturer/content/material/sub/prev/{dir}', [App\Http\Controllers\LecturerController::class, 'prevcourseSubDirectory'])->name('lecturer.subdirectory.prev');
Route::get('/lecturer/content/material/sub/create/{dir}', [App\Http\Controllers\LecturerController::class, 'createSubDirectory']);
Route::post('/lecturer/content/material/sub/store/{dir}', [App\Http\Controllers\LecturerController::class, 'storeSubDirectory']);
Route::post('/lecturer/content/material/sub/password/{dir}', [App\Http\Controllers\LecturerController::class, 'passwordSubDirectory']);
Route::get('/lecturer/content/material/sub/content/{dir}', [App\Http\Controllers\LecturerController::class, 'DirectoryContent'])->name('lecturer.directory.content');
Route::get('/lecturer/content/material/sub/content/prev/{dir}', [App\Http\Controllers\LecturerController::class, 'prevDirectoryContent'])->name('lecturer.directory.content.prev');
Route::post('/lecturer/content/material/sub/content/upload/{id}', [App\Http\Controllers\LecturerController::class, 'uploadMaterial']);
Route::post('/lecturer/content/material/sub/content/password/{dir}', [App\Http\Controllers\LecturerController::class, 'passwordContent']);
Route::get('/lecturer/{id}', [App\Http\Controllers\LecturerController::class, 'courseSummary'])->name('lecturer.summary');
Route::get('/lecturer/class/schedule', [App\Http\Controllers\LecturerController::class, 'classSchedule'])->name('lecturer.class.schedule');
Route::get('/lecturer/class/schedule/getGroup', [App\Http\Controllers\LecturerController::class, 'scheduleGetGroup']);
Route::post('/lecturer/class/schedule/getschedule', [App\Http\Controllers\LecturerController::class, 'getSchedule']);
Route::post('/lecturer/class/schedule/insertschedule', [App\Http\Controllers\LecturerController::class, 'scheduleInsertGroup']);
Route::get('/lecturer/class/attendance', [App\Http\Controllers\LecturerController::class, 'classAttendance'])->name('lecturer.class.attendance');
Route::get('/lecturer/class/attendance/getGroup', [App\Http\Controllers\LecturerController::class, 'attendanceGetGroup']);
Route::post('/lecturer/class/attendance/getStudents', [App\Http\Controllers\LecturerController::class, 'getStudents']);
Route::post('/lecturer/class/attendance/getDate', [App\Http\Controllers\LecturerController::class, 'getDate']);
Route::post('/lecturer/class/attendance/store', [App\Http\Controllers\LecturerController::class, 'storeAttendance'])->name('lecturer.attendance.store');
Route::get('/lecturer/class/attendance/report', [App\Http\Controllers\LecturerController::class, 'listAttendance'])->name('lecturer.attendance.report');
Route::get('/lecturer/class/attendance/report/{date}/{group}', [App\Http\Controllers\LecturerController::class, 'reportAttendance']);
Route::get('/lecturer/class/onlineclass', [App\Http\Controllers\LecturerController::class, 'onlineClass'])->name('lecturer.class.onlineclass');
Route::post('lecturer/class/onlineclass/getChapters', [App\Http\Controllers\LecturerController::class, 'getChapters']);
Route::post('lecturer/class/onlineclass/getSubChapters', [App\Http\Controllers\LecturerController::class, 'getSubChapters']);
Route::post('/lecturer/class/onlineclass/store', [App\Http\Controllers\LecturerController::class, 'storeOnlineClass'])->name('lecturer.onlineclass.store');
Route::get('/lecturer/class/onlineclass/list', [App\Http\Controllers\LecturerController::class, 'OnlineClassList'])->name('lecturer.onlineclass.list');
Route::get('/lecturer/class/onlineclass/list/edit/{id}', [App\Http\Controllers\LecturerController::class, 'OnlineClassListEdit'])->name('lecturer.onlineclass.list.edit');
Route::patch('/lecturer/class/onlineclass/list/update/{id}', [App\Http\Controllers\LecturerController::class, 'OnlineClassListUpdate'])->name('lecturer.onlineclass.list.update');

Route::get('/lecturer/report/{id}', [App\Http\Controllers\LecturerController::class, 'assessmentreport'])->name('lecturer.report');
Route::get('/lecturer/report/{id}/{student}', [App\Http\Controllers\LecturerController::class, 'studentreport'])->name('lecturer.report.student');

Route::get('/lecturer/quiz/{id}', [App\Http\Controllers\QuizController::class, 'quizlist'])->name('lecturer.quiz');
Route::get('/lecturer/quiz/{id}/create', [App\Http\Controllers\QuizController::class, 'quizcreate'])->name('lecturer.quiz.create');
Route::post('/lecturer/quiz/insert', [App\Http\Controllers\QuizController::class, 'insertquiz']);

Route::post('/lecturer/quiz/getStatus', [App\Http\Controllers\QuizController::class, 'getStatus']);
Route::post('/lecturer/quiz/updatequizresult', [App\Http\Controllers\QuizController::class, 'updatequizresult']);
Route::get('/lecturer/quiz/{id}/{quiz}', [App\Http\Controllers\QuizController::class, 'lecturerquizstatus'])->name('lecturer.quiz.status');
Route::get('/lecturer/quiz/{quizid}/{userid}/result', [App\Http\Controllers\QuizController::class, 'quizresult']);
Route::post('/lecturer/quiz/getChapters', [App\Http\Controllers\QuizController::class, 'getChapters']);


Route::get('/lecturer/test/{id}', [App\Http\Controllers\TestController::class, 'testlist'])->name('lecturer.test');
Route::get('/lecturer/test/{id}/create', [App\Http\Controllers\TestController::class, 'testcreate'])->name('lecturer.test.create');
Route::post('/lecturer/test/insert', [App\Http\Controllers\TestController::class, 'inserttest']);

Route::post('/lecturer/test/getStatus', [App\Http\Controllers\TestController::class, 'getStatus']);
Route::post('/lecturer/test/updatetestresult', [App\Http\Controllers\TestController::class, 'updatetestresult']);
Route::get('/lecturer/test/{id}/{test}', [App\Http\Controllers\TestController::class, 'lecturerteststatus'])->name('lecturer.test.status');
Route::get('/lecturer/test/{testid}/{userid}/result', [App\Http\Controllers\TestController::class, 'testresult']);
Route::post('/lecturer/test/getChapters', [App\Http\Controllers\TestController::class, 'getChapters']);


Route::get('/lecturer/assign/{id}', [App\Http\Controllers\AssignmentController::class, 'assignlist'])->name('lecturer.assign');
Route::get('/lecturer/assign/{id}/create', [App\Http\Controllers\AssignmentController::class, 'assigncreate'])->name('lecturer.assign.create');
Route::post('/lecturer/assign/insert', [App\Http\Controllers\AssignmentController::class, 'insertassign']);

Route::post('/lecturer/assign/getStatus', [App\Http\Controllers\AssignmentController::class, 'getStatus']);
Route::post('/lecturer/assign/updateassignresult', [App\Http\Controllers\AssignmentController::class, 'updateassignresult']);
Route::get('/lecturer/assign/{id}/{assign}', [App\Http\Controllers\AssignmentController::class, 'lecturerassignstatus'])->name('lecturer.assign.status');
Route::get('/lecturer/assign/{assignid}/{userid}/result', [App\Http\Controllers\AssignmentController::class, 'assignresult']);
Route::post('/lecturer/assign/getChapters', [App\Http\Controllers\AssignmentController::class, 'getChapters']);


Route::get('/lecturer/paperwork/{id}', [App\Http\Controllers\PaperworkController::class, 'paperworklist'])->name('lecturer.paperwork');
Route::get('/lecturer/paperwork/{id}/create', [App\Http\Controllers\PaperworkController::class, 'paperworkcreate'])->name('lecturer.paperwork.create');
Route::post('/lecturer/paperwork/insert', [App\Http\Controllers\PaperworkController::class, 'insertpaperwork']);

Route::post('/lecturer/paperwork/getStatus', [App\Http\Controllers\PaperworkController::class, 'getStatus']);
Route::post('/lecturer/paperwork/updatepaperworkresult', [App\Http\Controllers\PaperworkController::class, 'updatepaperworkresult']);
Route::get('/lecturer/paperwork/{id}/{paperwork}', [App\Http\Controllers\PaperworkController::class, 'lecturerpaperworkstatus'])->name('lecturer.paperwork.status');
Route::get('/lecturer/paperwork/{paperworkid}/{userid}/result', [App\Http\Controllers\PaperworkController::class, 'paperworkresult']);
Route::post('/lecturer/paperwork/getChapters', [App\Http\Controllers\PaperworkController::class, 'getChapters']);


Route::get('/lecturer/practical/{id}', [App\Http\Controllers\PracticalController::class, 'practicallist'])->name('lecturer.practical');
Route::get('/lecturer/practical/{id}/create', [App\Http\Controllers\PracticalController::class, 'practicalcreate'])->name('lecturer.practical.create');
Route::post('/lecturer/practical/insert', [App\Http\Controllers\PracticalController::class, 'insertpractical']);

Route::post('/lecturer/practical/getStatus', [App\Http\Controllers\PracticalController::class, 'getStatus']);
Route::post('/lecturer/practical/updatepracticalresult', [App\Http\Controllers\PracticalController::class, 'updatepracticalresult']);
Route::get('/lecturer/practical/{id}/{practical}', [App\Http\Controllers\PracticalController::class, 'lecturerpracticalstatus'])->name('lecturer.practical.status');
Route::get('/lecturer/practical/{practicalid}/{userid}/result', [App\Http\Controllers\PracticalController::class, 'practicalresult']);
Route::post('/lecturer/practical/getChapters', [App\Http\Controllers\PracticalController::class, 'getChapters']);


Route::get('/lecturer/other/{id}', [App\Http\Controllers\OtherController::class, 'otherlist'])->name('lecturer.other');
Route::get('/lecturer/other/{id}/create', [App\Http\Controllers\OtherController::class, 'othercreate'])->name('lecturer.other.create');
Route::post('/lecturer/other/insert', [App\Http\Controllers\OtherController::class, 'insertother']);

Route::post('/lecturer/other/getStatus', [App\Http\Controllers\OtherController::class, 'getStatus']);
Route::post('/lecturer/other/updateotherresult', [App\Http\Controllers\OtherController::class, 'updateotherresult']);
Route::get('/lecturer/other/{id}/{other}', [App\Http\Controllers\OtherController::class, 'lecturerotherstatus'])->name('lecturer.other.status');
Route::get('/lecturer/other/{otherid}/{userid}/result', [App\Http\Controllers\OtherController::class, 'otherresult']);
Route::post('/lecturer/other/getChapters', [App\Http\Controllers\OtherController::class, 'getChapters']);


Route::get('/lecturer/midterm/{id}', [App\Http\Controllers\MidtermController::class, 'midtermlist'])->name('lecturer.midterm');
Route::get('/lecturer/midterm/{id}/create', [App\Http\Controllers\MidtermController::class, 'midtermcreate'])->name('lecturer.midterm.create');
Route::post('/lecturer/midterm/insert', [App\Http\Controllers\MidtermController::class, 'insertmidterm']);

Route::post('/lecturer/midterm/getStatus', [App\Http\Controllers\MidtermController::class, 'getStatus']);
Route::post('/lecturer/midterm/updatemidtermresult', [App\Http\Controllers\MidtermController::class, 'updatemidtermresult']);
Route::get('/lecturer/midterm/{id}/{midterm}', [App\Http\Controllers\MidtermController::class, 'lecturermidtermstatus'])->name('lecturer.midterm.status');
Route::get('/lecturer/midterm/{midtermid}/{userid}/result', [App\Http\Controllers\MidtermController::class, 'midtermresult']);
Route::post('/lecturer/midterm/getChapters', [App\Http\Controllers\MidtermController::class, 'getChapters']);

Route::get('/lecturer/final/{id}', [App\Http\Controllers\FinalController::class, 'finallist'])->name('lecturer.final');
Route::get('/lecturer/final/{id}/create', [App\Http\Controllers\FInalController::class, 'finalcreate'])->name('lecturer.final.create');
Route::post('/lecturer/final/insert', [App\Http\Controllers\FinalController::class, 'insertfinal']);

Route::post('/lecturer/final/getStatus', [App\Http\Controllers\FinalController::class, 'getStatus']);
Route::post('/lecturer/final/updatefinalresult', [App\Http\Controllers\FinalController::class, 'updatefinalresult']);
Route::get('/lecturer/final/{id}/{final}', [App\Http\Controllers\FinalController::class, 'lecturerfinalstatus'])->name('lecturer.final.status');
Route::get('/lecturer/final/{finalid}/{userid}/result', [App\Http\Controllers\FinalController::class, 'finalresult']);
Route::post('/lecturer/final/getChapters', [App\Http\Controllers\FinalController::class, 'getChapters']);


Route::get('/student', [App\Http\Controllers\StudentController::class, 'index'])->name('student');
Route::post('/student/course/filter', [App\Http\Controllers\StudentController::class, 'getCourseList']);
Route::get('/student/{id}', [App\Http\Controllers\StudentController::class, 'courseSummary'])->name('student.summary');
Route::get('/student/content/{id}', [App\Http\Controllers\StudentController::class, 'courseContent'])->name('student.content');
Route::get('/student/content/material/{dir}', [App\Http\Controllers\StudentController::class, 'courseDirectory'])->name('student.directory');
Route::post('/student/content/password/{dir}', [App\Http\Controllers\StudentController::class, 'passwordDirectory']);
Route::get('/student/content/material/prev/{dir}', [App\Http\Controllers\StudentController::class, 'prevcourseDirectory'])->name('student.directory.prev');
Route::get('/student/content/material/sub/{dir}', [App\Http\Controllers\StudentController::class, 'courseSubDirectory'])->name('student.subdirectory');
Route::get('/student/content/material/sub/prev/{dir}', [App\Http\Controllers\StudentController::class, 'prevcourseSubDirectory'])->name('student.subdirectory.prev');
Route::post('/student/content/material/sub/password/{dir}', [App\Http\Controllers\StudentController::class, 'passwordSubDirectory']);
Route::get('/student/content/material/sub/content/{dir}', [App\Http\Controllers\StudentController::class, 'DirectoryContent'])->name('student.directory.content');
Route::post('/student/content/material/sub/content/password/{dir}', [App\Http\Controllers\StudentController::class, 'passwordContent']);
Route::get('/student/class/schedule', [App\Http\Controllers\StudentController::class, 'classSchedule'])->name('student.class.schedule');
Route::get('/Student/class/schedule/getGroup', [App\Http\Controllers\StudentController::class, 'scheduleGetGroup']);
Route::post('/student/class/schedule/getschedule', [App\Http\Controllers\StudentController::class, 'getSchedule']);
Route::get('/student/class/onlineclass/list', [App\Http\Controllers\StudentController::class, 'OnlineClassList'])->name('student.onlineclass.list');
Route::get('/student/class/onlineclass/list/{id}', [App\Http\Controllers\StudentController::class, 'OnlineClassListView'])->name('student.onlineclass.list.view');

Route::get('/student/report/{id}', [App\Http\Controllers\StudentController::class, 'studentreport'])->name('student.report.student');

Route::get('/student/quiz/{id}', [App\Http\Controllers\QuizController::class, 'studentquizlist'])->name('student.quiz');
Route::get('/student/quiz/{id}/{quiz}', [App\Http\Controllers\QuizController::class, 'studentquizstatus'])->name('student.quiz.status');
Route::get('/student/quiz/{id}/{quiz}/view', [App\Http\Controllers\QuizController::class, 'quizview']);
Route::get('/student/quiz/{quizid}/{userid}/result', [App\Http\Controllers\QuizController::class, 'quizresultstd']);
Route::post('/student/quiz/startquiz', [App\Http\Controllers\QuizController::class, 'startquiz']);
Route::post('/student/quiz/savequiz', [App\Http\Controllers\QuizController::class, 'savequiz']);
Route::post('/student/quiz/submitquiz', [App\Http\Controllers\QuizController::class, 'submitquiz']);

Route::get('/student/test/{id}', [App\Http\Controllers\TestController::class, 'studenttestlist'])->name('student.test');
Route::get('/student/test/{id}/{test}', [App\Http\Controllers\TestController::class, 'studentteststatus'])->name('student.test.status');
Route::get('/student/test/{id}/{test}/view', [App\Http\Controllers\TestController::class, 'testview']);
Route::get('/student/test/{testid}/{userid}/result', [App\Http\Controllers\TestController::class, 'testresultstd']);
Route::post('/student/test/starttest', [App\Http\Controllers\TestController::class, 'starttest']);
Route::post('/student/test/savetest', [App\Http\Controllers\TestController::class, 'savetest']);
Route::post('/student/test/submittest', [App\Http\Controllers\TestController::class, 'submittest']);


Route::get('/student/assign/{id}', [App\Http\Controllers\AssignmentController::class, 'studentassignlist'])->name('student.assign');
Route::get('/student/assign/{id}/{assign}', [App\Http\Controllers\AssignmentController::class, 'studentassignstatus'])->name('student.assign.status');
Route::get('/student/assign/{id}/{assign}/view', [App\Http\Controllers\AssignmentController::class, 'assignview']);
Route::get('/student/assign/{assignid}/{userid}/result', [App\Http\Controllers\AssignmentController::class, 'assignresultstd']);
Route::post('/student/assign/submitassign', [App\Http\Controllers\AssignmentController::class, 'submitassign']);

Route::get('/student/paperwork/{id}', [App\Http\Controllers\PaperworkController::class, 'studentpaperworklist'])->name('student.paperwork');
Route::get('/student/paperwork/{id}/{paperwork}', [App\Http\Controllers\PaperworkController::class, 'studentpaperworkstatus'])->name('student.paperwork.status');
Route::get('/student/paperwork/{id}/{paperwork}/view', [App\Http\Controllers\PaperworkController::class, 'paperworkview']);
Route::get('/student/paperwork/{paperworkid}/{userid}/result', [App\Http\Controllers\PaperworkController::class, 'paperworkresultstd']);
Route::post('/student/paperwork/submitpaperwork', [App\Http\Controllers\PaperworkController::class, 'submitpaperwork']);


Route::get('/student/practical/{id}', [App\Http\Controllers\PracticalController::class, 'studentpracticallist'])->name('student.practical');
Route::get('/student/practical/{id}/{practical}', [App\Http\Controllers\PracticalController::class, 'studentpracticalstatus'])->name('student.practical.status');
Route::get('/student/practical/{id}/{practical}/view', [App\Http\Controllers\PracticalController::class, 'practicalview']);
Route::get('/student/practical/{practicalid}/{userid}/result', [App\Http\Controllers\PracticalController::class, 'practicalresultstd']);
Route::post('/student/practical/submitpractical', [App\Http\Controllers\PracticalController::class, 'submitpractical']);


Route::get('/student/other/{id}', [App\Http\Controllers\OtherController::class, 'studentotherlist'])->name('student.other');
Route::get('/student/other/{id}/{other}', [App\Http\Controllers\OtherController::class, 'studentotherstatus'])->name('student.other.status');
Route::get('/student/other/{id}/{other}/view', [App\Http\Controllers\OtherController::class, 'otherview']);
Route::get('/student/other/{otherid}/{userid}/result', [App\Http\Controllers\OtherController::class, 'otherresultstd']);
Route::post('/student/other/submitother', [App\Http\Controllers\OtherController::class, 'submitother']);


Route::get('/student/midterm/{id}', [App\Http\Controllers\MidtermController::class, 'studentmidtermlist'])->name('student.midterm');
Route::get('/student/midterm/{id}/{midterm}', [App\Http\Controllers\MidtermController::class, 'studentmidtermstatus'])->name('student.midterm.status');
Route::get('/student/midterm/{id}/{midterm}/view', [App\Http\Controllers\MidtermController::class, 'midtermview']);
Route::get('/student/midterm/{midtermid}/{userid}/result', [App\Http\Controllers\MidtermController::class, 'midtermresultstd']);
Route::post('/student/midterm/startmidterm', [App\Http\Controllers\MidtermController::class, 'startmidterm']);
Route::post('/student/midterm/savemidterm', [App\Http\Controllers\MidtermController::class, 'savemidterm']);
Route::post('/student/midterm/submitmidterm', [App\Http\Controllers\MidtermController::class, 'submitmidterm']);

Route::get('/student/final/{id}', [App\Http\Controllers\FinalController::class, 'studentfinallist'])->name('student.final');
Route::get('/student/final/{id}/{final}', [App\Http\Controllers\FinalController::class, 'studentfinalstatus'])->name('student.final.status');
Route::get('/student/final/{id}/{final}/view', [App\Http\Controllers\FinalController::class, 'finalview']);
Route::get('/student/final/{finalid}/{userid}/result', [App\Http\Controllers\FinalController::class, 'finalresultstd']);
Route::post('/student/final/startfinal', [App\Http\Controllers\FinalController::class, 'startfinal']);
Route::post('/student/final/savefinal', [App\Http\Controllers\FinalController::class, 'savefinal']);
Route::post('/student/final/submitfinal', [App\Http\Controllers\FinalController::class, 'submitfinal']);

Route::post('/login/custom', [App\Http\Controllers\LoginController::class, 'login'])->name('login.custom');

Route::post('/login/student/custom', [App\Http\Controllers\LoginStudentController::class, 'login'])->name('login.student.custom');