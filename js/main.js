// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize animations
    initAnimations();
    
    // Initialize scroll reveal
    initScrollReveal();
    
    // Initialize testimonial slider
    initTestimonialSlider();
    
    // Initialize counter animation
    initCounterAnimation();
    
    // Initialize mobile menu
    initMobileMenu();
    
    // Initialize header scroll effect
    initHeaderScroll();

    // Initialize logo carousel
    initLogoCarousel();
});

// Initialize animations for hero section
function initAnimations() {
    // Hero section animation
    const heroContent = document.querySelector('.hero-content');
    if (heroContent) {
        setTimeout(() => {
            heroContent.classList.add('fade-in');
        }, 500);
    }
    
    // Add animation classes to benefit cards
    const benefitCards = document.querySelectorAll('.benefit-card');
    benefitCards.forEach((card, index) => {
        card.classList.add('reveal-up');
        card.style.transitionDelay = `${0.2 * index}s`;
    });
    
    // Add animation classes to stat boxes
    const statBoxes = document.querySelectorAll('.stat-box');
    statBoxes.forEach((box, index) => {
        box.classList.add('reveal-up');
        box.style.transitionDelay = `${0.2 * index}s`;
    });
    
    // Add animation classes to blog cards
    const blogCards = document.querySelectorAll('.blog-card');
    blogCards.forEach((card, index) => {
        card.classList.add('reveal-up');
        card.style.transitionDelay = `${0.2 * index}s`;
    });
    
    // Add animation to transaction tables
    const transactionTables = document.querySelectorAll('.transaction-table');
    transactionTables.forEach((table, index) => {
        table.classList.add(index % 2 === 0 ? 'reveal-left' : 'reveal-right');
    });
}

// Initialize scroll reveal effect
function initScrollReveal() {
    const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-up, .retirement-benefits li');
    
    function checkReveal() {
        const windowHeight = window.innerHeight;
        const revealPoint = 150;
        
        revealElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            
            if (elementTop < windowHeight - revealPoint) {
                element.classList.add('active');
            }
        });
    }
    
    // Check on initial load
    checkReveal();
    
    // Check on scroll
    window.addEventListener('scroll', checkReveal);
}

