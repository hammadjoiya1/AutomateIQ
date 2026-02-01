<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
Route::post('/lemonsqueezy/webhook', [\App\Http\Controllers\LemonSqueezyWebhookController::class, 'handle'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('lemonsqueezy.webhook');

Route::middleware(['auth'])->group(function () {
    Route::get('/billing/checkout/{plan}', [\App\Http\Controllers\BillingController::class, 'checkout'])
        ->name('billing.checkout');
    Route::get('/billing/portal', [\App\Http\Controllers\BillingController::class, 'portal'])
        ->name('billing.portal');
    Route::get('/billing/success', [\App\Http\Controllers\BillingController::class, 'success'])
        ->name('billing.success');
    Route::get('/billing/cancel', [\App\Http\Controllers\BillingController::class, 'cancel'])
        ->name('billing.cancel');

    Route::get('/onboarding', [\App\Http\Controllers\OnboardingController::class, 'show'])
        ->name('onboarding.show');
    Route::post('/onboarding/complete', [\App\Http\Controllers\OnboardingController::class, 'complete'])
        ->name('onboarding.complete');
});


Route::get('/', function () {
    $featuredTools = \App\Models\Tool::where('status', true)->where('is_featured', true)->take(3)->get();
    return view('home', compact('featuredTools'));
})->name('home');

Route::get('/tools', [\App\Http\Controllers\ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/{slug}', [\App\Http\Controllers\ToolController::class, 'show'])->name('tools.show');
Route::post('/tools/{slug}/run', [\App\Http\Controllers\ToolController::class, 'run'])
    ->middleware(['auth', 'throttle:tool-runs', \App\Http\Middleware\CheckPlanLimits::class . ':tool_run'])
    ->name('tools.run');
Route::post('/tools/{slug}/stream', [\App\Http\Controllers\ToolController::class, 'stream'])
    ->middleware(['auth', 'throttle:tool-runs', \App\Http\Middleware\CheckPlanLimits::class . ':tool_run'])
    ->name('tools.stream');
Route::post('/tools/{slug}/favorite', [\App\Http\Controllers\ToolController::class, 'toggleFavorite'])
    ->middleware(['auth'])
    ->name('tools.favorite');
Route::post('/tools/{slug}/presets', [\App\Http\Controllers\ToolController::class, 'storePreset'])
    ->middleware(['auth'])
    ->name('tools.presets.store');
Route::delete('/tools/presets/{preset}', [\App\Http\Controllers\ToolController::class, 'deletePreset'])
    ->middleware(['auth'])
    ->name('tools.presets.delete');

Route::get('/blog', [\App\Http\Controllers\BlogPostController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogPostController::class, 'show'])->name('blog.show');
Route::get('/blog/tag/{tag:slug}', [\App\Http\Controllers\BlogPostController::class, 'tag'])->name('blog.tag');
Route::post('/blog/{post}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/comments', [\App\Http\Controllers\CommentController::class, 'index'])->name('dashboard.comments');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Script Writer Tool
    Route::get('/tools/script-writer', [\App\Http\Controllers\ScriptController::class, 'create'])->name('tools.script-writer');
    Route::post('/tools/script-writer', [\App\Http\Controllers\ScriptController::class, 'store'])
        ->middleware(\App\Http\Middleware\CheckPlanLimits::class . ':tool_run')
        ->name('tools.script-writer.generate');

    // Scripts Management
    Route::get('/scripts', [\App\Http\Controllers\ScriptController::class, 'index'])->name('scripts.index');
    Route::get('/scripts/{script}', [\App\Http\Controllers\ScriptController::class, 'show'])->name('scripts.show');
    Route::delete('/scripts/{script}', [\App\Http\Controllers\ScriptController::class, 'destroy'])->name('scripts.destroy');
});

Route::post('/theme/switch', [\App\Http\Controllers\ThemeController::class, 'switch'])->name('theme.switch');

Route::middleware(['auth', 'verified', 'admin', 'admin.audit'])->prefix('admin')->name('admin.')->group(function () {
    // 1. Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // 2. User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('/users/{user}/ban', [\App\Http\Controllers\Admin\UserController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban', [\App\Http\Controllers\Admin\UserController::class, 'unban'])->name('users.unban');

    // 3. Tool Management
    Route::resource('tools', \App\Http\Controllers\Admin\ToolController::class);
    Route::post('/tools/reorder', [\App\Http\Controllers\Admin\ToolController::class, 'reorder'])->name('tools.reorder');
    Route::get('/tools/analytics', [\App\Http\Controllers\Admin\ToolAnalyticsController::class, 'index'])->name('tools.analytics');
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

    // 4. Tool Runs / Logs
    Route::get('/logs', [\App\Http\Controllers\Admin\RunController::class, 'index'])->name('logs.index');
    Route::get('/logs/{run}', [\App\Http\Controllers\Admin\RunController::class, 'show'])->name('logs.show');

    // 5. Workflow Management
    Route::get('/workflows', [\App\Http\Controllers\Admin\WorkflowController::class, 'index'])->name('workflows.index');
    Route::delete('/workflows/{workflow}', [\App\Http\Controllers\Admin\WorkflowController::class, 'destroy'])->name('workflows.destroy');

    // 6. Settings & Themes
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    Route::get('/themes', [\App\Http\Controllers\Admin\ThemeController::class, 'index'])->name('themes.index');
    Route::post('/themes/{theme}/activate', [\App\Http\Controllers\Admin\ThemeController::class, 'activate'])->name('themes.activate');

    // 7. Contact Messages
    Route::get('/messages', [\App\Http\Controllers\Admin\MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/{message}/read', [\App\Http\Controllers\Admin\MessageController::class, 'markAsRead'])->name('messages.read');
    Route::delete('/messages/{message}', [\App\Http\Controllers\Admin\MessageController::class, 'destroy'])->name('messages.destroy');

    Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');

    Route::get('/profitability', [\App\Http\Controllers\Admin\ProfitabilityController::class, 'index'])->name('profitability.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('brand-voices', \App\Http\Controllers\BrandVoiceController::class);

    Route::middleware(\App\Http\Middleware\CheckPlanLimits::class . ':workflow')->group(function () {
        Route::get('/workflows', [\App\Http\Controllers\WorkflowController::class, 'index'])->name('workflows.index');
        Route::get('/workflows/create', [\App\Http\Controllers\WorkflowController::class, 'create'])->name('workflows.create');
        Route::post('/workflows', [\App\Http\Controllers\WorkflowController::class, 'store'])->name('workflows.store');
        Route::post('/workflows/{workflow}/run', [\App\Http\Controllers\WorkflowController::class, 'run'])->name('workflows.run');
    });

    Route::get('/library', [\App\Http\Controllers\LibraryController::class, 'index'])->name('library.index');
    Route::post('/library', [\App\Http\Controllers\LibraryController::class, 'store'])->name('library.store');
    Route::post('/library/items', [\App\Http\Controllers\LibraryController::class, 'storeItem'])
        ->middleware(\App\Http\Middleware\CheckPlanLimits::class . ':library_storage')
        ->name('library.items.store');
    Route::get('/library/{collection}', [\App\Http\Controllers\LibraryController::class, 'show'])->name('library.show');

    // Video Factory
    Route::get('/videos', [\App\Http\Controllers\VideoController::class, 'index'])->name('videos.index');
    Route::get('/videos/create', [\App\Http\Controllers\VideoController::class, 'create'])
        ->middleware(\App\Http\Middleware\CheckPlanLimits::class . ':video_generation')
        ->name('videos.create');
    Route::post('/videos', [\App\Http\Controllers\VideoController::class, 'store'])
        ->middleware(\App\Http\Middleware\CheckPlanLimits::class . ':video_generation')
        ->name('videos.store');
    Route::get('/videos/{project}', [\App\Http\Controllers\VideoController::class, 'show'])->name('videos.show');
    Route::get('/videos/{project}/status', [\App\Http\Controllers\VideoController::class, 'checkStatus'])->name('videos.check-status');

    Route::get('/history', [\App\Http\Controllers\ToolController::class, 'history'])->name('tools.history');
    Route::get('/tools/history/{run}', [\App\Http\Controllers\ToolController::class, 'showRun'])->name('tools.show-run');
    Route::get('/tools/status/{id}', [\App\Http\Controllers\ToolController::class, 'checkStatus'])->name('tools.status');
});

Route::get('/pricing', [\App\Http\Controllers\PagesController::class, 'pricing'])->name('pricing');
Route::get('/about', [\App\Http\Controllers\PagesController::class, 'about'])->name('about');
Route::get('/contact', [\App\Http\Controllers\PagesController::class, 'contact'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');
Route::post('/newsletter/subscribe', [\App\Http\Controllers\ContactController::class, 'subscribe'])->name('newsletter.subscribe');
Route::view('/book-demo', 'pages.demo')->name('demo');
Route::view('/affiliate', 'pages.affiliate')->name('affiliate');
Route::get('/faq', [\App\Http\Controllers\PagesController::class, 'faq'])->name('faq');
Route::get('/privacy', [\App\Http\Controllers\PagesController::class, 'privacy'])->name('privacy');
Route::get('/terms', [\App\Http\Controllers\PagesController::class, 'terms'])->name('terms');

require __DIR__ . '/auth.php';
