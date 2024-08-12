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

Route::get('/loginAdmin', function () {
    return view('auth.loginAdmin');
});



Auth::routes();

Route::get('/home', function(){
    return view('auth.login');
});

Route::get('/SA', [App\Http\Controllers\SuperAdminController::class, 'index']);
Route::post('/SA/import', [App\Http\Controllers\SuperAdminController::class, 'import']);

Route::get('/admin_dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');
Route::get('/admin/{id}/edit', [App\Http\Controllers\AdminController::class, 'edit'])->name('admin.edit');
Route::patch('/admin/{id}', [App\Http\Controllers\AdminController::class, 'update'])->name('admin.update');
Route::get('/admin/create', [App\Http\Controllers\AdminController::class, 'create'])->name('admin.create');
Route::post('/admin/store', [App\Http\Controllers\AdminController::class, 'store'])->name('admin.store');
Route::delete('/admin/delete', [App\Http\Controllers\AdminController::class, 'delete'])->name('admin.delete');
Route::post('/admin/getProgramoptions', [App\Http\Controllers\AdminController::class, 'getProgramoptions']);
Route::post('/admin/getProgramoptions2', [App\Http\Controllers\AdminController::class, 'getProgramoptions2']);
Route::get('/admin/report/lecturer', [App\Http\Controllers\AdminController::class, 'getReportLecturer'])->name('admin.report.lecturer');
Route::post('/admin/report/lecturer/getLecturer', [App\Http\Controllers\AdminController::class, 'getReportLecturerList']);
Route::post('/admin/report/lecturer/getSubject', [App\Http\Controllers\AdminController::class, 'getReportSubjectList']);
Route::post('/admin/report/lecturer/getFolder', [App\Http\Controllers\AdminController::class, 'getFolder']);
Route::post('/admin/report/lecturer/getSubfolder', [App\Http\Controllers\AdminController::class, 'getSubFolder']);
Route::post('/admin/report/lecturer/getSubfolder/getSubfolder2', [App\Http\Controllers\AdminController::class, 'getSubFolder2']);
Route::post('/admin/report/lecturer/getSubfolder/getSubfolder2/getMaterial', [App\Http\Controllers\AdminController::class, 'getMaterial']);
Route::get('/admin/attendance/report', [App\Http\Controllers\AdminController::class, 'listAttendance'])->name('admin.attendance');
//Route::post('/admin/report/lecturer/getAssessment', [App\Http\Controllers\AdminController::class, 'getAssessment']);
Route::get('/admin/report/assessment', [App\Http\Controllers\AdminController::class, 'assessment'])->name('admin.report.assessment');
Route::post('/admin/report/assessment/getAssessment', [App\Http\Controllers\AdminController::class, 'getAssessment']);
Route::post('/admin/report/lecturer/getUserLog', [App\Http\Controllers\AdminController::class, 'getUserLog']);
Route::get('/admin/report/student', [App\Http\Controllers\AdminController::class, 'assessmentreport'])->name('admin.report.student');
Route::get('/admin/training', [App\Http\Controllers\AdminController::class, 'userTraining'])->name('admin.training');
Route::post('/admin/training/getUserList', [App\Http\Controllers\AdminController::class, 'getUserList']);
Route::post('/admin/training/getUserInfo', [App\Http\Controllers\AdminController::class, 'getUserInfo']);
Route::post('/admin/training/storeUserTraining', [App\Http\Controllers\AdminController::class, 'storeUserTraining']);
Route::post('/admin/training/deleteUserTraining', [App\Http\Controllers\AdminController::class, 'deleteUserTraining']);



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
Route::post('/KP/{course}/insert/marks', [App\Http\Controllers\KP_Controller::class, 'insert_marks'])->name('kp.insert.marks');
Route::delete('/KP/{course}/delete/marks', [App\Http\Controllers\KP_Controller::class, 'delete_marks']);
Route::get('/KP/create/group', [App\Http\Controllers\KP_Controller::class, 'create_group'])->name('kp.group');
Route::post('/KP/group/getStudentTable', [App\Http\Controllers\KP_Controller::class, 'getStudentTable']);
Route::post('/KP/group/getStudentTable2', [App\Http\Controllers\KP_Controller::class, 'getStudentTable2']);
Route::post('KP/group/getcourseoptions', [App\Http\Controllers\KP_Controller::class, 'getCourse']);
Route::post('KP/group/getLecturerSubject', [App\Http\Controllers\KP_Controller::class, 'getLecturerSubject']);
Route::post('KP/group/deleteLecturerSubject', [App\Http\Controllers\KP_Controller::class, 'deleteLecturerSubject']);
Route::post('KP/group/getlectureroptions', [App\Http\Controllers\KP_Controller::class, 'getLecturer']);
Route::patch('KP/group/update', [App\Http\Controllers\KP_Controller::class, 'update_group'])->name('kp.group.update');
Route::get('/KP/lecturer', [App\Http\Controllers\KP_Controller::class, 'lecturerindex'])->name('kp.lecturer');
Route::post('/KP/lecturer/filter', [App\Http\Controllers\KP_Controller::class, 'getLecturerTable']);
Route::get('/KP/lecturer/report/{id}', [App\Http\Controllers\KP_Controller::class, 'lecturer_report'])->name('kp.lecturer.report');
Route::get('/KP/marks', [App\Http\Controllers\KP_Controller::class, 'courseMark'])->name('kp.coursemark');
Route::get('/KP/report/file', [App\Http\Controllers\KP_Controller::class, 'lecturerReportFile'])->name('kp.report.file');
Route::get('/KP/report/assessment2', [App\Http\Controllers\KP_Controller::class, 'assessment2'])->name('kp.report.assessment2');
Route::post('/KP/report/assessment/getAssessment', [App\Http\Controllers\KP_Controller::class, 'getAssessment']);
Route::get('/KP/assign/meetingHour', [App\Http\Controllers\KP_Controller::class, 'meetingHour'])->name('kp.assign.meetingHour');
Route::post('/KP/assign/getMeetingHour', [App\Http\Controllers\KP_Controller::class, 'getMeetingHour']);
Route::post('/KP/assign/submitMeetingHour', [App\Http\Controllers\KP_Controller::class, 'submitMeetingHour']);

Route::get('/AO', [App\Http\Controllers\AO_Controller::class, 'index'])->name('pegawai_takbir');

Route::get('/DN', [App\Http\Controllers\DN_Controller::class, 'index'])->name('dekan');

Route::get('/pendaftar_dashboard', [App\Http\Controllers\PendaftarController::class, 'dashboard'])->name('pendaftar.dashboard');
Route::get('/pendaftar', [App\Http\Controllers\PendaftarController::class, 'index'])->name('pendaftar');
Route::get('/pendaftar/create', [App\Http\Controllers\PendaftarController::class, 'create'])->name('pendaftar.create');
Route::post('/pendaftar/create/search', [App\Http\Controllers\PendaftarController::class, 'createSearch'])->name('pendaftar.create.search');
Route::post('/pendaftar/store', [App\Http\Controllers\PendaftarController::class, 'store'])->name('pendaftar.store');
Route::get('/pendaftar/surat_tawaran', [App\Http\Controllers\PendaftarController::class, 'suratTawaran'])->name('pendaftar.surat_tawaran');
Route::get('/pendaftar/view/{ic}', [App\Http\Controllers\PendaftarController::class, 'view'])->name('pendaftar.view');
Route::get('/pendaftar/edit/{ic}', [App\Http\Controllers\PendaftarController::class, 'edit'])->name('pendaftar.edit');
Route::post('/pendaftar/edit/update', [App\Http\Controllers\PendaftarController::class, 'update'])->name('pendaftar.update');
Route::post('/pendaftar/getProgram', [App\Http\Controllers\PendaftarController::class, 'getProgram'])->name('pendaftar.getProgram');
Route::delete('/pendaftar/delete', [App\Http\Controllers\PendaftarController::class, 'delete'])->name('pendaftar.delete');
Route::post('/pendaftar/group/getSubject', [App\Http\Controllers\PendaftarController::class, 'getSubjectOption']);
Route::post('/pendaftar/group/getStudentTableIndex', [App\Http\Controllers\PendaftarController::class, 'getStudentTableIndex']);
Route::post('/pendaftar/group/getStudentTableIndex2', [App\Http\Controllers\PendaftarController::class, 'getStudentTableIndex2']);
Route::post('/pendaftar/group/getGroupOption', [App\Http\Controllers\PendaftarController::class, 'getGroupOption']);
Route::get('/pendaftar/spm/{ic}', [App\Http\Controllers\PendaftarController::class, 'spmIndex'])->name('pendaftar.spm');
Route::post('/pendaftar/spm/{ic}/store', [App\Http\Controllers\PendaftarController::class, 'spmStore'])->name('pendaftar.spm.store');
Route::post('/pendaftar/spm/{ic}/SPMVstore', [App\Http\Controllers\PendaftarController::class, 'SPMVStore'])->name('pendaftar.spmv.store');
Route::post('/pendaftar/spm/{ic}/SKMstore', [App\Http\Controllers\PendaftarController::class, 'SKMStore'])->name('pendaftar.skm.store');
Route::get('/pendaftar/student/edit', [App\Http\Controllers\PendaftarController::class, 'studentEdit'])->name('pendaftar.student.edit');
Route::get('/pendaftar/student/status', [App\Http\Controllers\PendaftarController::class, 'studentStatus'])->name('pendaftar.student.status');
Route::post('/pendaftar/student/status/listStudent', [App\Http\Controllers\PendaftarController::class, 'getStudentList']);
Route::post('/pendaftar/student/status/getStudent', [App\Http\Controllers\PendaftarController::class, 'getStudentInfo']);
Route::post('/pendaftar/student/status/storeStudent', [App\Http\Controllers\PendaftarController::class, 'storeStudentInfo']);
Route::post('/pendaftar/student/status/generateMatric', [App\Http\Controllers\PendaftarController::class, 'generateMatric']);
Route::get('/pendaftar/student/viewStatus', [App\Http\Controllers\PendaftarController::class, 'viewStatus'])->name('pendaftar.student.viewstatus');
Route::post('/pendaftar/student/status/getReportStd', [App\Http\Controllers\PendaftarController::class, 'getReportStd']);
Route::get('/pendaftar/student/report', [App\Http\Controllers\PendaftarController::class, 'studentReport'])->name('pendaftar.student.studentreport');
Route::post('/pendaftar/student/report/getStudentReport', [App\Http\Controllers\PendaftarController::class, 'getStudentReport']);
Route::get('/pendaftar/student/transcript', [App\Http\Controllers\PendaftarController::class, 'studentTranscript'])->name('pendaftar.student.transcript');
Route::post('/pendaftar/student/transcript/getTranscript', [App\Http\Controllers\PendaftarController::class, 'getTranscript']);
Route::post('/pendaftar/student/transcript/addTranscript', [App\Http\Controllers\PendaftarController::class, 'addTranscript']);
Route::get('/pendaftar/student/result', [App\Http\Controllers\PendaftarController::class, 'studentResult'])->name('pendaftar.student.result');
Route::post('/pendaftar/student/result/getStudentResult', [App\Http\Controllers\PendaftarController::class, 'getStudentResult']);
Route::get('/pendaftar/student/result/overallResult', [App\Http\Controllers\PendaftarController::class, 'overallResult']);
Route::get('/pendaftar/student/reportRs', [App\Http\Controllers\PendaftarController::class, 'studentReportRs'])->name('pendaftar.student.reportR');
Route::get('/pendaftar/student/reportRs/getStudentReportR', [App\Http\Controllers\PendaftarController::class, 'getStudentReportRs']);
Route::get('/pendaftar/student/reportR2', [App\Http\Controllers\PendaftarController::class, 'studentReportR2'])->name('pendaftar.student.reportR2');
Route::get('/pendaftar/student/reportR2/getStudentReportR2', [App\Http\Controllers\PendaftarController::class, 'getStudentReportR2']);
Route::get('/pendaftar/student/incomeReport', [App\Http\Controllers\PendaftarController::class,'incomeReport'])->name('pendaftar.student.incomeReport');
Route::post('/pendaftar/student/incomeReport/getIncomeReport', [App\Http\Controllers\PendaftarController::class,'getIncomeReport']);
Route::get('/pendaftar/student/internationalReport', [App\Http\Controllers\PendaftarController::class,'internationalReport'])->name('pendaftar.student.internationalReport');
Route::get('/pendaftar/student/annualStudentReport', [App\Http\Controllers\PendaftarController::class,'annualStudentReport'])->name('pendaftar.student.annualStudentReport');
Route::post('/pendaftar/student/annualStudentReport/getAnnualStudentReport', [App\Http\Controllers\PendaftarController::class, 'getAnnualStudentReport']);


