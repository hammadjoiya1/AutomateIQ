<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
Route::post('/lemonsqueezy/webhook', [\App\Http\Controllers\LemonSqueezyWebhookController::class, 'handle'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('lemonsqueezy.webhook');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/videos/enhance-prompt', function(Illuminate\Http\Request $request, \App\Services\OpenAIService $openai) {
        $request->validate(['prompt' => 'required|string|max:1000']);
        try {
            $enhanced = $openai->enhancePrompt($request->prompt);
            return response()->json(['enhanced_prompt' => $enhanced]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Enhancement failed: ' . $e->getMessage()], 500);
        }
    })->name('videos.enhance-prompt');

    Route::get('/billing/checkout/{plan}', [\App\Http\Controllers\BillingController::class, 'checkout'])
        ->name('billing.checkout');
    Route::get('/billing/portal', [\App\Http\Controllers\BillingController::class, 'portal'])
        ->name('billing.portal');
    Route::get('/billing/success', [\App\Http\Controllers\BillingController::class, 'success'])
        ->name('billing.success');
    Route::get('/billing/cancel', [\App\Http\Controllers\BillingController::class, 'cancel'])
        ->name('billing.cancel');
    Route::get('/billing/topup/{pack}', [\App\Http\Controllers\BillingController::class, 'topup'])
        ->name('billing.topup');

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
    ->middleware(['auth', 'verified', 'throttle:tool-runs', \App\Http\Middleware\CheckPlanLimits::class . ':tool_run'])
    ->name('tools.run');
Route::post('/tools/{slug}/stream', [\App\Http\Controllers\ToolController::class, 'stream'])
    ->middleware(['auth', 'verified', 'throttle:tool-runs', \App\Http\Middleware\CheckPlanLimits::class . ':tool_run'])
    ->name('tools.stream');
Route::post('/tools/{slug}/favorite', [\App\Http\Controllers\ToolController::class, 'toggleFavorite'])
    ->middleware(['auth', 'verified'])
    ->name('tools.favorite');
Route::post('/tools/{slug}/presets', [\App\Http\Controllers\ToolController::class, 'storePreset'])
    ->middleware(['auth', 'verified'])
    ->name('tools.presets.store');
Route::delete('/tools/presets/{preset}', [\App\Http\Controllers\ToolController::class, 'deletePreset'])
    ->middleware(['auth', 'verified'])
    ->name('tools.presets.delete');

Route::get('/blog', [\App\Http\Controllers\BlogPostController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogPostController::class, 'show'])->name('blog.show');
Route::get('/blog/tag/{tag:slug}', [\App\Http\Controllers\BlogPostController::class, 'tag'])->name('blog.tag');
Route::post('/blog/{post}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/comments', [\App\Http\Controllers\CommentController::class, 'index'])->name('dashboard.comments');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/impersonate/leave', [\App\Http\Controllers\Admin\UserController::class, 'leaveImpersonation'])->name('impersonate.leave');

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

    // AI Blog Generator & Management
    Route::get('/blog-generator', [\App\Http\Controllers\Admin\BlogGeneratorController::class, 'index'])->name('blog.generator');
    Route::post('/blog-generator', [\App\Http\Controllers\Admin\BlogGeneratorController::class, 'generate'])->name('blog.generate');
    Route::post('/blog-generator/store', [\App\Http\Controllers\Admin\BlogGeneratorController::class, 'store'])->name('blog.store');
    
    Route::resource('blog-posts', \App\Http\Controllers\Admin\BlogPostController::class)->except(['create', 'store', 'show']);

    // 2. User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('/users/{user}/ban', [\App\Http\Controllers\Admin\UserController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban', [\App\Http\Controllers\Admin\UserController::class, 'unban'])->name('users.unban');
    Route::post('/users/{user}/impersonate', [\App\Http\Controllers\Admin\UserController::class, 'impersonate'])->name('users.impersonate');

    // 3. Tool Management
    Route::post('/tools/reorder', [\App\Http\Controllers\Admin\ToolController::class, 'reorder'])->name('tools.reorder');
    Route::get('/tools/analytics', [\App\Http\Controllers\Admin\ToolAnalyticsController::class, 'index'])->name('tools.analytics');
    Route::resource('tools', \App\Http\Controllers\Admin\ToolController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

    // Comments Moderation
    Route::get('/comments/pending', [\App\Http\Controllers\Admin\CommentController::class, 'pending'])->name('comments.pending');
    Route::resource('comments', \App\Http\Controllers\Admin\CommentController::class)->except(['create', 'store', 'show', 'edit']);

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
    Route::post('/profitability/apply', [\App\Http\Controllers\Admin\ProfitabilityController::class, 'applyRecommendations'])
        ->name('profitability.apply');

    Route::get('/credits', [\App\Http\Controllers\Admin\CreditPackController::class, 'index'])->name('credits.index');
    Route::post('/credits', [\App\Http\Controllers\Admin\CreditPackController::class, 'update'])->name('credits.update');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('brand-voices', \App\Http\Controllers\BrandVoiceController::class);

    Route::middleware(\App\Http\Middleware\CheckPlanLimits::class . ':workflow')->group(function () {
        Route::get('/workflows', [\App\Http\Controllers\WorkflowController::class, 'index'])->name('workflows.index');
        Route::get('/workflows/create', [\App\Http\Controllers\WorkflowController::class, 'create'])->name('workflows.create');
        Route::get('/workflows/{workflow}/edit', [\App\Http\Controllers\WorkflowController::class, 'edit'])->name('workflows.edit');
        Route::post('/workflows', [\App\Http\Controllers\WorkflowController::class, 'store'])->name('workflows.store');
        Route::put('/workflows/{workflow}', [\App\Http\Controllers\WorkflowController::class, 'update'])->name('workflows.update');
        Route::post('/workflows/{workflow}/run', [\App\Http\Controllers\WorkflowController::class, 'run'])->name('workflows.run');
        Route::get('/workflows/{workflow}/runs', [\App\Http\Controllers\WorkflowController::class, 'runs'])->name('workflows.runs.index');
        Route::get('/workflows/runs/{run}', [\App\Http\Controllers\WorkflowController::class, 'showRun'])->name('workflows.runs.show');
        Route::get('/workflows/runs/{run}/status', [\App\Http\Controllers\WorkflowController::class, 'runStatus'])->name('workflows.runs.status');
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

Route::view('/glowy-demo', 'pages.glowy-demo')->name('glowy-demo');

require __DIR__ . '/auth.php';
