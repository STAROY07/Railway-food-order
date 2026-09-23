// Centralized LocalStorage Data Manager for Railway Food Order (Client-side / Netlify compatible)

const DEFAULT_USERS = [
    { id: 1, name: "Rahul Sharma", email: "user@example.com", password: "password123", phone: "9876543210" },
    { id: 2, name: "Pooja Verma", email: "pooja@example.com", password: "password123", phone: "9812345678" }
];

const DEFAULT_RESTAURANTS = [
    { id: 1, name: "Haldiram's Express", owner: "Ramesh Gupta", email: "haldirams@station.com", phone: "9876501234", station: "New Delhi", cuisine: "North Indian, Sweets", status: "Approved" },
    { id: 2, name: "Bikanervala Train Bites", owner: "Suresh Agarwal", email: "bikanervala@station.com", phone: "9876501235", station: "Jaipur", cuisine: "Rajasthani, Snacks", status: "Approved" },
    { id: 3, name: "Domino's Railway Hub", owner: "Anita Roy", email: "dominos@station.com", phone: "9876501236", station: "Mumbai Central", cuisine: "Pizza, Fast Food", status: "Approved" },
    { id: 4, name: "Pind Balluchi On Wheels", owner: "Harpreet Singh", email: "pind@station.com", phone: "9876501237", station: "Amritsar", cuisine: "Punjabi, Tandoor", status: "Approved" }
];

const DEFAULT_ORDERS = [
    {
        id: 101,
        user_email: "user@example.com",
        food_name: "Paneer Butter Masala x 1, Veg Biryani x 1",
        items: [
            { name: "Paneer Butter Masala", price: 180, qty: 1 },
            { name: "Veg Biryani", price: 140, qty: 1 }
        ],
        price: 320,
        quantity: 2,
        total_price: 320,
        station: "New Delhi",
        coach: "B2",
        seat_no: "42",
        pnr: "2847291048",
        payment_method: "UPI (Online)",
        payment_status: "Paid",
        order_status: "On Track",
        created_at: new Date(Date.now() - 3600000).toLocaleString()
    },
    {
        id: 102,
        user_email: "user@example.com",
        food_name: "Chicken Biryani x 2",
        items: [
            { name: "Chicken Biryani", price: 220, qty: 2 }
        ],
        price: 440,
        quantity: 2,
        total_price: 440,
        station: "Kanpur Central",
        coach: "A1",
        seat_no: "18",
        pnr: "4920184719",
        payment_method: "Card (Online)",
        payment_status: "Paid",
        order_status: "Delivered",
        created_at: new Date(Date.now() - 86400000).toLocaleString()
    }
];

const DEFAULT_COMPLAINTS = [
    { id: 1, user_email: "user@example.com", name: "Rahul Sharma", subject: "Packaging Damaged", message: "Food packaging was slightly crushed during transit at Agra Cantt.", status: "Resolved", created_at: "2026-09-21 14:30" }
];

const DEFAULT_FEEDBACK = [
    { id: 1, user_email: "user@example.com", name: "Rahul Sharma", rating: 5, message: "Super fast delivery directly to my seat at New Delhi station. Delicious hot food!", created_at: "2026-09-22 18:15" },
    { id: 2, user_email: "pooja@example.com", name: "Pooja Verma", rating: 4, message: "Loved the veg thali, very hygienic and fresh.", created_at: "2026-09-22 20:00" }
];

const STATIONS = [
    "New Delhi", "Mumbai Central", "Chennai Central", "Kolkata Howrah", "Bengaluru City",
    "Hyderabad Secunderabad", "Ahmedabad", "Pune Junction", "Jaipur", "Lucknow",
    "Patna Junction", "Bhopal Junction", "Nagpur", "Surat", "Kanpur Central",
    "Vadodara", "Coimbatore", "Kochi Ernakulam", "Visakhapatnam", "Indore",
    "Amritsar", "Chandigarh", "Agra Cantt", "Varanasi", "Guwahati",
    "Ranchi", "Bhubaneswar", "Thiruvananthapuram", "Jodhpur", "Allahabad",
    "Haridwar", "Dehradun", "Shimla", "Jammu Tawi", "Mysuru",
    "Madurai", "Tirupati", "Vijayawada", "Nashik Road", "Aurangabad"
];

// Initialize Storage
function initStorage() {
    if (!localStorage.getItem('rf_users')) {
        localStorage.setItem('rf_users', JSON.stringify(DEFAULT_USERS));
    }
    if (!localStorage.getItem('rf_restaurants')) {
        localStorage.setItem('rf_restaurants', JSON.stringify(DEFAULT_RESTAURANTS));
    }
    if (!localStorage.getItem('rf_orders')) {
        localStorage.setItem('rf_orders', JSON.stringify(DEFAULT_ORDERS));
    }
    if (!localStorage.getItem('rf_complaints')) {
        localStorage.setItem('rf_complaints', JSON.stringify(DEFAULT_COMPLAINTS));
    }
    if (!localStorage.getItem('rf_feedback')) {
        localStorage.setItem('rf_feedback', JSON.stringify(DEFAULT_FEEDBACK));
    }
    if (!localStorage.getItem('rf_cart')) {
        localStorage.setItem('rf_cart', JSON.stringify({}));
    }
}
initStorage();