Route::get('/AR_dashboard', [App\Http\Controllers\AR_Controller::class, 'dashboard'])->name('pendaftar_akademik.dashboard');
Route::get('/AR', [App\Http\Controllers\AR_Controller::class, 'courseList'])->name('pendaftar_akademik');
Route::post('/AR/getCourse', [App\Http\Controllers\AR_Controller::class, 'getCourse']);
Route::post('/AR/course/create', [App\Http\Controllers\AR_Controller::class, 'createCourse']);
Route::delete('/AR/course/delete', [App\Http\Controllers\AR_Controller::class, 'deleteCourse'])->name('pendaftar_akademik.delete');
Route::post('/AR/course/update', [App\Http\Controllers\AR_Controller::class, 'updateCourse']);
Route::get('/AR/assignCourse', [App\Http\Controllers\AR_Controller::class, 'assignCourse'])->name('pendaftar_akademik.assignCourse');
Route::post('/AR/assignCourse/getCourse0', [App\Http\Controllers\AR_Controller::class, 'getCourse0']);
Route::post('/AR/assignCourse/getCourse2', [App\Http\Controllers\AR_Controller::class, 'getCourse2']);
Route::post('/AR/assignCourse/addCourse', [App\Http\Controllers\AR_Controller::class, 'addCourse']);
Route::delete('/AR/assignCourse/deleteCourse2', [App\Http\Controllers\AR_Controller::class, 'deleteCourse2']);
Route::get('/AR/student', [App\Http\Controllers\AR_Controller::class, 'studentCourse'])->name('pendaftar_akademik.student');
Route::get('/AR/student/getStudent', [App\Http\Controllers\AR_Controller::class, 'getStudents']);
Route::get('/AR/student/getCourse', [App\Http\Controllers\AR_Controller::class, 'getCourses']);
Route::post('/AR/student/register', [App\Http\Controllers\AR_Controller::class, 'registerCourse']);
Route::delete('/AR/student/unregister', [App\Http\Controllers\AR_Controller::class, 'unregisterCourse']);
Route::get('/AR/student/getSlipExam', [App\Http\Controllers\AR_Controller::class, 'getSlipExam'])->name('pendaftar_akademik.student.slipExam');
Route::get('/AR/session', [App\Http\Controllers\AR_Controller::class, 'sessionList'])->name('pendaftar_akademik.session');
Route::post('/AR/session/create', [App\Http\Controllers\AR_Controller::class, 'createSession']);
Route::post('/AR/session/update', [App\Http\Controllers\AR_Controller::class, 'updateSession']);
Route::delete('/AR/session/delete', [App\Http\Controllers\AR_Controller::class, 'deleteDelete'])->name('pendaftar_akademik.session.delete');
Route::get('/AR/schedule', [App\Http\Controllers\AR_Controller::class, 'scheduleIndex'])->name('pendaftar_akademik.schedule');
Route::get('/AR/schedule/room', [App\Http\Controllers\AR_Controller::class, 'roomIndex'])->name('pendaftar_akademik.roomIndex');
Route::post('/AR/schedule/room/create', [App\Http\Controllers\AR_Controller::class, 'createRoomIndex']);
Route::post('/AR/schedule/room/update', [App\Http\Controllers\AR_Controller::class, 'updateRoomIndex']);
Route::post('/AR/schedule/room/delete', [App\Http\Controllers\AR_Controller::class, 'deleteRoomIndex']);
Route::post('/AR/schedule/getLectureRoom', [App\Http\Controllers\AR_Controller::class, 'getLectureRoom']);
Route::post('/AR/schedule/createLectureRoom', [App\Http\Controllers\AR_Controller::class, 'createLectureRoom']);
Route::get('/AR/schedule/scheduleTable/{id}', [App\Http\Controllers\AR_Controller::class, 'scheduleTable']);
Route::get('/AR/schedule/scheduleTable/{id}/getSubjectSchedule', [App\Http\Controllers\AR_Controller::class, 'getSubjectSchedule']);
Route::get('/AR/schedule/scheduleTable/{id}/getGroupSchedule', [App\Http\Controllers\AR_Controller::class, 'getGroupSchedule']);
Route::get('/AR/schedule/fetch/{id}', [App\Http\Controllers\AR_Controller::class, 'fetchEvents']);
// Route::get('/AR/schedule/index/old', [App\Http\Controllers\AR_Controller::class, 'scheduleIndex'])->name('pendaftar_akademik.schedule.old');
// Route::post('/AR/schedule/store', [App\Http\Controllers\AR_Controller::class, 'dropzoneStore'])->name('pendaftar_akademik.schedule.store');
Route::post('/AR/schedule/create/{id}', [App\Http\Controllers\AR_Controller::class, 'createEvent']);
Route::put('/AR/schedule/update/{id}', [App\Http\Controllers\AR_Controller::class, 'updateEvent']);
Route::put('/AR/schedule/update2/{id}', [App\Http\Controllers\AR_Controller::class, 'updateEvent2']);
Route::delete('/AR/schedule/delete/{id}', [App\Http\Controllers\AR_Controller::class, 'deleteEvent']);
Route::get('/AR/schedule/scheduleReport', [App\Http\Controllers\AR_Controller::class, 'scheduleReport'])->name('pendaftar_akademik.schedule.report');
Route::get('/AR/leave', [App\Http\Controllers\AR_Controller::class, 'studentLeave'])->name('pendaftar_akademik.leave');
Route::get('/AR/leave/getStudentLeave', [App\Http\Controllers\AR_Controller::class, 'getStudentLeave']);
Route::post('/AR/leave/updateLeave', [App\Http\Controllers\AR_Controller::class, 'updateLeave']);
Route::post('/AR/campus/updatecampus', [App\Http\Controllers\AR_Controller::class, 'updateCampus']);
Route::get('/AR/semester', [App\Http\Controllers\AR_Controller::class, 'studentSemester'])->name('pendaftar_akademik.semester');
Route::get('/AR/semester/getStudentSemester', [App\Http\Controllers\AR_Controller::class, 'getStudentSemester']);
Route::post('/AR/semester/updatesemester', [App\Http\Controllers\AR_Controller::class, 'updateSemester']);
Route::get('/AR/reportR', [App\Http\Controllers\AR_Controller::class, 'studentReportR'])->name('pendaftar_akademik.reportR');
Route::get('/AR/reportR/getStudentReportR', [App\Http\Controllers\AR_Controller::class, 'getStudentReportR']);
Route::get('/AR/student/warningLetter', [App\Http\Controllers\AR_Controller::class, 'warningLetter'])->name('pendaftar_akademik.warningLetter');
Route::post('/AR/student/getWarningLetter', [App\Http\Controllers\AR_Controller::class, 'getWarningLetter']);
Route::get('/AR/student/printWarningLetter', [App\Http\Controllers\AR_Controller::class, 'printWarningLetter']);
Route::get('/AR/student/senateReport', [App\Http\Controllers\AR_Controller::class, 'senateReport'])->name('pendaftar_akademik.senateReport');
Route::post('/AR/student/senateReport/getSenateReport', [App\Http\Controllers\AR_Controller::class, 'getSenateReport']);
Route::get('/AR/student/resultReport', [App\Http\Controllers\AR_Controller::class, 'resultReport'])->name('pendaftar_akademik.resultReport');
Route::post('/AR/student/resultReport/getResultReport', [App\Http\Controllers\AR_Controller::class, 'getResultReport']);
Route::get('/AR/student/studentAssessment', [App\Http\Controllers\AR_Controller::class, 'studentAssessment'])->name('pendaftar_akademik.student.studentAssessment');
Route::get('/AR/student/studentAssessment/getStudentAssessment', [App\Http\Controllers\AR_Controller::class, 'getStudentAssessment']);
Route::get('/AR/student/studentAssessment/assessmentStatus/{id}/{type}', [App\Http\Controllers\AR_Controller::class, 'assessmentStatus']);
Route::post('/AR/student/studentAssessment/assessmentStatus/{id}/{type}/update', [App\Http\Controllers\AR_Controller::class, 'updateAssessmentStatus']);
Route::get('/AR/student/studentAssessment/getSubjectLecturer', [App\Http\Controllers\AR_Controller::class, 'getSubjectLecturer']);
Route::get('/AR/student/studentAssessment/getGroupLecturer', [App\Http\Controllers\AR_Controller::class, 'getGroupLecturer']);
// Route::get('/AR/student/groupTable', [App\Http\Controllers\AR_Controller::class, 'groupTable'])->name('pendaftar_akademik.groupTable');

