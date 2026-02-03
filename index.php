<?php 
require_once 'config/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    $role = getUserRole();
    switch ($role) {
        case 'admin':
            redirect('admin/dashboard.php');
            break;
        case 'farmer':
            redirect('farmer/dashboard.php');
            break;
        case 'buyer':
            redirect('buyer/dashboard.php');
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - কৃষকের সাথী, ক্রেতার বন্ধু</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-leaf"></i> Smart AgroSupport
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">হোম</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">সেবাসমূহ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">আমাদের সম্পর্কে</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-language"></i> <span id="currentLang">বাংলা</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                            <li><a class="dropdown-item" href="#" onclick="changeLanguage('bn')"><i class="fas fa-check text-success" id="check-bn"></i> বাংলা (Bangla)</a></li>
                            <li><a class="dropdown-item" href="#" onclick="changeLanguage('en')"><i class="fas fa-check text-success d-none" id="check-en"></i> English</a></li>
                        </ul>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="btn btn-light text-success" href="auth/login.php"><i class="fas fa-sign-in-alt"></i> লগইন</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-warning" href="auth/register.php"><i class="fas fa-user-plus"></i> রেজিস্টার</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1625246333195-78d9c38ad449?q=80&w=2070'); background-size: cover; background-position: center; background-attachment: fixed;">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold text-white mb-4">
                        কৃষকের সাথী<br>
                        <span class="text-warning">ক্রেতার বন্ধু</span>
                    </h1>
                    <p class="lead text-white mb-4">
                        Smart AgroSupport System - যেখানে কৃষক ও ক্রেতা সরাসরি যুক্ত হয়। 
                        পাবেন ফসলের সঠিক দাম, আবহাওয়ার পরামর্শ এবং বাজার তথ্য।
                    </p>
                    <div class="d-flex gap-3">
                        <a href="auth/register.php?role=farmer" class="btn btn-warning btn-lg">
                            <i class="fas fa-user-plus"></i> কৃষক হিসেবে যোগ দিন
                        </a>
                        <a href="auth/register.php?role=buyer" class="btn btn-light btn-lg">
                            <i class="fas fa-shopping-cart"></i> ক্রেতা হিসেবে যোগ দিন
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold text-success">আমাদের সেবাসমূহ</h2>
                <p class="lead text-muted">কৃষক ও ক্রেতাদের জন্য বিশেষ সুবিধা</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon bg-success text-white mb-3">
                            <i class="fas fa-chart-line fa-3x"></i>
                        </div>
                        <h4>ফসলের দাম</h4>
                        <p>প্রতিদিন আপডেট হওয়া ফসলের বাজার মূল্য দেখুন</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon bg-info text-white mb-3">
                            <i class="fas fa-cloud-sun-rain fa-3x"></i>
                        </div>
                        <h4>আবহাওয়া পরামর্শ</h4>
                        <p>আবহাওয়া অনুযায়ী ফসলের যত্নের পরামর্শ পান</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon bg-warning text-white mb-3">
                            <i class="fas fa-store fa-3x"></i>
                        </div>
                        <h4>মার্কেটপ্লেস</h4>
                        <p>ফসল সরাসরি ক্রেতার কাছে বিক্রি করুন</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon bg-danger text-white mb-3">
                            <i class="fas fa-phone fa-3x"></i>
                        </div>
                        <h4>সরাসরি যোগাযোগ</h4>
                        <p>কৃষক ও ক্রেতা দালাল ছাড়াই যুক্ত হন</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-5 bg-success text-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="stat-box">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <h2 class="display-4 fw-bold">5000+</h2>
                        <p>নিবন্ধিত কৃষক</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box">
                        <i class="fas fa-shopping-bag fa-3x mb-3"></i>
                        <h2 class="display-4 fw-bold">2000+</h2>
                        <p>ক্রেতা</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box">
                        <i class="fas fa-seedling fa-3x mb-3"></i>
                        <h2 class="display-4 fw-bold">10000+</h2>
                        <p>ফসল বিক্রয়</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box">
                        <i class="fas fa-map-marker-alt fa-3x mb-3"></i>
                        <h2 class="display-4 fw-bold">64</h2>
                        <p>জেলায় সেবা</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold text-success">কীভাবে কাজ করে?</h2>
                <p class="lead text-muted">সহজ ৪টি ধাপে শুরু করুন</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="step-card text-center">
                        <div class="step-number">1</div>
                        <h4>রেজিস্টার করুন</h4>
                        <p>কৃষক বা ক্রেতা হিসেবে নিবন্ধন করুন</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step-card text-center">
                        <div class="step-number">2</div>
                        <h4>ফসল পোস্ট করুন</h4>
                        <p>কৃষক আপনার ফসলের তথ্য দিন</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step-card text-center">
                        <div class="step-number">3</div>
                        <h4>যোগাযোগ করুন</h4>
                        <p>ক্রেতা কৃষকের সাথে সরাসরি যোগাযোগ করুন</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step-card text-center">
                        <div class="step-number">4</div>
                        <h4>লেনদেন সম্পন্ন</h4>
                        <p>দালাল ছাড়াই ব্যবসা করুন</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5 bg-warning">
        <div class="container text-center">
            <h2 class="display-4 fw-bold mb-4">আজই যোগ দিন Smart AgroSupport-এ</h2>
            <p class="lead mb-4">কৃষিতে নতুন যুগের সূচনা করুন</p>
            <a href="auth/register.php" class="btn btn-success btn-lg">
                <i class="fas fa-rocket"></i> এখনই শুরু করুন
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-leaf"></i> Smart AgroSupport System</h5>
                    <p>কৃষকের সাথী, ক্রেতার বন্ধু - সবার সহযোগী</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2026 Smart AgroSupport System. All rights reserved.</p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube fa-2x"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    
    <script>
        // Language translations
        const translations = {
            bn: {
                home: 'হোম',
                services: 'সেবাসমূহ',
                about: 'আমাদের সম্পর্কে',
                login: 'লগইন',
                register: 'রেজিস্টার',
                heroTitle1: 'কৃষকের সাথী',
                heroTitle2: 'ক্রেতার বন্ধু',
                heroDesc: 'Smart AgroSupport System - যেখানে কৃষক ও ক্রেতা সরাসরি যুক্ত হয়। পাবেন ফসলের সঠিক দাম, আবহাওয়ার পরামর্শ এবং বাজার তথ্য।',
                joinFarmer: 'কৃষক হিসেবে যোগ দিন',
                joinBuyer: 'ক্রেতা হিসেবে যোগ দিন',
                ourServices: 'আমাদের সেবাসমূহ',
                servicesDesc: 'কৃষক ও ক্রেতাদের জন্য বিশেষ সুবিধা',
                cropPrice: 'ফসলের দাম',
                cropPriceDesc: 'প্রতিদিন আপডেট হওয়া ফসলের বাজার মূল্য দেখুন',
                weather: 'আবহাওয়া পরামর্শ',
                weatherDesc: 'আবহাওয়া অনুযায়ী ফসলের যত্নের পরামর্শ পান',
                marketplace: 'মার্কেটপ্লেস',
                marketplaceDesc: 'ফসল সরাসরি ক্রেতার কাছে বিক্রি করুন',
                directContact: 'সরাসরি যোগাযোগ',
                directContactDesc: 'কৃষক ও ক্রেতা দালাল ছাড়াই যুক্ত হন',
                farmers: 'নিবন্ধিত কৃষক',
                buyers: 'ক্রেতা',
                sales: 'ফসল বিক্রয়',
                districts: 'জেলায় সেবা',
                howItWorks: 'কীভাবে কাজ করে?',
                howItWorksDesc: 'সহজ ৪টি ধাপে শুরু করুন',
                step1: 'রেজিস্টার করুন',
                step1Desc: 'কৃষক বা ক্রেতা হিসেবে নিবন্ধন করুন',
                step2: 'ফসল পোস্ট করুন',
                step2Desc: 'কৃষক আপনার ফসলের তথ্য দিন',
                step3: 'যোগাযোগ করুন',
                step3Desc: 'ক্রেতা কৃষকের সাথে সরাসরি যোগাযোগ করুন',
                step4: 'লেনদেন সম্পন্ন',
                step4Desc: 'দালাল ছাড়াই ব্যবসা করুন',
                ctaTitle: 'আজই যোগ দিন Smart AgroSupport-এ',
                ctaDesc: 'কৃষিতে নতুন যুগের সূচনা করুন',
                startNow: 'এখনই শুরু করুন',
                footerDesc: 'কৃষকের সাথী, ক্রেতার বন্ধু - সবার সহযোগী',
                currentLang: 'বাংলা'
            },
            en: {
                home: 'Home',
                services: 'Services',
                about: 'About Us',
                login: 'Login',
                register: 'Register',
                heroTitle1: 'Farmer\'s Companion',
                heroTitle2: 'Buyer\'s Friend',
                heroDesc: 'Smart AgroSupport System - where farmers and buyers connect directly. Get accurate crop prices, weather advice, and market information.',
                joinFarmer: 'Join as Farmer',
                joinBuyer: 'Join as Buyer',
                ourServices: 'Our Services',
                servicesDesc: 'Special benefits for farmers and buyers',
                cropPrice: 'Crop Prices',
                cropPriceDesc: 'View daily updated crop market prices',
                weather: 'Weather Advisory',
                weatherDesc: 'Get crop care advice based on weather',
                marketplace: 'Marketplace',
                marketplaceDesc: 'Sell crops directly to buyers',
                directContact: 'Direct Contact',
                directContactDesc: 'Farmers and buyers connect without middlemen',
                farmers: 'Registered Farmers',
                buyers: 'Buyers',
                sales: 'Crop Sales',
                districts: 'Districts Served',
                howItWorks: 'How It Works?',
                howItWorksDesc: 'Get started in 4 easy steps',
                step1: 'Register',
                step1Desc: 'Sign up as a farmer or buyer',
                step2: 'Post Crops',
                step2Desc: 'Farmers share your crop information',
                step3: 'Connect',
                step3Desc: 'Buyers contact farmers directly',
                step4: 'Complete Transaction',
                step4Desc: 'Do business without middlemen',
                ctaTitle: 'Join Smart AgroSupport Today',
                ctaDesc: 'Start a new era in agriculture',
                startNow: 'Start Now',
                footerDesc: 'Farmer\'s Companion, Buyer\'s Friend - Everyone\'s Partner',
                currentLang: 'English'
            }
        };

        // Get saved language or default to Bangla
        let currentLanguage = localStorage.getItem('language') || 'bn';

        function changeLanguage(lang) {
            currentLanguage = lang;
            localStorage.setItem('language', lang);
            
            // Update check marks
            document.getElementById('check-bn').classList.toggle('d-none', lang !== 'bn');
            document.getElementById('check-en').classList.toggle('d-none', lang !== 'en');
            
            // Update current language display
            document.getElementById('currentLang').textContent = translations[lang].currentLang;
            
            // Update all translatable elements
            updatePageLanguage(lang);
            
            // Show notification
            showToast(lang === 'bn' ? 'ভাষা পরিবর্তন করা হয়েছে' : 'Language changed successfully', 'success');
        }

        function updatePageLanguage(lang) {
            const t = translations[lang];
            
            // Navigation
            document.querySelectorAll('.nav-link')[0].textContent = t.home;
            document.querySelectorAll('.nav-link')[1].textContent = t.services;
            document.querySelectorAll('.nav-link')[2].textContent = t.about;
            
            // Hero section
            document.querySelector('.hero-section h1').innerHTML = `${t.heroTitle1}<br><span class="text-warning">${t.heroTitle2}</span>`;
            document.querySelector('.hero-section .lead').textContent = t.heroDesc;
            document.querySelectorAll('.hero-section .btn')[0].innerHTML = `<i class="fas fa-user-plus"></i> ${t.joinFarmer}`;
            document.querySelectorAll('.hero-section .btn')[1].innerHTML = `<i class="fas fa-shopping-cart"></i> ${t.joinBuyer}`;
            
            // Services section
            document.querySelector('#features .display-4').textContent = t.ourServices;
            document.querySelector('#features .lead').textContent = t.servicesDesc;
            document.querySelectorAll('#features h4')[0].textContent = t.cropPrice;
            document.querySelectorAll('#features p')[0].textContent = t.cropPriceDesc;
            document.querySelectorAll('#features h4')[1].textContent = t.weather;
            document.querySelectorAll('#features p')[1].textContent = t.weatherDesc;
            document.querySelectorAll('#features h4')[2].textContent = t.marketplace;
            document.querySelectorAll('#features p')[2].textContent = t.marketplaceDesc;
            document.querySelectorAll('#features h4')[3].textContent = t.directContact;
            document.querySelectorAll('#features p')[3].textContent = t.directContactDesc;
            
            // Stats section
            document.querySelectorAll('.stat-box p')[0].textContent = t.farmers;
            document.querySelectorAll('.stat-box p')[1].textContent = t.buyers;
            document.querySelectorAll('.stat-box p')[2].textContent = t.sales;
            document.querySelectorAll('.stat-box p')[3].textContent = t.districts;
            
            // How it works
            document.querySelector('#about .display-4').textContent = t.howItWorks;
            document.querySelector('#about .lead').textContent = t.howItWorksDesc;
            document.querySelectorAll('.step-card h4')[0].textContent = t.step1;
            document.querySelectorAll('.step-card p')[0].textContent = t.step1Desc;
            document.querySelectorAll('.step-card h4')[1].textContent = t.step2;
            document.querySelectorAll('.step-card p')[1].textContent = t.step2Desc;
            document.querySelectorAll('.step-card h4')[2].textContent = t.step3;
            document.querySelectorAll('.step-card p')[2].textContent = t.step3Desc;
            document.querySelectorAll('.step-card h4')[3].textContent = t.step4;
            document.querySelectorAll('.step-card p')[3].textContent = t.step4Desc;
            
            // CTA section
            document.querySelector('.cta-section .display-4').textContent = t.ctaTitle;
            document.querySelector('.cta-section .lead').textContent = t.ctaDesc;
            document.querySelector('.cta-section .btn').innerHTML = `<i class="fas fa-rocket"></i> ${t.startNow}`;
            
            // Footer
            document.querySelector('footer p').textContent = t.footerDesc;
            
            // Buttons
            document.querySelectorAll('.btn-light.text-success')[0].textContent = t.login;
            document.querySelectorAll('.btn-warning')[0].textContent = t.register;
        }

        // Initialize language on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (currentLanguage === 'en') {
                changeLanguage('en');
            }
        });
    </script>
</body>
</html>
