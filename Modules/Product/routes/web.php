<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\app\Http\Controllers\AttributeController;
use Modules\Product\app\Http\Controllers\AttributeValueController;
use Modules\Product\app\Http\Controllers\BrandController;
use Modules\Product\app\Http\Controllers\GalleryImageController;
use Modules\Product\app\Http\Controllers\ProductCategoryController;
use Modules\Product\app\Http\Controllers\ProductController;
use Modules\Product\app\Http\Controllers\ProductLabelController;
use Modules\Product\app\Http\Controllers\ProductReviewController;
use Modules\Product\app\Http\Controllers\SellerProductController;
use Modules\Product\app\Http\Controllers\TagController;
use Modules\Product\app\Http\Controllers\UnitTypeController;

Route::name('admin.')
    ->prefix('admin')
    ->middleware(['auth:admin', 'translation'])
    ->group(function () {

        // product tags
        Route::resource('product/tags', TagController::class)->names('product.tags');

        // bulk product import

        Route::get('product/import', [ProductController::class, 'bulkImport'])->name('product.import');
        Route::post('product/import', [ProductController::class, 'bulkImportStore'])->name('product.import.store');

        // Products
        Route::get('product/barcode', [ProductController::class, 'barcode'])->name('product.barcode');
        Route::post('product/barcode/print', [ProductController::class, 'barcodePrint'])->name('product.barcode.print');
        Route::get('product/search', [ProductController::class, 'search'])->name('product.search');
        Route::post('product/status/{id}', [ProductController::class, 'status'])->name('product.status');
        Route::post('product/approve/{id}', [ProductController::class, 'approveProduct'])->name('product.approve');
        Route::get('product/view/{id}', [ProductController::class, 'singleProduct'])->name('product.view');
        Route::get('product/get/{id}', [ProductController::class, 'getProductJson'])->name('product.json');
        Route::post('product/delete', [ProductController::class, 'bulkDelete'])->name('product.bulk.delete');
        Route::resource('product', ProductController::class);

        Route::get('sellers/products', [SellerProductController::class, 'index'])->name('seller.products.index');

        Route::get('product/product-gallery/{id}', [ProductController::class, 'productGallery'])->name('product-gallery');
        Route::post('product/product-gallery/{id}', [ProductController::class, 'productGalleryStore'])->name('product-gallery.store');
        Route::delete('product/product-gallery/{id}', [ProductController::class, 'productGalleryDelete'])->name('product-gallery.delete');

        // product related product
        Route::get('product/related-product/{id}', [ProductController::class, 'related_product'])->name('related-products');
        // store
        Route::post('product/related-product/{id}', [ProductController::class, 'related_product_store'])->name('store-related-products');

        // product related variant
        Route::get('product/related-variant/{id}', [ProductController::class, 'product_variant'])->name('product.product-variant');

        Route::get('product/related-variant/{id}/create', [ProductController::class, 'product_variant_create'])->name('product.product-variant.create');

        Route::post('product/related-variant/{id}', [ProductController::class, 'product_variant_store'])->name('product.product-variant.store');
        Route::get('product/related-variant/edit/{variant_id}', [ProductController::class, 'product_variant_edit'])->name('product.product-variant.edit');
        Route::put('product/related-variant/{variant_id}', [ProductController::class, 'product_variant_update'])->name('product.product-variant.update');

        Route::delete('product/related-variant/{variant_id}', [ProductController::class, 'product_variant_delete'])->name('product.product-variant.delete');
        Route::delete('product/related-variant-image/{image_id}', [ProductController::class, 'product_variant_image_delete'])->name('product.product-variant-image.delete');

        Route::get('/{id}/clone', [ProductController::class, 'clone'])->name('clone');
        Route::get('bulk-product-upload', [ProductController::class, 'bulk_product_upload_page'])->name('bulk_product_upload_page');
        Route::post('bulk-product-upload-store', [ProductController::class, 'bulk_product_store'])->name('bulk_product_store');
        Route::post('wholesale-modal', [ProductController::class, 'productWholesaleModal'])->name('wholesale.modal');

        // Categories Routes

        Route::get('get-sub-category/{id}', [ProductCategoryController::class, 'getSubCategory'])->name('get.sub-category');
        Route::get('bulk-category-upload', [ProductCategoryController::class, 'bulk_category_upload_page'])->name('bulk_category_upload_page');

        Route::get('download-category-list-csv', [ProductCategoryController::class, 'csv_category_download'])->name('csv_category_download');

        Route::post('bulk-category-upload-store', [ProductCategoryController::class, 'bulk_category_store'])->name('bulk_category_store');

        Route::get('/category-info', [ProductCategoryController::class, 'info'])->name('categories.index_info');
        Route::get('/categories/get-data', [ProductCategoryController::class, 'getData'])->name('categories.get-data');

        // delete all selected category
        Route::post('/categories/delete-all', [ProductCategoryController::class, 'deleteAll'])->name('category.deleteSelected');

        // delete all selected brand
        Route::post('/brands/delete-all', [BrandController::class, 'deleteAll'])->name('brand.deleteSelected');

        Route::post('/request-product/approved', [ProductController::class, 'approved'])->name('request.approved');
        Route::group(['prefix' => 'products'], function () {
            Route::patch('category/{category}/status', [ProductCategoryController::class, 'status'])->name('category.status');
            Route::patch('brand/{brand}/status', [BrandController::class, 'status'])->name('brand.status');
            Route::resource('category', ProductCategoryController::class);
            Route::resource('brand', BrandController::class);
            Route::resource('attributes/value', AttributeValueController::class)->names('attributes.values')->only('index', 'store', 'edit', 'update', 'destroy');
            Route::resource('attribute', AttributeController::class);
            Route::post('attribute/get-value/', [AttributeController::class, 'getValue'])->name('attribute.get.value');
            Route::post('attribute/value/delete', [AttributeController::class, 'deleteValue'])->name('attribute.value.delete');
            Route::post('/attribute/has-value', [AttributeController::class, 'checkHasValue'])->name('attribute.has-value');
            Route::resource('unit', UnitTypeController::class);
            Route::get('unit/parent/{id}', [UnitTypeController::class, 'unitByParent'])->name('unit.parent');
            Route::post('upload/images', [GalleryImageController::class, 'store'])->name('dropzone.upload');
            Route::resource('label', ProductLabelController::class)->except('create', 'show');
            Route::patch('label/status/{id}', [ProductLabelController::class, 'updateStatus'])->name('label.status');
            Route::get('/product-prices', [ProductController::class, 'priceUpdate'])->name('products.product-prices');
            Route::post('/update-product-price', [ProductController::class, 'priceUpdateStore'])->name('products.price-update.store');
            Route::get('/product-inventory', [ProductController::class, 'productInventory'])->name('products.product-inventory');
            Route::post('/update-product-inventory', [ProductController::class, 'productInventoryStore'])->name('products.product-inventory.store');
            Route::get('/product-return-policy', [ProductController::class, 'productReturnPolicy'])->name('products.product-return-policy');
            Route::delete('/product-return-policy/{id}', [ProductController::class, 'productReturnPolicyDelete'])->name('products.product-return-policy.delete');
        });

        Route::get('product-review', [ProductReviewController::class, 'index'])->name('product-review');
        Route::get('show-product-review/{id}', [ProductReviewController::class, 'show'])->name('show-product-review');
        Route::post('product-review/status/{id}', [ProductReviewController::class, 'status'])->name('product-review.status');
        Route::delete('product-review/delete/{id}', [ProductReviewController::class, 'delete'])->name('product-review.delete');
    });