Route::get('/lecturer/getSuratAmaran', [App\Http\Controllers\LecturerController::class, 'getSuratAmaran'])->name('lecturer.suratamaran');

Route::get('/lecturer', [App\Http\Controllers\LecturerController::class, 'index'])->name('lecturer');
Route::get('/lecturer/setting', [App\Http\Controllers\LecturerController::class, 'setting'])->name('lecturer.setting');
Route::post('/lecturer/update', [App\Http\Controllers\LecturerController::class, 'updateSetting']);
Route::post('/lecturer/update/theme', [App\Http\Controllers\LecturerController::class, 'settingTheme']);
Route::post('/lecturer/course/filter', [App\Http\Controllers\LecturerController::class, 'getCourseList']);
Route::delete('/lecturer/content/delete', [App\Http\Controllers\LecturerController::class, 'deleteContent']);
Route::post('/lecturer/content/rename', [App\Http\Controllers\LecturerController::class, 'renameContent']);
Route::delete('/lecturer/content/folder/delete', [App\Http\Controllers\LecturerController::class, 'deleteFolder']);
Route::post('/lecturer/content/folder/rename', [App\Http\Controllers\LecturerController::class, 'renameFolder']);
Route::delete('/lecturer/content/folder/subfolder/delete', [App\Http\Controllers\LecturerController::class, 'deleteSubfolder']);
Route::delete('/lecturer/content/folder/subfolder/deletefile', [App\Http\Controllers\LecturerController::class, 'deleteSubfolderFile']);
Route::post('/lecturer/content/folder/subfolder/rename', [App\Http\Controllers\LecturerController::class, 'renameSubfolder']);
Route::post('/lecturer/content/folder/subfolder/renameFile', [App\Http\Controllers\LecturerController::class, 'renameFileSubfolder']);
Route::delete('/lecturer/content/folder/subfolder/material/delete', [App\Http\Controllers\LecturerController::class, 'deleteMaterial']);
Route::delete('/lecturer/content/folder/subfolder/material/url/delete', [App\Http\Controllers\LecturerController::class, 'deleteUrl']);
Route::post('/lecturer/content/folder/subfolder/material/renameFile', [App\Http\Controllers\LecturerController::class, 'renameMaterial']);
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
Route::post('/lecturer/content/material/sub/storefile/{dir}', [App\Http\Controllers\LecturerController::class, 'storefileSubDirectory']);
Route::post('/lecturer/content/material/sub/password/{dir}', [App\Http\Controllers\LecturerController::class, 'passwordSubDirectory']);
Route::get('/lecturer/content/material/sub/content/{dir}', [App\Http\Controllers\LecturerController::class, 'DirectoryContent'])->name('lecturer.directory.content');
Route::get('/lecturer/content/material/sub/content/prev/{dir}', [App\Http\Controllers\LecturerController::class, 'prevDirectoryContent'])->name('lecturer.directory.content.prev');
Route::post('/lecturer/content/material/sub/content/upload/{id}', [App\Http\Controllers\LecturerController::class, 'uploadMaterial']);
Route::post('/lecturer/content/material/sub/content/password/{dir}', [App\Http\Controllers\LecturerController::class, 'passwordContent']);
Route::get('/lecturer/{id}', [App\Http\Controllers\LecturerController::class, 'courseSummary'])->name('lecturer.summary');
// Route::get('/lecturer/class/schedule', [App\Http\Controllers\LecturerController::class, 'classSchedule'])->name('lecturer.class.schedule');
Route::get('/lecturer/class/schedule/getGroup', [App\Http\Controllers\LecturerController::class, 'scheduleGetGroup']);
Route::post('/lecturer/class/schedule/getschedule', [App\Http\Controllers\LecturerController::class, 'getSchedule']);
Route::post('/lecturer/class/schedule/insertschedule', [App\Http\Controllers\LecturerController::class, 'scheduleInsertGroup']);
Route::get('/lecturer/class/attendance', [App\Http\Controllers\LecturerController::class, 'classAttendance'])->name('lecturer.class.attendance');
Route::get('/lecturer/class/attendance/getGroup', [App\Http\Controllers\LecturerController::class, 'attendanceGetGroup']);
Route::post('/lecturer/class/attendance/getStudentProgram', [App\Http\Controllers\LecturerController::class, 'getStudentProgram']);
Route::post('/lecturer/class/attendance/getStudents', [App\Http\Controllers\LecturerController::class, 'getStudents']);
Route::post('/lecturer/class/attendance/getDate', [App\Http\Controllers\LecturerController::class, 'getDate']);
Route::post('/lecturer/class/attendance/store', [App\Http\Controllers\LecturerController::class, 'storeAttendance'])->name('lecturer.attendance.store');
Route::get('/lecturer/class/attendance/edit', [App\Http\Controllers\LecturerController::class, 'classAttendanceEdit'])->name('lecturer.class.attendance.edit');
Route::post('/lecturer/class/attendance/edit/update', [App\Http\Controllers\LecturerController::class, 'updateAttendance'])->name('lecturer.attendance.update');
Route::get('/lecturer/class/attendance/report', [App\Http\Controllers\LecturerController::class, 'reportAttendance'])->name('lecturer.attendance.report');
Route::get('/lecturer/class/attendance/print', [App\Http\Controllers\LecturerController::class, 'printAttendance'])->name('lecturer.attendance.print');
//Route::get('/lecturer/class/attendance/report', [App\Http\Controllers\LecturerController::class, 'listAttendance'])->name('lecturer.attendance.report');
//Route::get('/lecturer/class/attendance/report/{date}/{group}', [App\Http\Controllers\LecturerController::class, 'reportAttendance']);
Route::post('/lecturer/class/attendance/deleteAttendance', [App\Http\Controllers\LecturerController::class, 'deleteAttendance']);
Route::get('/lecturer/class/onlineclass', [App\Http\Controllers\LecturerController::class, 'onlineClass'])->name('lecturer.class.onlineclass');
Route::post('lecturer/class/onlineclass/getChapters', [App\Http\Controllers\LecturerController::class, 'getChapters']);
Route::post('lecturer/class/onlineclass/getSubChapters', [App\Http\Controllers\LecturerController::class, 'getSubChapters']);
Route::post('/lecturer/class/onlineclass/store', [App\Http\Controllers\LecturerController::class, 'storeOnlineClass'])->name('lecturer.onlineclass.store');
Route::get('/lecturer/class/onlineclass/list', [App\Http\Controllers\LecturerController::class, 'OnlineClassList'])->name('lecturer.onlineclass.list');
Route::delete('/lecturer/class/onlineclass/list/delete', [App\Http\Controllers\LecturerController::class, 'OnlineClassListDelete'])->name('lecturer.onlineclass.list.delete');
Route::get('/lecturer/class/onlineclass/list/edit/{id}', [App\Http\Controllers\LecturerController::class, 'OnlineClassListEdit'])->name('lecturer.onlineclass.list.edit');
Route::patch('/lecturer/class/onlineclass/list/update/{id}', [App\Http\Controllers\LecturerController::class, 'OnlineClassListUpdate'])->name('lecturer.onlineclass.list.update');