// Auth Helpers
function getCurrentUser() {
    const userStr = localStorage.getItem('rf_current_user');
    return userStr ? JSON.parse(userStr) : null;
}

function requireAuth(redirect = 'login.html') {
    const user = getCurrentUser();
    if (!user) {
        window.location.href = redirect;
        return null;
    }
    return user;
}

function loginUser(email, password) {
    const users = JSON.parse(localStorage.getItem('rf_users') || '[]');
    const found = users.find(u => u.email.toLowerCase() === email.trim().toLowerCase() && u.password === password);
    if (found) {
        localStorage.setItem('rf_current_user', JSON.stringify(found));
        return { success: true, user: found };
    }
    return { success: false, message: "Invalid email or password." };
}

function registerUser(name, email, password, phone = '') {
    const users = JSON.parse(localStorage.getItem('rf_users') || '[]');
    if (users.find(u => u.email.toLowerCase() === email.trim().toLowerCase())) {
        return { success: false, message: "Email already registered. Please login." };
    }
    const newUser = {
        id: Date.now(),
        name: name.trim(),
        email: email.trim(),
        password: password,
        phone: phone.trim()
    };
    users.push(newUser);
    localStorage.setItem('rf_users', JSON.stringify(users));
    return { success: true, user: newUser };
}

function logoutUser() {
    localStorage.removeItem('rf_current_user');
    window.location.href = 'login.html';
}

// Admin Auth
function getAdminSession() {
    return localStorage.getItem('rf_admin_logged') === 'true';
}

function loginAdmin(email, password) {
    if (email.trim() === 'admin@railway.com' && password === 'admin123') {
        localStorage.setItem('rf_admin_logged', 'true');
        return true;
    }
    // allow generic admin as well
    if (email.trim().toLowerCase().includes('admin') && (password === 'admin' || password === 'admin123')) {
        localStorage.setItem('rf_admin_logged', 'true');
        return true;
    }
    return false;
}

function logoutAdmin() {
    localStorage.removeItem('rf_admin_logged');
    window.location.href = 'admin_login.html';
}

function requireAdmin() {
    if (!getAdminSession()) {
        window.location.href = 'admin_login.html';
    }
}

// Cart Helpers
function getUserCartKey() {
    const user = getCurrentUser();
    return user ? `rf_cart_${user.email}` : 'rf_cart_guest';
}

function getCart() {
    const key = getUserCartKey();
    return JSON.parse(localStorage.getItem(key) || '[]');
}

function saveCart(cart) {
    const key = getUserCartKey();
    localStorage.setItem(key, JSON.stringify(cart));
    updateCartBadges();
}

function addToCart(itemName, price, img = '', qty = 1) {
    const cart = getCart();
    const existing = cart.find(i => i.name === itemName);
    if (existing) {
        existing.qty += qty;
    } else {
        cart.push({
            id: Date.now(),
            name: itemName,
            price: Number(price),
            img: img,
            qty: qty
        });
    }
    saveCart(cart);
    showToast(`"${itemName}" added to cart!`);
}

function updateCartQty(itemName, qty) {
    let cart = getCart();
    if (qty <= 0) {
        cart = cart.filter(i => i.name !== itemName);
    } else {
        const item = cart.find(i => i.name === itemName);
        if (item) item.qty = qty;
    }
    saveCart(cart);
}

function removeFromCart(itemName) {
    let cart = getCart();
    cart = cart.filter(i => i.name !== itemName);
    saveCart(cart);
}

function clearCart() {
    const key = getUserCartKey();
    localStorage.removeItem(key);
    updateCartBadges();
}

function getCartTotal() {
    const cart = getCart();
    return cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
}

function getCartCount() {
    const cart = getCart();
    return cart.reduce((sum, item) => sum + item.qty, 0);
}

function updateCartBadges() {
    const count = getCartCount();
    document.querySelectorAll('.cart-badge').forEach(el => {
        el.textContent = count;
        el.style.display = count > 0 ? 'inline-block' : 'none';
    });
}

// Toast notification
function showToast(msg, bg = '#16a34a') {
    let container = document.getElementById('rf-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'rf-toast-container';
        container.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:99999;display:flex;flex-direction:column;gap:10px;';
        document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    toast.style.cssText = `background:${bg};color:#fff;padding:12px 22px;border-radius:10px;font-family:'Poppins',sans-serif;font-size:14px;box-shadow:0 8px 24px rgba(0,0,0,0.3);display:flex;align-items:center;gap:8px;animation:fadeIn 0.3s ease;`;
    toast.innerHTML = `<span>✔</span> ${msg}`;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.4s ease';
        setTimeout(() => toast.remove(), 400);
    }, 2500);
}

document.addEventListener('DOMContentLoaded', updateCartBadges);