// Initialize testimonial slider
function initTestimonialSlider() {
    const slider = document.querySelector('.testimonial-slider');
    const testimonials = document.querySelectorAll('.testimonial');
    const indicators = document.querySelectorAll('.slide-indicator');
    
    if (!slider || testimonials.length === 0) return;
    
    let currentIndex = 0;
    let autoSlideInterval;
    let touchStartX = 0;
    let touchEndX = 0;
    let isDragging = false;
    let startPos = 0;
    let currentTranslate = 0;
    let prevTranslate = 0;
    let animationID = 0;
    
    // Set initial active testimonial
    testimonials[currentIndex].classList.add('active');
    indicators[currentIndex].classList.add('active');
    
    // Function to move to a specific slide
    function goToSlide(index) {
        // Remove active class from all testimonials and indicators
        testimonials.forEach(testimonial => {
            testimonial.classList.remove('active');
        });
        
        indicators.forEach(indicator => {
            indicator.classList.remove('active');
        });
        
        // Handle index boundaries
        if (index < 0) {
            currentIndex = testimonials.length - 1;
        } else if (index >= testimonials.length) {
            currentIndex = 0;
        } else {
            currentIndex = index;
        }
        
        // Add active class to current testimonial and indicator
        testimonials[currentIndex].classList.add('active');
            indicators[currentIndex].classList.add('active');
    }
    
    // Function to start auto sliding
    function startAutoSlide() {
        stopAutoSlide(); // Clear any existing interval
        autoSlideInterval = setInterval(() => {
            goToSlide(currentIndex + 1);
        }, 5000); // Auto-slide every 5 seconds
    }
    
    // Function to stop auto sliding
    function stopAutoSlide() {
        if (autoSlideInterval) {
            clearInterval(autoSlideInterval);
        }
    }
    
    // Touch events
    function touchStart(event) {
        touchStartX = event.touches[0].clientX;
        isDragging = true;
        stopAutoSlide();
        animationID = requestAnimationFrame(animation);
    }
    
    function touchMove(event) {
        if (!isDragging) return;
        touchEndX = event.touches[0].clientX;
        currentTranslate = prevTranslate + touchEndX - touchStartX;
    }
    
    function touchEnd() {
        isDragging = false;
        cancelAnimationFrame(animationID);
        
        const movedBy = touchEndX - touchStartX;
        if (Math.abs(movedBy) > 100) {
            if (movedBy > 0) {
            goToSlide(currentIndex - 1);
            } else {
            goToSlide(currentIndex + 1);
        }
    }
    
        startAutoSlide();
    }
    
    // Mouse events
    function mouseStart(event) {
        startPos = event.clientX;
        isDragging = true;
        stopAutoSlide();
        animationID = requestAnimationFrame(animation);
    }
    
    function mouseMove(event) {
        if (!isDragging) return;
        currentTranslate = prevTranslate + event.clientX - startPos;
    }
    
    function mouseEnd() {
        isDragging = false;
        cancelAnimationFrame(animationID);
        
        const movedBy = event.clientX - startPos;
        if (Math.abs(movedBy) > 100) {
            if (movedBy > 0) {
            goToSlide(currentIndex - 1);
            } else {
            goToSlide(currentIndex + 1);
            }
        }
        
        startAutoSlide();
    }
    
    // Animation function
    function animation() {
        if (isDragging) {
            slider.style.transform = `translateX(${currentTranslate}px)`;
            requestAnimationFrame(animation);
        }
    }
    
    // Add event listeners
    slider.addEventListener('touchstart', touchStart);
    slider.addEventListener('touchmove', touchMove);
    slider.addEventListener('touchend', touchEnd);
    
    slider.addEventListener('mousedown', mouseStart);
    slider.addEventListener('mousemove', mouseMove);
    slider.addEventListener('mouseup', mouseEnd);
    slider.addEventListener('mouseleave', mouseEnd);
    
    // Add event listeners to indicators
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            goToSlide(index);
            stopAutoSlide();
            startAutoSlide();
        });
    });
    
    // Start auto sliding
    startAutoSlide();
}

// Counter Animation
function animateCounter(element, target) {
    let current = 0;
    const duration = 4000; // 4 seconds
    const counter = element;
    const isMonetary = target >= 1000000;
    const framesPerSecond = 60;
    const totalFrames = duration * framesPerSecond / 1000;
    
    function formatNumber(num) {
        if (isMonetary) {
            return num.toLocaleString('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
                style: 'decimal'
            });
        }
        return num.toLocaleString('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }

    function easeOutElastic(x) {
        const c4 = (2 * Math.PI) / 3;
        return x === 0
            ? 0
            : x === 1
            ? 1
            : Math.pow(2, -10 * x) * Math.sin((x * 10 - 0.75) * c4) + 1;
    }

    function updateCounter(timestamp) {
        if (!updateCounter.startTime) {
            updateCounter.startTime = timestamp;
        }

        const elapsed = timestamp - updateCounter.startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easedProgress = easeOutElastic(progress);
        
        current = target * easedProgress;

        // Add visual feedback
        if (progress < 1) {
            counter.textContent = formatNumber(Math.round(current));
            
            // Add scale effect on significant digit changes
            if (Math.floor(current / 1000) !== Math.floor((target * (progress - 0.01)) / 1000)) {
                counter.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    counter.style.transform = 'scale(1)';
                }, 50);
            }
            
            requestAnimationFrame(updateCounter);
        } else {
            counter.textContent = formatNumber(target);
            
            // Add final animation
            counter.style.transform = 'scale(1.2)';
            setTimeout(() => {
                counter.style.transform = 'scale(1)';
            }, 50);
        }
    }

    // Initialize counter styles
    counter.style.transition = 'transform 0.2s ease';
    
    requestAnimationFrame(updateCounter);
}