Route::get('/lecturer/library/{id}', [App\Http\Controllers\LecturerController::class, 'libraryIndex'])->name('lecturer.library');
Route::post('/lecturer/library/getFolder', [App\Http\Controllers\LecturerController::class, 'getContent'])->name('lecturer.library.content');
Route::post('/lecturer/library/getSubfolder', [App\Http\Controllers\LecturerController::class, 'getSubFolder']);
Route::post('/lecturer/library/getSubfolder/getSubfolder2', [App\Http\Controllers\LecturerController::class, 'getSubFolder2']);
Route::post('/lecturer/library/getSubfolder/getSubfolder2/getMaterial', [App\Http\Controllers\LecturerController::class, 'getMaterial']);
Route::post('/lecturer/library/getQuiz', [App\Http\Controllers\LecturerController::class, 'getQuiz'])->name('lecturer.library.quiz');
Route::post('/lecturer/library/getTest', [App\Http\Controllers\LecturerController::class, 'getTest'])->name('lecturer.library.test');
Route::post('/lecturer/library/getAssignment', [App\Http\Controllers\LecturerController::class, 'getAssignment'])->name('lecturer.library.assignment');
Route::post('/lecturer/library/getOther', [App\Http\Controllers\LecturerController::class, 'getOther'])->name('lecturer.library.other');
Route::post('/lecturer/library/getExtra', [App\Http\Controllers\LecturerController::class, 'getExtra'])->name('lecturer.library.extra');
Route::post('/lecturer/library/getMidterm', [App\Http\Controllers\LecturerController::class, 'getMidterm'])->name('lecturer.library.midterm');
Route::post('/lecturer/library/getFinal', [App\Http\Controllers\LecturerController::class, 'getFinal'])->name('lecturer.library.final');

Route::get('/lecturer/class/announcement', [App\Http\Controllers\LecturerController::class, 'announcement'])->name('lecturer.class.announcement');
Route::get('/lecturer/class/announcement/getGroupList', [App\Http\Controllers\LecturerController::class, 'announcementGetGroupList']);
Route::post('/lecturer/class/announcement/store', [App\Http\Controllers\LecturerController::class, 'storeAnnouncement'])->name('lecturer.announcement.store');
Route::get('/lecturer/class/announcement/list', [App\Http\Controllers\LecturerController::class, 'announcementList'])->name('lecturer.announcement.list');
Route::delete('/lecturer/class/announcement/list/delete', [App\Http\Controllers\LecturerController::class, 'announcementListDelete'])->name('lecturer.announcement.list.delete');
Route::get('/lecturer/class/announcement/list/edit/{id}', [App\Http\Controllers\LecturerController::class, 'announcementListEdit'])->name('lecturer.announcement.list.edit');
Route::patch('/lecturer/class/announcement/list/update/{id}', [App\Http\Controllers\LecturerController::class, 'announcementListUpdate'])->name('lecturer.announcement.list.update');

Route::get('/lecturer/report/{id}', [App\Http\Controllers\LecturerController::class, 'assessmentreport'])->name('lecturer.report');
Route::get('/lecturer/report/{id}/{student}', [App\Http\Controllers\LecturerController::class, 'studentreport'])->name('lecturer.report.student');
Route::get('/lecturer/class/schedule', [App\Http\Controllers\LecturerController::class, 'classSchedule'])->name('lecturer.class.schedule');
Route::get('/lecturer/class/schedule/fetch', [App\Http\Controllers\LecturerController::class, 'fetchEvents']);


Route::post('/update-data', [App\Http\Controllers\LecturerController::class, 'autoudateData']);

Route::get('/lecturer/quiz/{id}', [App\Http\Controllers\QuizController::class, 'quizlist'])->name('lecturer.quiz');
Route::post('/lecturer/quiz/getextend', [App\Http\Controllers\QuizController::class, 'getExtendQuiz']);
Route::post('/lecturer/quiz/updateExtend', [App\Http\Controllers\QuizController::class, 'updateExtendQuiz']);
Route::get('/lecturer/quiz/{id}/create', [App\Http\Controllers\QuizController::class, 'quizcreate'])->name('lecturer.quiz.create');
Route::post('/lecturer/quiz/insert', [App\Http\Controllers\QuizController::class, 'insertquiz']);
Route::post('/lecturer/quiz/getStatus', [App\Http\Controllers\QuizController::class, 'getStatus']);
Route::post('/lecturer/quiz/updatequizresult', [App\Http\Controllers\QuizController::class, 'updatequizresult']);
Route::get('/lecturer/quiz/{id}/{quiz}', [App\Http\Controllers\QuizController::class, 'lecturerquizstatus'])->name('lecturer.quiz.status');
Route::post('/lecturer/quiz/{id}/{quiz}/getGroup', [App\Http\Controllers\QuizController::class, 'quizGetGroup']);
Route::delete('/lecturer/quiz/status/delete', [App\Http\Controllers\QuizController::class, 'deletequizstatus']);
Route::get('/lecturer/quiz/{quizid}/{userid}/result', [App\Http\Controllers\QuizController::class, 'quizresult']);
Route::post('/lecturer/quiz/getChapters', [App\Http\Controllers\QuizController::class, 'getChapters']);
Route::post('/lecturer/quiz/deletequiz', [App\Http\Controllers\QuizController::class, 'deletequiz']);

Route::get('/lecturer/quiz2/{id}', [App\Http\Controllers\QuizController::class, 'quiz2list'])->name('lecturer.quiz2');
Route::get('/lecturer/quiz2/{id}/create', [App\Http\Controllers\QuizController::class, 'quiz2create'])->name('lecturer.quiz2.create');
Route::post('/lecturer/quiz2/insert', [App\Http\Controllers\QuizController::class, 'insertquiz2']);
Route::post('/lecturer/quiz2/update', [App\Http\Controllers\QuizController::class, 'updatequiz2']);
Route::post('/lecturer/quiz2/getStatus', [App\Http\Controllers\QuizController::class, 'getStatus']);
Route::post('/lecturer/quiz2/updatequiz2result', [App\Http\Controllers\QuizController::class, 'updatequiz2result']);
Route::get('/lecturer/quiz2/{id}/{quiz}', [App\Http\Controllers\QuizController::class, 'lecturerquiz2status'])->name('lecturer.quiz2.status');
Route::post('/lecturer/quiz2/{id}/{quiz}/getGroup', [App\Http\Controllers\QuizController::class, 'quiz2GetGroup']);
Route::get('/lecturer/quiz2/{quizid}/{userid}/result', [App\Http\Controllers\QuizController::class, 'quiz2result']);
Route::post('/lecturer/quiz2/getChapters', [App\Http\Controllers\QuizController::class, 'getChapters']);

//Route::get('/lecturer/quiz2/{id}', [App\Http\Controllers\QuizController::class, 'quiz2list'])->name('lecturer.quiz2');
//Route::get('/lecturer/quiz2/{id}/create', [App\Http\Controllers\QuizController::class, 'quiz2create'])->name('lecturer.quiz2.create');
//Route::post('/lecturer/quiz2/insert', [App\Http\Controllers\QuizController::class, 'insertquiz2']);

//Route::post('/lecturer/quiz2/updatequiz2result', [App\Http\Controllers\QuizController::class, 'updatequiz2result']);
//Route::get('/lecturer/quiz2/{id}/{quiz2}', [App\Http\Controllers\QuizController::class, 'lecturerquiz2status'])->name('lecturer.quiz2.status');
//Route::get('/lecturer/quiz2/{quizid}/{userid}/result', [App\Http\Controllers\QuizController::class, 'quiz2result']);


Route::get('/lecturer/test/{id}', [App\Http\Controllers\TestController::class, 'testlist'])->name('lecturer.test');
Route::post('/lecturer/test/getextend', [App\Http\Controllers\TestController::class, 'getExtendTest']);
Route::post('/lecturer/test/updateExtend', [App\Http\Controllers\TestController::class, 'updateExtendTest']);
Route::get('/lecturer/test/{id}/create', [App\Http\Controllers\TestController::class, 'testcreate'])->name('lecturer.test.create');
Route::post('/lecturer/test/insert', [App\Http\Controllers\TestController::class, 'inserttest']);
Route::post('/lecturer/test/getStatus', [App\Http\Controllers\TestController::class, 'getStatus']);
Route::post('/lecturer/test/updatetestresult', [App\Http\Controllers\TestController::class, 'updatetestresult']);
Route::get('/lecturer/test/{id}/{test}', [App\Http\Controllers\TestController::class, 'lecturerteststatus'])->name('lecturer.test.status');
Route::post('/lecturer/test/{id}/{test}/getGroup', [App\Http\Controllers\TestController::class, 'testGetGroup']);
Route::delete('/lecturer/test/status/delete', [App\Http\Controllers\TestController::class, 'deleteteststatus']);
Route::get('/lecturer/test/{testid}/{userid}/result', [App\Http\Controllers\TestController::class, 'testresult']);
Route::post('/lecturer/test/getChapters', [App\Http\Controllers\TestController::class, 'getChapters']);
Route::post('/lecturer/test/deletetest', [App\Http\Controllers\TestController::class, 'deletetest']);

Route::get('/lecturer/test2/{id}', [App\Http\Controllers\TestController::class, 'test2list'])->name('lecturer.test2');
Route::get('/lecturer/test2/{id}/create', [App\Http\Controllers\TestController::class, 'test2create'])->name('lecturer.test2.create');
Route::post('/lecturer/test2/insert', [App\Http\Controllers\TestController::class, 'inserttest2']);
Route::post('/lecturer/test2/update', [App\Http\Controllers\TestController::class, 'updatetest2']);
Route::post('/lecturer/test2/getStatus', [App\Http\Controllers\TestController::class, 'getStatus']);
Route::post('/lecturer/test2/updatetest2result', [App\Http\Controllers\TestController::class, 'updatetest2result']);
Route::get('/lecturer/test2/{id}/{test}', [App\Http\Controllers\TestController::class, 'lecturertest2status'])->name('lecturer.test2.status');
Route::post('/lecturer/test2/{id}/{test}/getGroup', [App\Http\Controllers\TestController::class, 'test2GetGroup']);
Route::get('/lecturer/test2/{testid}/{userid}/result', [App\Http\Controllers\TestController::class, 'test2result']);
Route::post('/lecturer/test2/getChapters', [App\Http\Controllers\TestController::class, 'getChapters']);


