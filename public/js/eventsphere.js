document.addEventListener('DOMContentLoaded', function () {
    console.log('EventSphere System Script Loaded');

    // Auto-hide alert messages after 5 seconds
    const alerts = document.querySelectorAll('.alert-auto-dismiss');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Star Rating Interactivity
    const starContainers = document.querySelectorAll('.star-rating-input');
    starContainers.forEach(function (container) {
        const stars = container.querySelectorAll('.star-icon');
        const hiddenInput = container.querySelector('input[type="hidden"]');

        stars.forEach(function (star, index) {
            star.addEventListener('click', function () {
                const rating = index + 1;
                if (hiddenInput) hiddenInput.value = rating;

                stars.forEach((s, idx) => {
                    if (idx < rating) {
                        s.classList.add('active');
                        s.style.color = '#f59e0b';
                    } else {
                        s.classList.remove('active');
                        s.style.color = '#4b5563';
                    }
                });
            });
        });
    });

    // Modal Trigger Helpers
    const modalTriggers = document.querySelectorAll('[data-modal-target]');
    modalTriggers.forEach(function (trigger) {
        trigger.addEventListener('click', function () {
            const modalId = this.getAttribute('data-modal-target');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('show');
            }
        });
    });

    const modalCloses = document.querySelectorAll('[data-modal-close]');
    modalCloses.forEach(function (closeBtn) {
        closeBtn.addEventListener('click', function () {
            const modal = this.closest('.modal-backdrop');
            if (modal) {
                modal.classList.remove('show');
            }
        });
    });

    // Mobile Navigation Toggle
    const mobileNavToggle = document.getElementById('mobileNavToggle');
    const mainNavLinks = document.getElementById('mainNavLinks');
    if (mobileNavToggle && mainNavLinks) {
        mobileNavToggle.addEventListener('click', function () {
            mainNavLinks.classList.toggle('show');
            const icon = mobileNavToggle.querySelector('i');
            if (icon) {
                if (mainNavLinks.classList.contains('show')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-xmark');
                } else {
                    icon.classList.remove('fa-xmark');
                    icon.classList.add('fa-bars');
                }
            }
        });
    }
});

// Social Share Popup Window Helper
function shareEvent(platform, title, url, hashtags) {
    const encodedTitle = encodeURIComponent(title);
    const encodedUrl = encodeURIComponent(url);
    const encodedHashtags = encodeURIComponent(hashtags || 'EventSphere,CollegeEvents');

    let shareUrl = '';
    if (platform === 'whatsapp') {
        shareUrl = `https://api.whatsapp.com/send?text=${encodedTitle}%20-%20Check%20it%20out:%20${encodedUrl}`;
    } else if (platform === 'twitter') {
        shareUrl = `https://twitter.com/intent/tweet?text=${encodedTitle}&url=${encodedUrl}&hashtags=${encodedHashtags}`;
    } else if (platform === 'facebook') {
        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
    } else if (platform === 'linkedin') {
        shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`;
    } else if (platform === 'email') {
        shareUrl = `mailto:?subject=${encodedTitle}&body=Hi,%20Check%20out%20this%20event%20on%20EventSphere:%20${encodedUrl}`;
    }

    if (shareUrl) {
        window.open(shareUrl, '_blank', 'width=600,height=450');
    }
}
