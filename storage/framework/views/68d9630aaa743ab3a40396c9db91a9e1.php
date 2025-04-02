<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="text-center mb-4">Welcome to StyleSphere</h1>

    <!-- Navigation and Cart Section -->
    <div class="d-flex justify-content-between mb-4">
        <!-- Hamburger Profile Menu -->
        <div>
            <div class="dropdown">
                <button class="btn btn-outline-secondary" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>"><i class="fas fa-user-edit me-2"></i>Edit Profile</a></li>
                    <li><a class="dropdown-item" href="<?php echo e(route('orders.history')); ?>"><i class="fas fa-history me-2"></i>Order History</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Shopping Cart -->
        <div>
            <a href="<?php echo e(route('cart.index')); ?>" class="btn btn-outline-info position-relative">
                <i class="fas fa-shopping-bag"></i>
                <?php if(auth()->user()->cart->count() > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?php echo e(auth()->user()->cart->count()); ?>

                </span>
                <?php endif; ?>
            </a>
        </div>
    </div>

    <div class="row">
        <?php if($product->total() > 0): ?>
            <?php $__currentLoopData = $product; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0">
                        <!-- Product Image Carousel -->
                        <?php if($p->images->count() > 0): ?>
                        <div id="productCarousel-<?php echo e($p->product_id); ?>" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php $__currentLoopData = $p->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="carousel-item <?php echo e($key === 0 ? 'active' : ''); ?>">
                                    <img src="<?php echo e(asset('storage/' . $image->image_path)); ?>" 
                                         class="d-block w-100 card-img-top" 
                                         style="height: 300px; object-fit: cover;" 
                                         alt="<?php echo e($p->product_name); ?>">
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php if($p->images->count() > 1): ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel-<?php echo e($p->product_id); ?>" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel-<?php echo e($p->product_id); ?>" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            <?php endif; ?>
                        </div>
                        <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                        <?php endif; ?>

                        <div class="card-body">
                            <h5 class="card-title"><?php echo e($p->product_name); ?></h5>
                            <p class="card-text"><strong>Supplier:</strong> <?php echo e($p->supplier->brand_name ?? 'No Supplier'); ?></p>
                            <p class="card-text"><strong>Price:</strong> $<?php echo e(number_format($p->sell_price, 2)); ?></p>
                            <p class="card-text"><strong>Stock:</strong> <?php echo e($p->stock); ?></p>
                            
                            <!-- Enhanced Product Rating Display -->
                            <div class="mb-3">
                                <?php if($p->reviews->count() > 0): ?>
                                    <div class="d-flex align-items-center mb-1">
                                        <?php
                                            $avgRating = $p->average_rating;
                                            $fullStars = floor($avgRating);
                                            $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
                                        ?>
                                        
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($i <= $fullStars): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php elseif($i == $fullStars + 1 && $hasHalfStar): ?>
                                                <i class="fas fa-star-half-alt text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <span class="ms-2"><?php echo e(number_format($avgRating, 1)); ?> (<?php echo e($p->reviews_count); ?> reviews)</span>
                                    </div>
                                    
                                    <!-- Display top 2 reviews -->
                                    <div class="mt-2">
                                        <?php $__currentLoopData = $p->reviews->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="review-item mb-2 pb-2 border-bottom">
                                                <div class="d-flex justify-content-between">
                                                    <strong><?php echo e($review->user->name); ?></strong>
                                                    <div>
                                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                                            <i class="fas fa-star<?php echo e($i > $review->rating ? '-empty' : ''); ?> text-warning"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                                <?php if($review->comment): ?>
                                                    <p class="mb-0 small">"<?php echo e(Str::limit($review->comment, 80)); ?>"</p>
                                                <?php endif; ?>
                                                <small class="text-muted"><?php echo e($review->created_at->diffForHumans()); ?></small>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-muted">
                                        <i class="far fa-star"></i> No reviews yet
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="mb-2">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="product_id" value="<?php echo e($p->product_id); ?>">
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>
                            
                            <!-- View All Reviews Button -->
                            <?php if($p->reviews->count() > 2): ?>
                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#reviewsModal-<?php echo e($p->product_id); ?>">
                                    <i class="fas fa-comment me-1"></i> View All Reviews (<?php echo e($p->reviews->count()); ?>)
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Reviews Modal for each product -->
                <div class="modal fade" id="reviewsModal-<?php echo e($p->product_id); ?>" tabindex="-1" aria-labelledby="reviewsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reviewsModalLabel">Reviews for <?php echo e($p->product_name); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <?php if($p->reviews->count() > 0): ?>
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center">
                                            <h4 class="me-3"><?php echo e(number_format($p->average_rating, 1)); ?></h4>
                                            <div>
                                                <div class="d-flex mb-1">
                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                        <?php if($i <= $fullStars): ?>
                                                            <i class="fas fa-star text-warning"></i>
                                                        <?php elseif($i == $fullStars + 1 && $hasHalfStar): ?>
                                                            <i class="fas fa-star-half-alt text-warning"></i>
                                                        <?php else: ?>
                                                            <i class="far fa-star text-warning"></i>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </div>
                                                <p class="mb-0"><?php echo e($p->reviews->count()); ?> reviews</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="reviews-list">
                                        <?php $__currentLoopData = $p->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="review-item mb-3 pb-3 border-bottom">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <strong><?php echo e($review->user->name ?? 'Anonymous'); ?></strong>
                                                    <div>
                                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                                            <i class="fas fa-star<?php echo e($i > $review->rating ? '-empty' : ''); ?> text-warning"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                                <?php if($review->comment): ?>
                                                    <p class="mb-0">"<?php echo e($review->comment); ?>"</p>
                                                <?php else: ?>
                                                    <p class="text-muted mb-0">No comment provided</p>
                                                <?php endif; ?>
                                                <small class="text-muted d-block mt-2"><?php echo e($review->created_at->format('M d, Y')); ?></small>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-center">No reviews yet for this product.</p>
                                <?php endif; ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center">No products available.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($product->hasPages()): ?>
    <div class="d-flex justify-content-center mt-4">
        <?php echo e($product->links()); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->startSection('scripts'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\stylesphere\resources\views/home.blade.php ENDPATH**/ ?>