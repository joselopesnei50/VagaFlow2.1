<?php

use App\Http\Controllers\ProfileController;
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
    $totalToday = \App\Models\Application::whereDate('created_at', \Carbon\Carbon::today())->count();
    $totalToday = $totalToday > 0 ? $totalToday : 412; 
    
    $recentPosts = \App\Models\Post::where('is_published', true)->latest()->take(2)->get();
    
    return view('welcome', compact('totalToday', 'recentPosts'));
});

Route::get('/privacy', [\App\Http\Controllers\PublicPageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [\App\Http\Controllers\PublicPageController::class, 'terms'])->name('terms');

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Payment Routes
    Route::get('/checkout', [\App\Http\Controllers\PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook']);

    // CV Routes
    Route::post('/cv/upload', [\App\Http\Controllers\CvController::class, 'upload'])->name('cv.upload');
    Route::get('/cv/search', [\App\Http\Controllers\CvController::class, 'search'])->name('cv.search');
    Route::post('/cv/analyze', [\App\Http\Controllers\CvController::class, 'analyze'])->name('cv.analyze');
    Route::post('/cv/send', [\App\Http\Controllers\CvController::class, 'send'])->name('cv.send');
    Route::post('/cv/autopilot', [\App\Http\Controllers\CvController::class, 'autopilot'])->name('cv.autopilot');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'store'])->name('settings.store');
    
    // Admin Post Routes
    Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);

    // Plan Management
    Route::get('/plans', [\App\Http\Controllers\Admin\PlanController::class, 'index'])->name('plans.index');
    Route::post('/plans', [\App\Http\Controllers\Admin\PlanController::class, 'store'])->name('plans.store');
});

// Public Blog Routes
Route::get('/blog/{slug}', function($slug) {
    $post = \App\Models\Post::where('slug', $slug)->where('is_published', true)->firstOrFail();
    return view('blog.show', compact('post'));
})->name('blog.show');

require __DIR__.'/auth.php';