Route::get('/lecturer/assign/{id}', [App\Http\Controllers\AssignmentController::class, 'assignlist'])->name('lecturer.assign');
Route::get('/lecturer/assign/{id}/create', [App\Http\Controllers\AssignmentController::class, 'assigncreate'])->name('lecturer.assign.create');
Route::post('/lecturer/assign/insert', [App\Http\Controllers\AssignmentController::class, 'insertassign']);
Route::post('/lecturer/assign/getStatus', [App\Http\Controllers\AssignmentController::class, 'getStatus']);
Route::post('/lecturer/assign/updateassignresult', [App\Http\Controllers\AssignmentController::class, 'updateassignresult']);
Route::get('/lecturer/assign/{id}/{assign}', [App\Http\Controllers\AssignmentController::class, 'lecturerassignstatus'])->name('lecturer.assign.status');
Route::post('/lecturer/assign/{id}/{assign}/getGroup', [App\Http\Controllers\AssignmentController::class, 'assignGetGroup']);
Route::delete('/lecturer/assign/status/delete', [App\Http\Controllers\AssignmentController::class, 'deleteassignstatus']);
Route::get('/lecturer/assign/{assignid}/{userid}/result', [App\Http\Controllers\AssignmentController::class, 'assignresult']);
Route::post('/lecturer/assign/getChapters', [App\Http\Controllers\AssignmentController::class, 'getChapters']);
Route::post('/lecturer/assign/deleteassign', [App\Http\Controllers\AssignmentController::class, 'deleteassign']);

Route::get('/lecturer/assign2/{id}', [App\Http\Controllers\AssignmentController::class, 'assign2list'])->name('lecturer.assign2');
Route::post('/lecturer/assign2/{id}/{assign}/getGroup', [App\Http\Controllers\AssignmentController::class, 'assign2GetGroup']);
Route::get('/lecturer/assign2/{id}/create', [App\Http\Controllers\AssignmentController::class, 'assign2create'])->name('lecturer.assign2.create');
Route::post('/lecturer/assign2/insert', [App\Http\Controllers\AssignmentController::class, 'insertassign2']);
Route::post('/lecturer/assign2/update', [App\Http\Controllers\AssignmentController::class, 'updateassign2']);

Route::post('/lecturer/assign2/updateassign2result', [App\Http\Controllers\AssignmentController::class, 'updateassign2result']);
Route::get('/lecturer/assign2/{id}/{assign}', [App\Http\Controllers\AssignmentController::class, 'lecturerassign2status'])->name('lecturer.assign2.status');
Route::post('/lecturer/assign2/getChapters', [App\Http\Controllers\AssignmentController::class, 'getChapters']);


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
Route::post('/lecturer/other/update', [App\Http\Controllers\OtherController::class, 'updateother']);
Route::post('/lecturer/other/deleteother', [App\Http\Controllers\OtherController::class, 'deleteother']);

Route::post('/lecturer/other/updateotherresult', [App\Http\Controllers\OtherController::class, 'updateotherresult']);
Route::get('/lecturer/other/{id}/{other}', [App\Http\Controllers\OtherController::class, 'lecturerotherstatus'])->name('lecturer.other.status');
Route::post('/lecturer/other/{id}/{other}/getGroup', [App\Http\Controllers\OtherController::class, 'otherGetGroup']);
Route::get('/lecturer/other/{otherid}/{userid}/result', [App\Http\Controllers\OtherController::class, 'otherresult']);
Route::post('/lecturer/other/getChapters', [App\Http\Controllers\OtherController::class, 'getChapters']);


Route::get('/lecturer/midterm/{id}', [App\Http\Controllers\MidtermController::class, 'midtermlist'])->name('lecturer.midterm');
Route::get('/lecturer/midterm/{id}/create', [App\Http\Controllers\MidtermController::class, 'midtermcreate'])->name('lecturer.midterm.create');
Route::post('/lecturer/midterm/insert', [App\Http\Controllers\MidtermController::class, 'insertmidterm']);
Route::post('/lecturer/midterm/update', [App\Http\Controllers\MidtermController::class, 'updatemidterm']);
Route::post('/lecturer/midterm/deletemidterm', [App\Http\Controllers\MidtermController::class, 'deletemidterm']);

Route::get('/lecturer/midterm/{id}/{midterm}', [App\Http\Controllers\MidtermController::class, 'lecturermidtermstatus'])->name('lecturer.midterm.status');
Route::post('/lecturer/midterm/{id}/{midterm}/getGroup', [App\Http\Controllers\MidtermController::class, 'midtermGetGroup']);
Route::post('/lecturer/midterm/getChapters', [App\Http\Controllers\MidtermController::class, 'getChapters']);


Route::get('/lecturer/final/{id}', [App\Http\Controllers\FinalController::class, 'finallist'])->name('lecturer.final');
Route::get('/lecturer/final/{id}/create', [App\Http\Controllers\FinalController::class, 'finalcreate'])->name('lecturer.final.create');
Route::post('/lecturer/final/insert', [App\Http\Controllers\FinalController::class, 'insertfinal']);
Route::post('/lecturer/final/update', [App\Http\Controllers\FinalController::class, 'updatefinal']);
Route::post('/lecturer/final/deletefinal', [App\Http\Controllers\FinalController::class, 'deletefinal']);

Route::get('/lecturer/final/{id}/{final}', [App\Http\Controllers\FinalController::class, 'lecturerfinalstatus'])->name('lecturer.final.status');
Route::post('/lecturer/final/{id}/{final}/getGroup', [App\Http\Controllers\FinalController::class, 'finalGetGroup']);
Route::post('/lecturer/final/getChapters', [App\Http\Controllers\FinalController::class, 'getChapters']);


Route::get('/lecturer/extra/{id}', [App\Http\Controllers\ExtraController::class, 'extralist'])->name('lecturer.extra');
Route::get('/lecturer/extra/{id}/create', [App\Http\Controllers\ExtraController::class, 'extracreate'])->name('lecturer.extra.create');
Route::post('/lecturer/extra/insert', [App\Http\Controllers\ExtraController::class, 'insertextra']);
Route::post('/lecturer/extra/update', [App\Http\Controllers\ExtraController::class, 'updateextra']);
Route::post('/lecturer/extra/deleteextra', [App\Http\Controllers\ExtraController::class, 'deleteextra']);

Route::post('/lecturer/extra/updateextraresult', [App\Http\Controllers\ExtraController::class, 'updateextraresult']);
Route::get('/lecturer/extra/{id}/{extra}', [App\Http\Controllers\ExtraController::class, 'lecturerextrastatus'])->name('lecturer.extra.status');
Route::post('/lecturer/extra/{id}/{extra}/getGroup', [App\Http\Controllers\ExtraController::class, 'extraGetGroup']);
Route::get('/lecturer/extra/{extraid}/{userid}/result', [App\Http\Controllers\ExtraController::class, 'extraresult']);
Route::post('/lecturer/extra/getChapters', [App\Http\Controllers\ExtraController::class, 'getChapters']);

Route::get('/lecturer/forum/{id}', [App\Http\Controllers\ForumController::class, 'lectForum'])->name('lecturer.forum');
Route::post('/lecturer/forum/{id}/insert', [App\Http\Controllers\ForumController::class, 'insertTopic']);
Route::post('/lecturer/forum/{id}/topic/insert', [App\Http\Controllers\ForumController::class, 'insertForum']);


Route::get('/student', [App\Http\Controllers\StudentController::class, 'index'])->name('student');
Route::get('/student/setting', [App\Http\Controllers\StudentController::class, 'setting'])->name('student.setting');
Route::post('/student/update', [App\Http\Controllers\StudentController::class, 'updateSetting']);
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
Route::get('/student/class/announcement/list', [App\Http\Controllers\StudentController::class, 'AnnouncementList'])->name('student.announcement.list');
Route::get('/student/affair/statement', [App\Http\Controllers\StudentController::class, 'studentStatement'])->name('student.affair.statement');
// Route::get('/student/affair/result', [App\Http\Controllers\StudentController::class, 'studentResult'])->name('student.affair.result');


Route::get('/student/report/{id}', [App\Http\Controllers\StudentController::class, 'studentreport'])->name('student.report.student');

Route::get('/student/quiz/{id}', [App\Http\Controllers\QuizController::class, 'studentquizlist'])->name('student.quiz');
Route::get('/student/quiz/{id}/{quiz}', [App\Http\Controllers\QuizController::class, 'studentquizstatus'])->name('student.quiz.status');
Route::get('/student/quiz/{id}/{quiz}/view', [App\Http\Controllers\QuizController::class, 'quizview']);
Route::get('/student/quiz/{quizid}/{userid}/result', [App\Http\Controllers\QuizController::class, 'quizresultstd']);
Route::post('/student/quiz/startquiz', [App\Http\Controllers\QuizController::class, 'startquiz']);
Route::post('/student/quiz/savequiz', [App\Http\Controllers\QuizController::class, 'savequiz']);
Route::post('/student/quiz/submitquiz', [App\Http\Controllers\QuizController::class, 'submitquiz']);

Route::get('/student/quiz2/{id}', [App\Http\Controllers\QuizController::class, 'studentquiz2list'])->name('student.quiz2');

