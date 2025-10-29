<footer class="bg-slate-900 text-white py-16 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <div>
                <div class="flex items-center space-x-2 mb-6">
                    <div class="w-12 h-8 flex items-center justify-center">
                        <img src="{{ asset('storage/assets/pod-logo.png') }}" alt="Logo" class="footer-pod-logo">
                    </div>
                    <span class="text-xl font-bold">People Of Data</span>
                </div>
                <p class="text-slate-400 leading-relaxed mb-6">
                    Egypt's premier community for AI and data professionals. Join us to network, learn, and grow together.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center hover:bg-primary transition-colors">
                        <i class="ri-facebook-fill text-lg"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center hover:bg-primary transition-colors">
                        <i class="ri-twitter-fill text-lg"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center hover:bg-primary transition-colors">
                        <i class="ri-linkedin-fill text-lg"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center hover:bg-primary transition-colors">
                        <i class="ri-instagram-fill text-lg"></i>
                    </a>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-6">Community</h3>
                <ul class="space-y-4 text-slate-400">
                    <li><a href="#" class="hover:text-white transition-colors">Forums</a></li>
                    <li><a href="{{ route('events.index') }}" class="hover:text-white transition-colors">Events</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Webinars</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Networking</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Mentorship</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-6">Opportunities</h3>
                <ul class="space-y-4 text-slate-400">
                    <li><a href="{{ route('jobs.index') }}" class="hover:text-white transition-colors">Job Board</a></li>
                    <li><a href="{{ route('internships.apply') }}" class="hover:text-white transition-colors">Internships</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Freelance</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Remote Work</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Career Advice</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-6">Stay Updated</h3>
                <p class="text-slate-400 mb-4">
                    Get the latest news, events, and job opportunities delivered to your inbox.
                </p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Enter your email" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
        <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-slate-400 text-sm">
                © 2024 People Of Data. All rights reserved.
            </p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Privacy Policy</a>
                <a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Terms of Service</a>
                <a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Contact Us</a>
            </div>
        </div>
    </div>
</footer>

<style>
/* Footer POD logo tweaks */
.footer-pod-logo {
    background: none !important;
    border-radius: 16px !important;
    border: 1.5px solid rgba(100, 116, 139, 0.18);
    filter: invert(1);
    width: 2.75rem;
    height: 2.75rem;
    object-fit: contain;
    padding: 0.35rem;
}

/* Newsletter form compound pill design */
.newsletter-form {
    display: flex;
    width: 100%;
    border-radius: 9999px;
    overflow: hidden;
    box-shadow: 0 2px 8px 0 rgba(100, 116, 139, 0.06);
    border: 1.5px solid rgba(100, 116, 139, 0.13);
    background: rgba(30, 41, 59, 0.85);
}

.newsletter-form input[type="email"] {
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
    color: #fff;
    padding: 0.85rem 1.2rem;
    font-size: 1rem;
    border-radius: 9999px 0 0 9999px;
    min-width: 100px
}

.newsletter-form input[type="email"]::placeholder {
    color: #cbd5e1;
}

.newsletter-form button {
    background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%);
    color: #fff;
    border: none;
    padding: 0 1.5rem;
    font-size: 0.9rem;
    min-width: 100px;
    font-weight: 600;
    border-radius: 0 9999px 9999px 0;
    transition: background 0.2s;
    cursor: pointer;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.newsletter-form button:hover {
    background: linear-gradient(90deg, #312e81 0%, #5b21b6 100%);
    transform: scale(1.06);
}
</style>