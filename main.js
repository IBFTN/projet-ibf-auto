(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        initMobileMenu();
        initGallery();
        initCatalog();
        initCarDetails();
        initContactForm();
        initSmoothScroll();
        initBuyButtons();
        initAuth();
    });

    function initMobileMenu() {
        const navCheck = document.getElementById('navCheck');
        const primaryNav = document.getElementById('primaryNav');
        if (navCheck && primaryNav) {
            document.addEventListener('click', function(e) {
                if (!navCheck.contains(e.target) && !primaryNav.contains(e.target)) {
                    navCheck.checked = false;
                }
            });
        }
    }

    function initGallery() {
        const galleryGrid = document.getElementById('galleryGrid');
        if (!galleryGrid) return;

        fetch('api.php?action=featured')
            .then(function(response) { return response.json(); })
            .then(function(cars) {
                galleryGrid.innerHTML = '';
                cars.forEach(function(car) {
                    const figure = document.createElement('figure');
                    figure.className = 'gallery-item';
                    figure.innerHTML = '<img src="' + escapeHtml(car.image_blob || car.image_url) + '" alt="' + escapeHtml(car.brand_name + ' ' + car.model) + '">';
                    galleryGrid.appendChild(figure);
                });
            })
            .catch(function(err) { console.error('Error loading gallery:', err); });
    }

    function initCatalog() {
        const catalogGrid = document.getElementById('catalogGrid');
        if (!catalogGrid) return;

        const urlParams = new URLSearchParams(window.location.search);
        const brandFilter = urlParams.get('brand') || 'all';

        fetch('api.php?action=cars' + (brandFilter !== 'all' ? '&brand=' + encodeURIComponent(brandFilter) : ''))
            .then(function(response) { return response.json(); })
            .then(function(cars) {
                catalogGrid.innerHTML = '';
                cars.forEach(function(car) {
                    const brandClass = getBrandClass(car.brand_name);
                    const whatsappMsg = 'Hello%20I%20am%20interested%20in%20' + encodeURIComponent(car.brand_name + ' ' + car.model + ' ' + car.year + '%20-%20$' + numberFormat(car.price));
                    const article = document.createElement('article');
                    article.className = 'card ' + brandClass;
                    article.innerHTML =
                        '<img src="' + escapeHtml(car.image_url) + '" alt="' + escapeHtml(car.brand_name + ' ' + car.model) + '">' +
                        '<div class="info">' +
                            '<div class="title">' + escapeHtml(car.brand_name + ' ' + car.model) + '</div>' +
                            '<div class="meta">' + car.year + '</div>' +
                            '<div class="price">$' + numberFormat(car.price) + '</div>' +
                        '</div>' +
                        '<div class="actions">' +
                            '<button class="btn btn-primary buy-btn" data-car-id="' + car.id + '" data-car-name="' + escapeHtml(car.brand_name + ' ' + car.model) + '">Buy</button>' +
                            '<a class="btn" href="car.html?id=' + car.id + '">Details</a>' +
                        '</div>';
                    catalogGrid.appendChild(article);
                });

                if (brandFilter !== 'all') {
                    const radio = document.getElementById('filter-' + getBrandRadioId(car.brand_name));
                    if (radio) radio.checked = true;
                }
            })
            .catch(function(err) { console.error('Error loading catalog:', err); });
    }

    function initCarDetails() {
        const carHero = document.getElementById('carHero');
        if (!carHero) return;

        const urlParams = new URLSearchParams(window.location.search);
        const carId = urlParams.get('id');

        if (!carId) {
            window.location.href = 'catalog.html';
            return;
        }

        fetch('api.php?action=car&id=' + carId)
            .then(function(response) { return response.json(); })
            .then(function(car) {
                if (car.error) {
                    window.location.href = 'catalog.html';
                    return;
                }

                carHero.style.background = 'url("' + escapeHtml(car.image_url) + '") center/cover no-repeat';
                document.getElementById('carTitle').textContent = car.brand_name + ' ' + car.model;
                document.getElementById('carOverview').textContent = 'Year ' + car.year + ' • Price $' + numberFormat(car.price);

                const specs = document.getElementById('carSpecs');
                specs.innerHTML =
                    '<div>Brand: ' + escapeHtml(car.brand_name) + '</div>' +
                    '<div>Model: ' + escapeHtml(car.model) + '</div>';
                if (car.engine) specs.innerHTML += '<div>Engine: ' + escapeHtml(car.engine) + '</div>';
                if (car.power) specs.innerHTML += '<div>Power: ' + escapeHtml(car.power) + '</div>';
                if (car.drive) specs.innerHTML += '<div>Drive: ' + escapeHtml(car.drive) + '</div>';

                const whatsappMsg = 'Hello%20I%20am%20interested%20in%20' + encodeURIComponent(car.brand_name + ' ' + car.model + ' ' + car.year + '%20-%20$' + numberFormat(car.price));
                document.getElementById('whatsappBtn').href = 'https://wa.me/21693754078?text=' + whatsappMsg;
                document.getElementById('carImage').src = car.image_url;
                document.getElementById('carImage').alt = car.brand_name + ' ' + car.model;
                document.title = car.brand_name + ' ' + car.model + ' — IBF Motors';
            })
            .catch(function(err) { console.error('Error loading car:', err); });
    }

    function initContactForm() {
        const form = document.getElementById('contactForm');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const alertDiv = document.getElementById('formAlert') || createAlertDiv(form);

            const name = form.querySelector('[name="name"]').value.trim();
            const email = form.querySelector('[name="email"]').value.trim();
            const phone = form.querySelector('[name="phone"]').value.trim();
            const message = form.querySelector('[name="message"]').value.trim();

            if (!name || !email || !message) {
                showAlert(alertDiv, 'Please fill in all required fields.', 'error');
                return;
            }

            if (!isValidEmail(email)) {
                showAlert(alertDiv, 'Please enter a valid email address.', 'error');
                return;
            }

            if (phone && !isValidPhone(phone)) {
                showAlert(alertDiv, 'Please enter a valid phone number.', 'error');
                return;
            }

            fetch('api.php?action=contact', {
                method: 'POST',
                body: formData
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    showAlert(alertDiv, data.message, 'success');
                    form.reset();
                } else {
                    showAlert(alertDiv, data.error, 'error');
                }
            })
            .catch(function(err) {
                showAlert(alertDiv, 'Something went wrong. Please try again.', 'error');
            });
        });
    }

    function createAlertDiv(form) {
        const div = document.createElement('div');
        div.id = 'formAlert';
        form.insertBefore(div, form.firstChild);
        return div;
    }

    function showAlert(div, message, type) {
        div.className = 'alert alert-' + type;
        div.textContent = message;
        div.style.display = 'block';
        setTimeout(function() {
            div.style.display = 'none';
        }, 5000);
    }

    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    }

    function initBuyButtons() {
        document.addEventListener('click', function(e) {
            const buyBtn = e.target.closest('.buy-btn');
            if (buyBtn) {
                const carId = buyBtn.getAttribute('data-car-id');
                const carName = buyBtn.getAttribute('data-car-name');

                fetch('api.php?action=car&id=' + carId)
                    .then(function(response) { return response.json(); })
                    .then(function(car) {
                        if (car.error) {
                            alert('Car not found');
                            return;
                        }

                        const cartItem = {
                            id: car.id,
                            model: (car.brand_name || '') + ' ' + car.model,
                            year: car.year,
                            price: parseFloat(car.price),
                            engine: car.engine,
                            image_blob: car.image_blob || car.image_url,
                            image_url: car.image_url
                        };

                        let cart = JSON.parse(localStorage.getItem('cart')) || [];
                        const exists = cart.some(item => item.id === car.id);

                        if (exists) {
                            alert('This car is already in your cart!');
                        } else {
                            cart.push(cartItem);
                            localStorage.setItem('cart', JSON.stringify(cart));
                            alert('Car added to cart! Go to checkout to complete your order.');
                        }
                    })
                    .catch(function(err) {
                        console.error('Error adding to cart:', err);
                        alert('Failed to add to cart');
                    });
            }
        });
    }

    function initAuth() {
        const navAuth = document.getElementById('navAuth');
        if (!navAuth) return;
        
        const userData = localStorage.getItem('user');
        
        if (userData) {
            const user = JSON.parse(userData);
            navAuth.textContent = 'Logout';
            navAuth.href = '#';
            navAuth.addEventListener('click', function(e) {
                e.preventDefault();
                localStorage.removeItem('user');
                fetch('auth.php?action=logout')
                    .then(function() { window.location.href = 'index.html'; })
                    .catch(function() { window.location.href = 'index.html'; });
            });
        }
    }

    function getBrandClass(brandName) {
        const classes = {
            'Ferrari': 'brand-Ferrari',
            'Lamborghini': 'brand-Lamborghini',
            'Porsche': 'brand-Porsche',
            'McLaren': 'brand-McLaren',
            'Bugatti': 'brand-Bugatti',
            'Aston Martin': 'brand-Aston',
            'Mercedes': 'brand-Mercedes',
            'BMW': 'brand-BMW',
            'Bentley': 'brand-Bentley',
            'Rolls-Royce': 'brand-Rolls'
        };
        return classes[brandName] || 'brand-All';
    }

    function getBrandRadioId(brandName) {
        const ids = {
            'Ferrari': 'Ferrari',
            'Lamborghini': 'Lamborghini',
            'Porsche': 'Porsche',
            'McLaren': 'McLaren',
            'Bugatti': 'Bugatti',
            'Aston Martin': 'Aston',
            'Mercedes': 'Mercedes',
            'BMW': 'BMW',
            'Bentley': 'Bentley',
            'Rolls-Royce': 'Rolls'
        };
        return ids[brandName] || 'all';
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function isValidPhone(phone) {
        return /^[0-9+\-\s()]{8,20}$/.test(phone);
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function numberFormat(num) {
        return parseFloat(num).toLocaleString('en-US');
    }

    window.addEventListener('scroll', function() {
        const header = document.querySelector('.site-header');
        if (header) {
            header.classList.toggle('scrolled', window.scrollY > 50);
        }
    });
})();