Route::get('/student/test/{id}', [App\Http\Controllers\TestController::class, 'studenttestlist'])->name('student.test');
Route::get('/student/test/{id}/{test}', [App\Http\Controllers\TestController::class, 'studentteststatus'])->name('student.test.status');
Route::get('/student/test/{id}/{test}/view', [App\Http\Controllers\TestController::class, 'testview']);
Route::get('/student/test/{testid}/{userid}/result', [App\Http\Controllers\TestController::class, 'testresultstd']);
Route::post('/student/test/starttest', [App\Http\Controllers\TestController::class, 'starttest']);
Route::post('/student/test/savetest', [App\Http\Controllers\TestController::class, 'savetest']);
Route::post('/student/test/submittest', [App\Http\Controllers\TestController::class, 'submittest']);

Route::get('/student/test2/{id}', [App\Http\Controllers\TestController::class, 'studenttest2list'])->name('student.test2');


Route::get('/student/assign/{id}', [App\Http\Controllers\AssignmentController::class, 'studentassignlist'])->name('student.assign');
Route::get('/student/assign/{id}/{assign}', [App\Http\Controllers\AssignmentController::class, 'studentassignstatus'])->name('student.assign.status');
Route::get('/student/assign/{id}/{assign}/view', [App\Http\Controllers\AssignmentController::class, 'assignview']);
Route::get('/student/assign/{assignid}/{userid}/result', [App\Http\Controllers\AssignmentController::class, 'assignresultstd']);
Route::post('/student/assign/submitassign', [App\Http\Controllers\AssignmentController::class, 'submitassign']);

Route::get('/student/assign2/{id}', [App\Http\Controllers\AssignmentController::class, 'studentassign2list'])->name('student.assign2');

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

Route::get('/student/forum/{id}', [App\Http\Controllers\ForumController::class, 'studForum'])->name('student.forum');
Route::post('/student/forum/{id}/insert', [App\Http\Controllers\ForumController::class, 'studinsertTopic']);
Route::post('/student/forum/{id}/topic/insert', [App\Http\Controllers\ForumController::class, 'studinsertForum']);

Route::get('/student/warning/{id}', [App\Http\Controllers\StudentController::class, 'warningLetter'])->name('student.warning');
Route::get('/student/warning/{id}/getWarningLetter', [App\Http\Controllers\StudentController::class, 'getWarningLetter']);

