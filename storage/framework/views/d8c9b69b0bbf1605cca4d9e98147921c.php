<?php use App\Models\Review; ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="card shadow-lg my-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h2 class="h5 mb-0 fw-bold">
                <i class="fas fa-star me-2"></i> Customer Reviews
            </h2>
            <div class="d-flex">
                <div class="input-group me-3" style="width: 250px;">
                    <span class="input-group-text bg-transparent"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." id="searchInput" value="<?php echo e(request('search')); ?>">
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-1"></i> Filters
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-3" style="width: 300px;">
                        <div class="mb-3">
                            <label class="form-label small">Rating</label>
                            <select class="form-select" id="ratingFilter">
                                <option value="">All Ratings</option>
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?php echo e($i); ?>" <?php echo e(request('rating') == $i ? 'selected' : ''); ?>>
                                        <?php echo e($i); ?> Star<?php echo e($i > 1 ? 's' : ''); ?>

                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <button class="btn btn-sm btn-primary w-100" id="applyFilters">Apply</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-start-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-star fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <div class="small">Average Rating</div>
                                    <div class="h5 fw-bold"><?php echo e($averageRating ?? 0); ?>/5</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-start-info h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-list-alt fa-2x text-info"></i>
                                </div>
                                <div>
                                    <div class="small">Total Reviews</div>
                                    <div class="h5 fw-bold"><?php echo e($totalReviews); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if(isset($review->product->image_url)): ?>
                                    <img src="<?php echo e($review->product->image_url); ?>" alt="<?php echo e($review->product->product_name ?? 'Product image'); ?>" 
                                         class="rounded me-2" width="40" height="40" style="object-fit: cover;">
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-bold"><?php echo e(Str::limit($review->product->product_name ?? 'N/A', 20)); ?></div>
                                        <small class="text-muted"><?php echo e($review->product->types ?? ''); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($review->user->name ?? 'N/A'); ?></td>
                            <td>
                                <div class="star-rating">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?php echo e($i <= ($review->rating ?? 0) ? 'text-warning' : 'text-secondary'); ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </td>
                            <td><?php echo e(Str::limit($review->comment ?? '', 50)); ?></td>
                            <td><?php echo e(optional($review->created_at)->format('M d, Y') ?? 'N/A'); ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="<?php echo e(route('admin.reviews.show', $review)); ?>" class="btn btn-sm btn-outline-primary" 
                                       data-bs-toggle="tooltip" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.reviews.destroy', $review)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="tooltip" title="Delete Review"
                                                onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">No reviews found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="small text-muted">
                    Showing <?php echo e($reviews->firstItem()); ?> to <?php echo e($reviews->lastItem()); ?> of <?php echo e($reviews->total()); ?> reviews
                </div>
                <div>
                    <?php echo e($reviews->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .star-rating {
        color: #ffc107;
        font-size: 0.9rem;
    }
    .card-header {
        background-color: #f8f9fa;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Filter functionality
    const applyFilters = () => {
        const params = new URLSearchParams();
        const search = document.getElementById('searchInput').value;
        const rating = document.getElementById('ratingFilter').value;

        if (search) params.set('search', search);
        if (rating) params.set('rating', rating);

        window.location.href = `${window.location.pathname}?${params.toString()}`;
    };

    document.getElementById('searchInput').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') applyFilters();
    });

    document.getElementById('applyFilters').addEventListener('click', applyFilters);
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\stylesphere\resources\views/admin/reviews/index.blade.php ENDPATH**/ ?>