// Initialize counter animations when elements are in view
function initCounterAnimation() {
    const counters = document.querySelectorAll('.counter');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.getAttribute('data-target'));
                if (!counter.hasAttribute('data-animated')) {
                    // Add pre-animation setup
                    counter.style.opacity = '0';
                    counter.style.transform = 'translateY(20px)';
                    
                    // Trigger animation with delay
                    setTimeout(() => {
                        counter.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                        counter.style.opacity = '1';
                        counter.style.transform = 'translateY(0)';
                        
                        // Start counter animation after entrance
                        setTimeout(() => {
                            animateCounter(counter, target);
                        }, 500);
                    }, 100);
                    
                    counter.setAttribute('data-animated', 'true');
                }
                observer.unobserve(counter);
            }
        });
    }, { 
        threshold: 0.5,
        rootMargin: '0px'
    });

    counters.forEach(counter => {
        observer.observe(counter);
    });
}

// Initialize mobile menu
function initMobileMenu() {
    const menuBtn = document.querySelector('.mobile-menu-btn');
    const menu = document.querySelector('.menu');
    
    if (menuBtn && menu) {
        menuBtn.addEventListener('click', () => {
            menu.classList.toggle('active');
            menuBtn.classList.toggle('active');
        });
    }
}

// Initialize header scroll effect
function initHeaderScroll() {
    const header = document.querySelector('header');
    
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }
}

// Add parallax effect to hero section
window.addEventListener('scroll', function() {
    const hero = document.querySelector('.hero');
    if (hero) {
        const scrollPosition = window.scrollY;
        hero.style.backgroundPosition = `center ${scrollPosition * 0.5}px`;
    }
});

// Add smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 100,
                behavior: 'smooth'
            });
        }
    });
});

// Add animated background particles
function initParticles() {
    const hero = document.querySelector('.hero');
    if (!hero) return;
    
    for (let i = 0; i < 50; i++) {
        const particle = document.createElement('div');
        particle.classList.add('particle');
        
        // Random position
        const posX = Math.random() * 100;
        const posY = Math.random() * 100;
        
        // Random size
        const size = Math.random() * 15 + 5;
        
        // Random opacity
        const opacity = Math.random() * 0.5 + 0.1;
        
        // Random animation duration
        const duration = Math.random() * 20 + 10;
        
        // Set styles
        particle.style.left = `${posX}%`;
        particle.style.top = `${posY}%`;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.opacity = opacity;
        particle.style.animationDuration = `${duration}s`;
        
        hero.appendChild(particle);
    }
}

// Initialize particles
initParticles();

// Add typing animation to hero heading
function initTypingAnimation() {
    const heroHeading = document.querySelector('.hero h1');
    if (!heroHeading) return;
    
    const text = heroHeading.textContent;
    heroHeading.textContent = '';
    
    let i = 0;
    const typeInterval = setInterval(() => {
        if (i < text.length) {
            heroHeading.textContent += text.charAt(i);
            i++;
        } else {
            clearInterval(typeInterval);
        }
    }, 100);
}

// Initialize typing animation after hero content fades in
setTimeout(initTypingAnimation, 1500);

// Add hover effects to navigation items
function initNavHoverEffects() {
    const navItems = document.querySelectorAll('nav .menu li a');
    
    navItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.classList.add('pulse');
            setTimeout(() => {
                this.classList.remove('pulse');
            }, 500);
        });
    });
}

// Initialize nav hover effects
initNavHoverEffects();

// Add 3D tilt effect to cards
function initTiltEffect() {
    const cards = document.querySelectorAll('.benefit-card, .blog-card');
    
    cards.forEach(card => {
        card.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const deltaX = (x - centerX) / centerX;
            const deltaY = (y - centerY) / centerY;
            
            this.style.transform = `perspective(1000px) rotateX(${deltaY * -5}deg) rotateY(${deltaX * 5}deg) translateY(-10px)`;
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
        });
    });
}

// Initialize tilt effect
initTiltEffect();

// Add animated background gradient
function initAnimatedGradient() {
    const ctaSection = document.querySelector('.cta');
    if (!ctaSection) return;
    
    let degree = 0;
    
    setInterval(() => {
        degree = (degree + 1) % 360;
        ctaSection.style.background = `linear-gradient(${degree}deg, var(--primary-color), var(--secondary-color))`;
    }, 100);
}