Route::get('/finance_dashboard', [App\Http\Controllers\FinanceController::class, 'dashboard'])->name('finance.dashboard');
Route::get('/finance', [App\Http\Controllers\FinanceController::class, 'index'])->name('finance');
Route::post('/finance/claim/create', [App\Http\Controllers\FinanceController::class, 'createClaim']);
Route::post('/finance/claim/update', [App\Http\Controllers\FinanceController::class, 'updateClaim']);
Route::post('/finance/claim/delete', [App\Http\Controllers\FinanceController::class, 'deleteClaim']);
Route::get('/finance/claimpackage', [App\Http\Controllers\FinanceController::class, 'claimPackage'])->name('claimpackage');
Route::post('/finance/claimpackage/getclaim', [App\Http\Controllers\FinanceController::class, 'getClaim']);
Route::post('/finance/claimpackage/addclaim', [App\Http\Controllers\FinanceController::class, 'addClaim']);
Route::post('/finance/claimpackage/copyclaim', [App\Http\Controllers\FinanceController::class, 'copyClaim']);
Route::post('/finance/claimpackage/update', [App\Http\Controllers\FinanceController::class, 'updatePackage']);
Route::post('/finance/claimpackage/delete', [App\Http\Controllers\FinanceController::class, 'deletePackage']);
Route::get('/finance/payment', [App\Http\Controllers\FinanceController::class, 'studentPayment'])->name('finance.payment');
Route::post('/finance/payment/getStudent', [App\Http\Controllers\FinanceController::class, 'getStudentPayment']);
Route::post('/finance/payment/storePayment', [App\Http\Controllers\FinanceController::class, 'storePayment']);
Route::post('/finance/payment/storePaymentDtl', [App\Http\Controllers\FinanceController::class, 'storePaymentDtl']);
Route::post('/finance/payment/deletePayment', [App\Http\Controllers\FinanceController::class, 'deletePayment']);
Route::post('/finance/payment/confirmPayment', [App\Http\Controllers\FinanceController::class, 'confirmPayment']);
Route::get('/finance/payment/claim', [App\Http\Controllers\FinanceController::class, 'studentClaim'])->name('finance.payment.claim');
Route::post('/finance/payment/claim/getStudent', [App\Http\Controllers\FinanceController::class, 'getStudentClaim']);
Route::post('/finance/payment/claim/registerClaim', [App\Http\Controllers\FinanceController::class, 'registerClaim']);
Route::post('/finance/payment/claim/addStudentClaim', [App\Http\Controllers\FinanceController::class, 'addStudentClaim']);
Route::post('/finance/payment/claim/deleteStudentClaim', [App\Http\Controllers\FinanceController::class, 'deleteStudentClaim']);
Route::post('/finance/payment/claim/confirmClaim', [App\Http\Controllers\FinanceController::class, 'confirmClaim']);
Route::get('/finance/payment/tuition', [App\Http\Controllers\FinanceController::class, 'studentTuition'])->name('finance.payment.tuition');
Route::post('/finance/payment/tuition/getStudent', [App\Http\Controllers\FinanceController::class, 'getStudentTuition']);
Route::post('/finance/payment/tuition/storeTuition', [App\Http\Controllers\FinanceController::class, 'storeTuition']);
Route::post('/finance/payment/tuition/storeTuitionDtl', [App\Http\Controllers\FinanceController::class, 'storeTuitionDtl']);
Route::post('/finance/payment/tuition/confirmTuition', [App\Http\Controllers\FinanceController::class, 'confirmTuition']);
Route::post('/finance/payment/tuition/deleteTuition', [App\Http\Controllers\FinanceController::class, 'deleteTuition']);
Route::get('/finance/payment/incentive', [App\Http\Controllers\FinanceController::class, 'studentIncentive'])->name('finance.payment.incentive');
Route::post('/finance/payment/incentive/getStudent', [App\Http\Controllers\FinanceController::class, 'getStudentIncentive']);
Route::post('/finance/payment/incentive/storeIncentive2', [App\Http\Controllers\FinanceController::class, 'storeIncentive2']);
Route::post('/finance/payment/incentive/storeIncentiveDtl', [App\Http\Controllers\FinanceController::class, 'storeIncentiveDtl']);
Route::post('/finance/payment/incentive/confirmIncentive', [App\Http\Controllers\FinanceController::class, 'confirmIncentive']);
Route::post('/finance/payment/incentive/deleteIncentive', [App\Http\Controllers\FinanceController::class, 'deleteIncentive']);
Route::get('/finance/payment/refund', [App\Http\Controllers\FinanceController::class, 'studentRefund'])->name('finance.payment.refund');
Route::post('/finance/payment/refund/getStudent', [App\Http\Controllers\FinanceController::class, 'getStudentRefund']);
Route::post('/finance/payment/refund/storeRefund', [App\Http\Controllers\FinanceController::class, 'storeRefund']);
Route::post('/finance/payment/refund/storeRefundDtl', [App\Http\Controllers\FinanceController::class, 'storeRefundDtl']);
Route::post('/finance/payment/refund/confirmRefund', [App\Http\Controllers\FinanceController::class, 'confirmRefund']);
Route::post('/finance/payment/refund/deleteRefund', [App\Http\Controllers\FinanceController::class, 'deleteRefund']);
Route::get('/finance/payment/KWSPrefund', [App\Http\Controllers\FinanceController::class, 'studentKWSPRefund'])->name('finance.payment.KWSPrefund');
Route::post('/finance/payment/KWSPrefund/getStudent', [App\Http\Controllers\FinanceController::class, 'getStudentKWSPrefund']);
Route::post('/finance/payment/KWSPrefund/storeKWSPrefund', [App\Http\Controllers\FinanceController::class, 'storeKWSPrefund']);
Route::post('/finance/payment/KWSPrefund/deleteKWSPrefund', [App\Http\Controllers\FinanceController::class, 'deleteKWSPrefund']);
Route::get('/finance/sponsorship/library', [App\Http\Controllers\FinanceController::class, 'sponsorLibrary'])->name('sponsorship.library');
Route::post('/finance/sponsorship/library/create', [App\Http\Controllers\FinanceController::class, 'createSponsor']);
Route::post('/finance/sponsorship/library/update', [App\Http\Controllers\FinanceController::class, 'updateSponsor']);
Route::post('/finance/sponsorship/library/delete', [App\Http\Controllers\FinanceController::class, 'deleteSponsor']);
Route::get('/finance/sponsorship/library/payment', [App\Http\Controllers\FinanceController::class, 'paymentSponsor'])->name('sponsorship.payment');
Route::get('/finance/sponsorship/library/payment/input', [App\Http\Controllers\FinanceController::class, 'paymentSponsorInput'])->name('sponsorship.payment.input');
Route::post('/finance/sponsorship/library/payment/input/store', [App\Http\Controllers\FinanceController::class, 'paymentSponsorStore']);
Route::post('/finance/sponsorship/library/payment/input/store2', [App\Http\Controllers\FinanceController::class, 'paymentSponsorStore2']);
Route::post('/finance/sponsorship/library/payment/input/delete', [App\Http\Controllers\FinanceController::class, 'paymentSponsorDelete']);
Route::post('/finance/sponsorship/library/payment/input/confirm', [App\Http\Controllers\FinanceController::class, 'paymentSponsorConfirm']);
Route::get('/finance/sponsorship/payment/student', [App\Http\Controllers\FinanceController::class, 'paymentStudent'])->name('sponsorship.payment.student');
Route::post('/finance/sponsorship/payment/student/getStudent', [App\Http\Controllers\FinanceController::class, 'getStudentSponsor']);
Route::post('/finance/sponsorship/payment/student/storeStudent', [App\Http\Controllers\FinanceController::class, 'storeStudent']);
Route::post('/finance/sponsorship/payment/student/confirmStudent', [App\Http\Controllers\FinanceController::class, 'confirmStudent']);
Route::get('/finance/sponsorship/payment/getReceipt', [App\Http\Controllers\FinanceController::class, 'getReceipt'])->name('receipt');
Route::get('/finance/sponsorship/payment/getReceipt2', [App\Http\Controllers\FinanceController::class, 'getReceipt2'])->name('receipt2');
Route::get('/finance/sponsorship/payment/getReceipt3', [App\Http\Controllers\FinanceController::class, 'getReceipt3'])->name('receipt3');
Route::get('/finance/sponsorship/payment/report', [App\Http\Controllers\FinanceController::class, 'sponsorReport'])->name('finance.payment.report');
Route::post('/finance/sponsorship/payment/report/getReport', [App\Http\Controllers\FinanceController::class, 'sponsorGetReport']);
Route::get('/finance/sponsorship/payment/report/showReportStudent', [App\Http\Controllers\FinanceController::class, 'showReportStudent']);
Route::get('/finance/report/statement', [App\Http\Controllers\FinanceController::class, 'studentStatement'])->name('finance.statement');
Route::post('/finance/report/statement/getStudent', [App\Http\Controllers\FinanceController::class, 'statementGetStudent']);
Route::get('/finance/report/receiptlist', [App\Http\Controllers\FinanceController::class, 'receiptList'])->name('finance.receiptList');
Route::post('/finance/report/receiptlist/getReceiptList', [App\Http\Controllers\FinanceController::class, 'getReceiptList']);
Route::get('/finance/report/receiptlist/getReceiptProof', [App\Http\Controllers\FinanceController::class, 'getReceiptProof']);
Route::get('/finance/report/dailyreport', [App\Http\Controllers\FinanceController::class, 'dailyReport'])->name('finance.dailyReport');
Route::get('/finance/report/dailyreport/getDailyReport', [App\Http\Controllers\FinanceController::class, 'getDailyReport']);
Route::get('/finance/report/chargeReport', [App\Http\Controllers\FinanceController::class, 'chargeReport'])->name('finance.chargeReport');
Route::get('/finance/report/chargeReport/getChargeReport', [App\Http\Controllers\FinanceController::class, 'getChargeReport']);
Route::get('finance/report/arrearsReport', [App\Http\Controllers\FinanceController::class,'arrearsReport'])->name('finance.arrearsReport');
Route::post('finance/report/arrearsReport/getArrearsReport', [App\Http\Controllers\FinanceController::class,'getArrearsReport']);
Route::get('finance/report/urReport', [App\Http\Controllers\FinanceController::class,'urReport'])->name('finance.urReport');
Route::post('finance/report/urReport/getUrReport', [App\Http\Controllers\FinanceController::class,'getUrReport']);
Route::get('finance/report/agingReport', [App\Http\Controllers\FinanceController::class,'agingReport'])->name('finance.agingReport');
Route::post('finance/report/agingReport/getAgingReport', [App\Http\Controllers\FinanceController::class,'getAgingReport']);
Route::get('finance/report/programAgingReport', [App\Http\Controllers\FinanceController::class,'programAgingReport'])->name('finance.programAgingReport');
Route::post('finance/report/programAgingReport/getProgramAgingReport', [App\Http\Controllers\FinanceController::class,'getProgramAgingReport']);
Route::get('finance/report/statusAgingReport', [App\Http\Controllers\FinanceController::class,'statusAgingReport'])->name('finance.statusAgingReport');
Route::post('finance/report/statusAgingReport/getStatusAgingReport', [App\Http\Controllers\FinanceController::class,'getStatusAgingReport']);
Route::get('finance/report/studentArrearsReport', [App\Http\Controllers\FinanceController::class,'studentArrearsReport'])->name('finance.studentArrearsReport');
Route::post('finance/report/studentArrearsReport/getStudentArrearsReport', [App\Http\Controllers\FinanceController::class,'getStudentArrearsReport']);
Route::get('/finance/payment/other', [App\Http\Controllers\FinanceController::class, 'studentOtherPayment'])->name('finance.payment.other');
Route::post('/finance/payment/other/getStudent', [App\Http\Controllers\FinanceController::class, 'getOtherStudentPayment']);
Route::post('/finance/payment/other/storePayment', [App\Http\Controllers\FinanceController::class, 'storeOtherPayment']);
Route::post('/finance/payment/other/storePaymentDtl', [App\Http\Controllers\FinanceController::class, 'storeOtherPaymentDtl']);
Route::post('/finance/payment/other/deletePayment', [App\Http\Controllers\FinanceController::class, 'deleteOtherPayment']);
Route::post('/finance/payment/other/confirmPayment', [App\Http\Controllers\FinanceController::class, 'confirmOtherPayment']);
Route::get('/finance/payment/cancel', [App\Http\Controllers\FinanceController::class, 'cancelTransaction'])->name('finance.payment.cancel');
Route::post('/finance/payment/cancel/confirm', [App\Http\Controllers\FinanceController::class, 'cancelTransactionConfirm']);
Route::get('/finance/package/incentive', [App\Http\Controllers\FinanceController::class, 'incentive'])->name('finance.package.incentive');
Route::get('/finance/package/incentive/getIncentive', [App\Http\Controllers\FinanceController::class, 'getIncentive']);
Route::post('/finance/package/incentive/storeIncentive', [App\Http\Controllers\FinanceController::class, 'storeIncentive']);
Route::post('/finance/package/incentive/getProgram', [App\Http\Controllers\FinanceController::class, 'getProgram']);
Route::post('/finance/package/incentive/registerPRG', [App\Http\Controllers\FinanceController::class, 'registerPRG']);
Route::post('/finance/package/incentive/unregisterPRG', [App\Http\Controllers\FinanceController::class, 'unregisterPRG']);
Route::get('/finance/package/tabungkhas', [App\Http\Controllers\FinanceController::class, 'tabungkhas'])->name('finance.package.tabungkhas');
Route::get('/finance/package/tabungkhas/getTabungkhas', [App\Http\Controllers\FinanceController::class, 'getTabungkhas']);
Route::post('/finance/package/tabungkhas/storeTabungkhas', [App\Http\Controllers\FinanceController::class, 'storeTabungkhas']);
Route::post('/finance/package/tabungkhas/getProgram2', [App\Http\Controllers\FinanceController::class, 'getProgram2']);
Route::post('/finance/package/tabungkhas/registerPRG2', [App\Http\Controllers\FinanceController::class, 'registerPRG2']);
Route::post('/finance/package/tabungkhas/unregisterPRG2', [App\Http\Controllers\FinanceController::class, 'unregisterPRG2']);
Route::get('/finance/package/insentifkhas', [App\Http\Controllers\FinanceController::class, 'insentifkhas'])->name('finance.package.insentifkhas');
Route::get('/finance/package/insentifkhas/getInsentifkhas', [App\Http\Controllers\FinanceController::class, 'getInsentifkhas']);
Route::post('/finance/package/insentifkhas/storeInsentifkhas', [App\Http\Controllers\FinanceController::class, 'storeInsentifkhas']);
Route::post('/finance/package/insentifkhas/getProgram3', [App\Http\Controllers\FinanceController::class, 'getProgram3']);
Route::post('/finance/package/insentifkhas/registerPRG3', [App\Http\Controllers\FinanceController::class, 'registerPRG3']);
Route::post('/finance/package/insentifkhas/unregisterPRG3', [App\Http\Controllers\FinanceController::class, 'unregisterPRG3']);
Route::get('/finance/package/sponsorPackage', [App\Http\Controllers\FinanceController::class, 'sponsorPackage'])->name('finance.package.sponsorPackage');
Route::get('/finance/package/sponsorPackage/getsponsorPackage', [App\Http\Controllers\FinanceController::class, 'getsponsorPackage']);
Route::post('/finance/package/sponsorPackage/storeSponsorPackage', [App\Http\Controllers\FinanceController::class, 'storeSponsorPackage']);
Route::post('/finance/package/sponsorPackage/getEditPackage', [App\Http\Controllers\FinanceController::class, 'getEditPackage']);
Route::post('/finance/package/sponsorPackage/updateSponsorPackage', [App\Http\Controllers\FinanceController::class, 'updateSponsorPackage']);
Route::delete('/finance/package/sponsorPackage/deleteSponsorPackage', [App\Http\Controllers\FinanceController::class, 'deleteSponsorPackage']);
Route::get('/finance/package/payment', [App\Http\Controllers\FinanceController::class, 'Payment'])->name('finance.package.payment');
Route::get('/finance/package/payment/getPayment', [App\Http\Controllers\FinanceController::class, 'getPayment']);
Route::post('/finance/package/payment/storePaymentPKG', [App\Http\Controllers\FinanceController::class, 'storePaymentPKG']);
Route::post('/finance/package/payment/getProgramPayment', [App\Http\Controllers\FinanceController::class, 'getProgramPayment']);
Route::post('/finance/package/payment/registerPRGPYM', [App\Http\Controllers\FinanceController::class, 'registerPRGPYM']);
Route::post('/finance/package/payment/deletePRGPYM', [App\Http\Controllers\FinanceController::class, 'deletePRGPYM']);
Route::get('/finance/voucher/student', [App\Http\Controllers\FinanceController::class, 'studentVoucher'])->name('finance.voucher.student');
Route::post('/finance/voucher/student/getStudent', [App\Http\Controllers\FinanceController::class, 'getStudentVoucher']);
Route::post('/finance/voucher/student/storeVoucherDtl', [App\Http\Controllers\FinanceController::class, 'storeVoucherDtl']);
Route::post('/finance/voucher/student/deleteVoucherDtl', [App\Http\Controllers\FinanceController::class, 'deleteVoucherDtl']);
Route::post('/finance/voucher/student/claimVoucherDtl', [App\Http\Controllers\FinanceController::class, 'claimVoucherDtl']);
Route::post('/finance/voucher/student/unclaimVoucherDtl', [App\Http\Controllers\FinanceController::class, 'unclaimVoucherDtl']);
Route::get('/finance/debt/claimLog', [App\Http\Controllers\FinanceController::class,'claimLog'])->name('finance.claimLog');
Route::get('/finance/debt/claimLog/{ic}', [App\Http\Controllers\FinanceController::class,'studentClaimLog'])->name('finance.studentClaimLog');
Route::post('/finance/debt/claimLog/getClaimLog', [App\Http\Controllers\FinanceController::class,'getClaimLog']);
Route::post('/finance/debt/claimLog/storeNote', [App\Http\Controllers\FinanceController::class,'storeNote']);
Route::post('/finance/debt/claimLog/storeStudentLog/{ic}', [App\Http\Controllers\FinanceController::class,'storeStudentLog']);
Route::post('/finance/debt/claimLog/deleteStudentLog', [App\Http\Controllers\FinanceController::class,'deleteStudentLog']);
Route::get('/finance/debt/collectionReport', [App\Http\Controllers\FinanceController::class,'collectionReport'])->name('finance.collectionReport');
Route::post('/finance/debt/collectionReport/getCollectionReport', [App\Http\Controllers\FinanceController::class,'getCollectionReport']);
Route::get('/finance/debt/collectionExpectReport', [App\Http\Controllers\FinanceController::class,'collectionExpectReport'])->name('finance.collectionExpectReport');
Route::post('/finance/debt/collectionExpectReport/getCollectionExpectReport', [App\Http\Controllers\FinanceController::class,'getCollectionExpectReport']);
Route::get('/finance/debt/monthlyPayment', [App\Http\Controllers\FinanceController::class,'monthlyPayment'])->name('finance.monthlyPayment');
Route::post('/finance/debt/monthlyPayment/getMonthlyPayment', [App\Http\Controllers\FinanceController::class,'getMonthlyPayment']);
Route::get('/finance/debt/ctosReport', [App\Http\Controllers\FinanceController::class,'ctosReport'])->name('finance.ctosReport');
Route::post('/finance/debt/ctosReport/getCtosReport', [App\Http\Controllers\FinanceController::class,'getCtosReport']);
Route::get('/finance/debt/arrearNotice', function(){
    return view('finance.debt.arrear_notice.index');
})->name('finance.arrearNotice');
Route::post('/finance/debt/arrearNotice/printArrearNotice', [App\Http\Controllers\FinanceController::class,'printArrearNotice'])->name('finance.arrearNotice.store');


