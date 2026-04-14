<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HeadOfFamilyController;
use App\Http\Controllers\FamilyMemberController;
use App\Http\Controllers\SocialAssistanceController;
use App\Http\Controllers\SocialAssistanceRecipientController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\DevelopmentController;
use App\Http\Controllers\DevelopmentApplicantController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (NO TOKEN REQUIRED)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/me', [AuthController::class, 'me']);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (SANCTUM)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // ================= USER =================
    Route::apiResource('user', UserController::class);
    Route::get('user/all/paginated', [UserController::class, 'getAllPaginated']);

    // ================= HEAD OF FAMILY =================
    Route::apiResource('head-of-family', HeadOfFamilyController::class);
    Route::get('head-of-family/all/paginated', [HeadOfFamilyController::class, 'getAllPaginated']);

    // ================= FAMILY MEMBER =================
    Route::apiResource('family-member', FamilyMemberController::class);
    Route::get('family-member/all/paginated', [FamilyMemberController::class, 'getAllPaginated']);

    // ================= SOCIAL ASSISTANCE =================
    Route::apiResource('social-assistance', SocialAssistanceController::class);
    Route::get('social-assistance/all/paginated', [SocialAssistanceController::class, 'getAllPaginated']);

    Route::apiResource('social-assistance-recipient', SocialAssistanceRecipientController::class);
    Route::get('social-assistance-recipient/all/paginated', [SocialAssistanceRecipientController::class, 'getAllPaginated']);

    // ================= EVENT =================
    Route::apiResource('event', EventController::class);
    Route::get('event/all/paginated', [EventController::class, 'getAllPaginated']);

    Route::apiResource('event-participant', EventParticipantController::class);
    Route::get('event-participant/all/paginated', [EventParticipantController::class, 'getAllPaginated']);

    // ================= DEVELOPMENT =================
    Route::apiResource('development', DevelopmentController::class);
    Route::get('development/all/paginated', [DevelopmentController::class, 'getAllPaginated']);

    Route::apiResource('development-applicant', DevelopmentApplicantController::class);
    Route::get('development-applicant/all/paginated', [DevelopmentApplicantController::class, 'getAllPaginated']);

    // ================= PROFILE =================
    Route::apiResource('profile', ProfileController::class);
    Route::get('profile/all/paginated', [ProfileController::class, 'getAllPaginated']);

    // ================= CURRENT USER =================
    Route::get('/me', [AuthController::class, 'me']);
});