// Initialize animated gradient
initAnimatedGradient();

// Add scroll progress indicator
function initScrollProgress() {
    const progressBar = document.createElement('div');
    progressBar.classList.add('scroll-progress');
    document.body.appendChild(progressBar);
    
    window.addEventListener('scroll', () => {
        const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrollPercent = (scrollTop / scrollHeight) * 100;
        
        progressBar.style.width = `${scrollPercent}%`;
    });
}

// Initialize scroll progress
initScrollProgress();

// Add CSS for new animations
function addAnimationStyles() {
    const styleSheet = document.createElement('style');
    styleSheet.textContent = `
        .particle {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            pointer-events: none;
            animation: float-particle linear infinite;
        }
        
        @keyframes float-particle {
            0% {
                transform: translateY(0) translateX(0);
            }
            25% {
                transform: translateY(-20px) translateX(10px);
            }
            50% {
                transform: translateY(0) translateX(20px);
            }
            75% {
                transform: translateY(20px) translateX(10px);
            }
            100% {
                transform: translateY(0) translateX(0);
            }
        }
        
        .pulse {
            animation: pulse-animation 0.5s ease;
        }
        
        @keyframes pulse-animation {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
        
        .scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: var(--accent-color);
            z-index: 9999;
            width: 0%;
            transition: width 0.2s ease;
        }
    `;
    
    document.head.appendChild(styleSheet);
}

// Add animation styles
addAnimationStyles();