Route::get('/treasurer_dashboard', [App\Http\Controllers\TreasurerController::class, 'dashboard'])->name('treasurer.dashboard');
Route::get('/treasurer/payment/credit', [App\Http\Controllers\TreasurerController::class, 'creditNote'])->name('treasurer.payment.credit');
Route::post('/treasurer/payment/credit/getStudent', [App\Http\Controllers\TreasurerController::class, 'getStudentCredit']);
Route::post('/treasurer/payment/credit/storeCredit', [App\Http\Controllers\TreasurerController::class, 'storeCredit']);
Route::post('/treasurer/payment/credit/confirmCredit', [App\Http\Controllers\TreasurerController::class, 'confirmCredit']);
Route::get('/treasurer/payment/debit', [App\Http\Controllers\TreasurerController::class, 'debitNote'])->name('treasurer.payment.debit');
Route::post('/treasurer/payment/debit/getStudent', [App\Http\Controllers\TreasurerController::class, 'getStudentDebit']);
Route::post('/treasurer/payment/debit/storeDebit', [App\Http\Controllers\TreasurerController::class, 'storeDebit']);
Route::post('/treasurer/payment/credit/getStatement', [App\Http\Controllers\TreasurerController::class, 'getStatement']);

Route::get('/Others_dashboard', [App\Http\Controllers\OtherUserController::class, 'dashboard'])->name('others.dashboard');

Route::get('/coop_dashboard', [App\Http\Controllers\CoopController::class, 'dashboard'])->name('coop.dashboard');
Route::get('/coop/voucher', [App\Http\Controllers\CoopController::class, 'voucher'])->name('coop.voucher');
Route::post('/coop/voucher/findVoucher', [App\Http\Controllers\CoopController::class, 'findVoucher']);
Route::post('/coop/voucher/redeemVoucher', [App\Http\Controllers\CoopController::class, 'redeemVoucher']);
Route::get('/coop/voucher/report/dailyreport', [App\Http\Controllers\CoopController::class, 'dailyReport'])->name('coop.voucher.dailyReport');
Route::get('/coop/voucher/report/dailyreport/getDailyReport', [App\Http\Controllers\CoopController::class, 'getDailyReport']);

Route::group(['prefix' => 'ur'], function () {
    Route::get('/dashboard', [App\Http\Controllers\CoopController::class, 'dashboard'])->name('ur.dashboard');
    Route::get('/educationAdvisor', [App\Http\Controllers\URController::class, 'educationAdvisor'])->name('ur.educationAdvisor');
    Route::post('/educationAdvisor/post', [App\Http\Controllers\URController::class, 'postEducationAdvisor']);
    Route::delete('/educationAdvisor/delete', [App\Http\Controllers\URController::class, 'deleteEducationAdvisor'])->name('ur.educationAdvisor.delete');
});

Route::get('/quality/report/attendance', [App\Http\Controllers\QualityController::class, 'attendanceReport'])->name('quality.report.attendance');
Route::post('/quality/report/attendance/getLecturer', [App\Http\Controllers\QualityController::class, 'getLectAttendance'])->name('quality.report.attendance.getLecturer');
Route::get('/quality/report/allreport', [App\Http\Controllers\QualityController::class, 'allReport'])->name('quality.report.allReport');
Route::get('/export-table', [App\Http\Controllers\QualityController::class, 'exportTableToExcel'])->name('export-table');

Route::get('/posting/staff', [App\Http\Controllers\AllController::class, 'staffPosting'])->name('posting.staff');
Route::post('/posting/staff/create', [App\Http\Controllers\AllController::class, 'postingCreate']);
Route::post('/posting/staff/delete', [App\Http\Controllers\AllController::class, 'postingDelete']);
Route::post('/posting/staff/update', [App\Http\Controllers\AllController::class, 'postingUpdate']);
Route::get('/posting/admin', [App\Http\Controllers\AllController::class, 'adminPosting'])->name('posting.admin');
Route::post('/posting/admin/listStaff', [App\Http\Controllers\AllController::class, 'getStaffList']);
Route::post('/posting/admin/getStaffPost', [App\Http\Controllers\AllController::class, 'getStaffPost']);
Route::get('/all/student/spm/report', [App\Http\Controllers\AllController::class, 'studentSPM'])->name('all.student.spm.report');
Route::post('/all/student/spm/report/getStudentSPM', [App\Http\Controllers\AllController::class, 'getStudentSPM']);

Route::get('/all/massage/user', [App\Http\Controllers\AllController::class, 'studentMassage']);
Route::post('/all/massage/user/getStudentMassage', [App\Http\Controllers\AllController::class, 'getStudentMassage']);
Route::post('/all/massage/user/sendMassage', [App\Http\Controllers\AllController::class, 'sendMassage']);
Route::post('/all/massage/user/getMassage', [App\Http\Controllers\AllController::class, 'getMassage']);
Route::get('/all/massage/student/countMessage', [App\Http\Controllers\AllController::class, 'countMessage']);

Route::get('/yuran-pengajian', [App\Http\Controllers\PaymentController::class, 'showPaymentForm'])->name('yuran-pengajian');
Route::post('/yuran-pengajian/submitPayment', [App\Http\Controllers\PaymentController::class, 'submitPayment'])->name('yuran-pengajian.submitpayment');
Route::get('/yuran-pengajian/showQuotation', [App\Http\Controllers\PaymentController::class, 'showQuotation'])->name('yuran-pengajian.showQuotation');

Route::post('/checkout', [App\Http\Controllers\PaymentController::class, 'createCheckoutSession'])->name('checkout');
Route::get('/checkout/success', [App\Http\Controllers\PaymentController::class, 'handlePaymentSuccess'])->name('checkout.success');
Route::get('/checkout/cancel', function () {
    return "Payment canceled!";
})->name('checkout.cancel');
Route::get('/checkout/receipt/{session_id}', [App\Http\Controllers\PaymentController::class, 'showReceipt'])->name('checkout.receipt');

Route::post('/securepay-checkout', [App\Http\Controllers\PaymentController::class, 'securePayCheckout'])->name('securepay.checkout');
Route::post('/checkout/securePay/receipt', [App\Http\Controllers\PaymentController::class, 'showReceiptSecurePay'])->name('checkout.receipt');


Route::middleware(['preventBackHistory'])->group(function () {
    Route::post('/login/custom', [App\Http\Controllers\LoginController::class, 'login'])->name('login.custom');
    Route::post('/loginAdmin/custom', [App\Http\Controllers\LoginController::class, 'loginAdmin'])->name('loginAdmin.custom');
    Route::post('/login/student/custom', [App\Http\Controllers\LoginStudentController::class, 'login'])->name('login.student.custom');
});

Route::get('/send-announcement', [App\Http\Controllers\AnnouncementStudentController::class, 'sendAnnouncement']);

Route::post("/logout/custom",[App\Http\Controllers\LogoutController::class,"store"])->name('custom_logout');