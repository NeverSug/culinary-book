<?php

use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\Admin\CommentAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\Admin\RecipeAdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RecipeController;

Route::middleware('auth')->group(function () {
    Route::resource('recipes', RecipeController::class)->except(['index', 'show']);
    Route::resource('products', ProductController::class)->except(['index', 'show']);
    Route::post('recipes/{recipe}/like', [RecipeController::class, 'like'])
        ->name('recipes.like');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/plan', [MealPlanController::class, 'store'])->name('plan.store');
    Route::delete('/plan/{mealPlan}', [MealPlanController::class, 'destroy'])->name('plan.destroy');
    Route::get('/plan', [MealPlanController::class, 'index'])->name('plan.index');
    Route::get('/favorites', [ProfileController::class, 'favorites'])->name('favorites.index');
    Route::get('/plan/shopping', [MealPlanController::class, 'shopping'])->name('plan.shopping');
    Route::post('recipes/{recipe}/comments', [CommentController::class, 'store'])
        ->name('recipes.comments.store');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::resource('recipes', RecipeAdminController::class)
            ->names('recipes');
        Route::resource('products', ProductAdminController::class)
            ->names('products');
        Route::resource('categories', CategoryAdminController::class)
            ->names('categories');

        Route::delete('comments/{comment}', [CommentAdminController::class, 'destroy'])
            ->name('comments.destroy');
        Route::get('comments', [CommentAdminController::class, 'index'])->name('comments.index');
    });

Route::resource('recipes', RecipeController::class)->only(['index', 'show']);
Route::resource('products', ProductController::class)->only(['index', 'show']);
Route::get('/categories/{category}', [CategoryController::class, 'show'])
    ->name('categories.show');
Route::view('privacy', 'pages.privacy')->name('privacy');
Route::view('consent', 'pages.consent')->name('consent');


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
