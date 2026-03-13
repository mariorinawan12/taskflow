<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('workspace.index');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])
        ->name('register');
    Route::post('/register', [RegisterController::class, 'store'])
        ->name('register.store');

    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])
        ->name('login.authenticate');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');

    Route::get('/workspaces/create', [WorkspaceController::class, 'create'])
        ->name('workspace.create');
    Route::post('/workspaces', [WorkspaceController::class, 'store'])
        ->name('workspace.store');

    Route::get('/invitations/{token}', [InvitationController::class, 'show'])
        ->name('invitations.show');
    Route::post('/invitations/{token}', [InvitationController::class, 'accept'])
        ->name('invitations.accept');
    Route::get('/workspaces', [WorkspaceController::class, 'index'])
        ->name('workspace.index');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');


});

Route::middleware(['auth', 'resolve.workspace'])
    ->prefix('/{workspace:slug}')
    ->group(function () {
        Route::get('/dashboard', [WorkspaceController::class, 'dashboard'])
            ->name('workspace.dashboard');
        Route::get('/chat', [WorkspaceController::class, 'chat'])
            ->name('workspace.chat');
        Route::get('/members', [MemberController::class, 'index'])
            ->name('workspace.members');
        Route::post('/members/invite', [MemberController::class, 'invite'])
            ->name('workspace.members.invite');

        Route::get('/projects', [ProjectController::class, 'index'])
            ->name('projects.index');
        Route::get('/projects/create', [ProjectController::class, 'create'])
            ->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])
            ->name('projects.store');
        Route::get('/projects/{project}', [ProjectController::class, 'show'])
            ->name('projects.show');
        Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])
            ->name('projects.edit');
        Route::patch('/projects/{project}', [ProjectController::class, 'update'])
            ->name('projects.update');
        Route::patch('/projects/{project}/archive', [ProjectController::class, 'archive'])
            ->name('projects.archive');

        Route::get('/projects/{project}/task/create', [TaskController::class, 'create'])
            ->name('tasks.create');
        Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])
            ->name('tasks.store');
        Route::get('/projects/{project}/tasks/{task}', [TaskController::class, 'show'])
            ->name('tasks.show');
        Route::patch('/projects/{project}/tasks/{task}', [TaskController::class, 'update'])
            ->name('tasks.update');
        Route::delete('/projects/{project}/tasks/{task}', [TaskController::class, 'destroy'])
            ->name('tasks.destroy');
        Route::patch('/projects/{project}/tasks/{task}/status', [TaskController::class, 'updateStatus'])
            ->name('tasks.updateStatus');

        Route::post('/projects/{project}/tasks/{task}/comments', [TaskCommentController::class, 'store'])
            ->name('tasks.comments.store');
        Route::delete('/projects/{project}/tasks/{task}/comments/{comment}', [TaskCommentController::class, 'destroy'])
            ->name('task.comments.destroy');


        Route::get('/notifications', [NotificationController::class, 'index'])
            ->name('notifications.index');
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])
            ->name('notifications.destroy');

        Route::post('/chat/conversation', [ChatController::class, 'createConversation'])
            ->name('chat.conversation.create');
        Route::post('/chat/{type}/{id}', [ChatController::class, 'store'])
            ->name('chat.store');
        Route::get('/chat/{type}/{id}/messages', [ChatController::class, 'loadMessages'])
            ->name('chat.messages.load');
        Route::get('/chat/{type}/{id}/attachments', [ChatController::class, 'getAttachments'])
            ->name('chat.attachments');
    });