// Add animated transactions
function animateTransactions() {
    const tables = document.querySelectorAll('.transaction-table table tbody');
    let usedNames = new Set(); // Track used names
    let lastUpdateTime = new Map(); // Track when each name was last used
    
    // Diverse pool of names from various ethnicities and cultures
    const namePool = [
        // East Asian Names
        'Wei L.', 'Jin Z.', 'Yuki T.', 'Min-ji K.', 'Hiroshi S.', 'Mei C.', 
        'Jae-won P.', 'Xiao W.', 'Sakura M.', 'Chen Y.', 'Ji-eun L.', 'Tao X.',
        
        // South Asian Names
        'Arjun P.', 'Priya S.', 'Rahul M.', 'Deepa K.', 'Arun V.', 'Zara M.',
        'Raj P.', 'Anita B.', 'Krishna R.', 'Maya D.', 'Sanjay G.', 'Neha T.',
        
        // African/African American Names
        'Kwame A.', 'Zainab O.', 'Jamal W.', 'Amara K.', 'Marcus D.', 'Aisha B.',
        'Malik R.', 'Ebony C.', 'Oluwaseun A.', 'Chioma O.', 'Darnell J.', 'Imani W.',
        
        // Middle Eastern Names
        'Hassan M.', 'Fatima A.', 'Omar K.', 'Leila R.', 'Ahmad S.', 'Yasmin H.',
        'Karim N.', 'Noor A.', 'Mohammed R.', 'Amira S.', 'Ali H.', 'Rania M.',
        
        // European Names
        'Stefan B.', 'Isabella R.', 'Luca M.', 'Sofia P.', 'Henrik N.', 'Amélie D.',
        'Klaus W.', 'Elena V.', 'Pierre L.', 'Astrid H.', 'Giovanni C.', 'Natalia K.',
        
        // Latin American Names
        'Carlos R.', 'Ana M.', 'Luis P.', 'Carmen S.', 'Jorge L.', 'Isabel V.',
        'Miguel A.', 'Rosa L.', 'Diego M.', 'Maria F.', 'Roberto C.', 'Luna R.',
        
        // Indigenous/Native American Names
        'Kaya W.', 'Nova S.', 'River M.', 'Sierra B.', 'Dakota L.', 'Sky R.',
        
        // Pacific Islander Names
        'Kekoa M.', 'Leilani P.', 'Maui K.', 'Moana L.', 'Kai A.', 'Alana N.',
        
        // Mixed/Modern Names
        'Kai-ren T.', 'Aiden-Lee W.', 'Zara-Rose K.', 'Milan J.', 'Nico Z.', 'Aria K.',
        'Atlas M.', 'Eden P.', 'Zion R.', 'Phoenix L.', 'Rain S.', 'Storm D.',
        
        // Additional Global Names
        'Sven H.', 'Yara K.', 'Boris M.', 'Ines G.', 'Dmitri K.', 'Freya N.',
        'Thor A.', 'Indira R.', 'Leo T.', 'Nina P.', 'Oscar M.', 'Vera S.'
    ];

    function getRandomAmount() {
        // Generate random amount between 100 and 50000
        const min = 100;
        const max = 50000;
        const amount = Math.floor(Math.random() * (max - min + 1)) + min;
        return amount.toLocaleString();
    }

    function getUniqueName() {
        const currentTime = Date.now();
        // Remove names that were used more than an hour ago
        for (const [name, time] of lastUpdateTime.entries()) {
            if (currentTime - time > 3600000) { // 1 hour in milliseconds
                lastUpdateTime.delete(name);
                usedNames.delete(name);
            }
        }

        // Filter available names
        const availableNames = namePool.filter(name => !usedNames.has(name));
        
        // If all names are used, clear the used names set
        if (availableNames.length === 0) {
            usedNames.clear();
            lastUpdateTime.clear();
            return namePool[Math.floor(Math.random() * namePool.length)];
        }
        
        // Get random name from available names
        const randomIndex = Math.floor(Math.random() * availableNames.length);
        const selectedName = availableNames[randomIndex];
        
        // Mark name as used and record time
        usedNames.add(selectedName);
        lastUpdateTime.set(selectedName, currentTime);
        
        return selectedName;
    }

    function updateTransactionRow(row, isDeposit) {
        const cells = row.querySelectorAll('td');
        if (cells.length >= 3) {
            const name = getUniqueName();
            if (name) {
                const amount = getRandomAmount();
                
                // Animate out
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                
                setTimeout(() => {
                    // Update content
                    const icon = cells[0].querySelector('i');
                    if (icon) {
                        icon.style.color = isDeposit ? '#28a745' : '#dc3545';
                    }
                    cells[1].textContent = `$${amount}`;
                    cells[1].style.color = isDeposit ? '#28a745' : '#dc3545';
                    cells[2].textContent = name;
                
                // Animate in
                    row.style.transition = 'all 0.5s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateX(0)';
                }, 300);
            }
        }
    }

    function updateAllTransactions() {
        tables.forEach(table => {
            const isDeposit = table.closest('.transaction-table').querySelector('h2').textContent.includes('Deposit');
            const rows = table.querySelectorAll('tr');
            rows.forEach((row, index) => {
                setTimeout(() => {
                    updateTransactionRow(row, isDeposit);
                }, index * 200); // Stagger updates
            });
        });
    }

    // Initial update
    updateAllTransactions();

    // Update every 3 seconds
    setInterval(updateAllTransactions, 3000);
}

// Initialize transaction animations immediately
animateTransactions();

// Initialize logo carousel
function initLogoCarousel() {
    const slides = document.querySelectorAll('.logo-slide');
    const dots = document.querySelectorAll('.logo-dot');
    let currentIndex = 0;
    let interval;

    function showSlide(index) {
        // Remove active class from all slides and dots
        slides.forEach(slide => {
            slide.classList.remove('active');
            slide.style.transform = 'translateX(100%)';
        });
        dots.forEach(dot => dot.classList.remove('active'));

        // Handle index boundaries
        if (index >= slides.length) {
            currentIndex = 0;
        } else if (index < 0) {
            currentIndex = slides.length - 1;
        } else {
            currentIndex = index;
        }

        // Show current slide
        slides[currentIndex].classList.add('active');
        slides[currentIndex].style.transform = 'translateX(0)';
        dots[currentIndex].classList.add('active');
    }

    function nextSlide() {
        showSlide(currentIndex + 1);
    }

    function startCarousel() {
        if (interval) {
            clearInterval(interval);
        }
        interval = setInterval(nextSlide, 3000);
    }

    // Add click events to dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            showSlide(index);
            startCarousel();
        });
    });

    // Show first slide and start carousel
    showSlide(0);
    startCarousel();
}

