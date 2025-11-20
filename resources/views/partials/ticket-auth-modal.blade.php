<div id="authModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative overflow-hidden max-h-[90vh] flex flex-col">
        <!-- Gradient Header -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-800 p-4 text-center flex-shrink-0">
            <button class="absolute top-3 right-3 text-white hover:text-gray-200 text-2xl font-bold z-10" onclick="closeAuthModal()">&times;</button>
            <i class="fas fa-user-circle text-white text-4xl mb-2"></i>
            <h2 class="text-xl font-bold text-white">Welcome Back</h2>
            <p class="text-purple-100 text-xs mt-1">Login or create your account</p>
        </div>
        
        <form id="authForm" autocomplete="off" class="p-6 overflow-y-auto flex-grow">
            <div class="mb-3" id="nameFieldContainer">
                <label class="block text-gray-800 font-semibold mb-1 text-sm">
                    <i class="fas fa-user text-purple-600 mr-2"></i>Full Name
                </label>
                <input type="text" name="name" id="authName" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm">
            </div>

            <div class="mb-3">
                <label class="block text-gray-800 font-semibold mb-1 text-sm">
                    <i class="fas fa-envelope text-purple-600 mr-2"></i>Email Address
                </label>
                <input type="email" name="email" id="authEmail" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm" required>
            </div>

            <div class="mb-3" id="passwordField">
                <label class="block text-gray-800 font-semibold mb-1 text-sm">
                    <i class="fas fa-lock text-purple-600 mr-2"></i>Password
                </label>
                <input type="password" name="password" id="authPassword" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm" required>
            </div>

            <div class="mb-3" id="confirmPasswordField">
                <label class="block text-gray-800 font-semibold mb-1 text-sm">
                    <i class="fas fa-lock text-purple-600 mr-2"></i>Confirm Password
                </label>
                <input type="password" name="confirm_password" id="authConfirmPassword" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm">
            </div>

            <div class="mb-3 hidden" id="verificationField">
                <label class="block text-gray-800 font-semibold mb-1 text-sm">
                    <i class="fas fa-shield-alt text-purple-600 mr-2"></i>Verification Code
                </label>
                <input type="text" name="verification_code" id="verificationCode" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-center text-xl font-bold tracking-widest" maxlength="6" placeholder="000000">
                <div class="mt-2 p-2 bg-blue-50 border-l-4 border-blue-500 rounded">
                    <p class="text-xs text-blue-800">
                        <i class="fas fa-info-circle mr-1"></i>Check your email. Don't forget spam folder!
                    </p>
                </div>
                <div class="text-center mt-2">
                    <button type="button" id="resendCodeBtn" class="text-purple-600 hover:text-purple-800 text-xs font-semibold underline">
                        <i class="fas fa-redo-alt mr-1"></i>Resend Code
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" class="bg-gradient-to-r from-purple-600 to-purple-800 hover:from-purple-700 hover:to-purple-900 text-white px-4 py-2 rounded-lg font-bold w-full text-base shadow-lg transition transform hover:scale-105" id="authSubmitBtn">
                    <i class="fas fa-arrow-right mr-2"></i>Continue
                </button>
            </div>
            
            <div class="text-center mb-2">
                {{-- <div id="authError" class="text-xs text-red-600 font-semibold mb-1"></div> --}}
                <div id="authSuccess" class="text-xs text-green-600 font-semibold mb-1 hidden"></div>
            </div>
            
            <div class="text-center pt-3 border-t border-gray-200">
                <p class="text-xs text-gray-600 mb-2">Don't have an account yet?</p>
                <div class="flex gap-2 justify-center">
                    <a href="#" id="switchToRegister" class="text-purple-600 hover:text-purple-800 font-semibold text-sm hover:underline">
                        <i class="fas fa-user-plus mr-1"></i>Register
                    </a>
                    <span class="text-gray-400">|</span>
                    <a href="#" id="switchToLogin" class="text-purple-600 hover:text-purple-800 font-semibold text-sm hover:underline">
                        <i class="fas fa-sign-in-alt mr-1"></i>Login
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Ticket Auth modal JS - reusable
(function(){
    if (window.ticketAuthModalInitialized) return; // idempotent
    window.ticketAuthModalInitialized = true;

    function openAuthModal() {
        const el = document.getElementById('authModal');
        if (el) el.classList.remove('hidden');
    }
    function closeAuthModal() {
        const el = document.getElementById('authModal');
        if (el) el.classList.add('hidden');
    }
    window.openAuthModal = openAuthModal;
    window.closeAuthModal = closeAuthModal;

    let authMode = 'login';
    function setAuthMode(mode) {
        authMode = mode;
        const authError = document.getElementById('authError');
        const authSuccess = document.getElementById('authSuccess');
        if (authError) authError.textContent = '';
        if (authSuccess) {
            authSuccess.textContent = '';
            authSuccess.classList.add('hidden');
        }
        
        const verificationField = document.getElementById('verificationField');
        const passwordField = document.getElementById('passwordField');
        const confirmPasswordField = document.getElementById('confirmPasswordField');
        const nameFieldContainer = document.getElementById('nameFieldContainer');
        const submitBtn = document.getElementById('authSubmitBtn');
        
        verificationField.classList.add('hidden');
        passwordField.classList.remove('hidden');
        confirmPasswordField.classList.add('hidden');
        nameFieldContainer.classList.add('hidden');
        
        if (mode === 'register') {
            submitBtn.innerHTML = '<i class="fas fa-user-plus mr-2"></i>Create Account';
            confirmPasswordField.classList.remove('hidden');
            nameFieldContainer.classList.remove('hidden');
        } else if (mode === 'verify') {
            submitBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Verify Account';
            verificationField.classList.remove('hidden');
            passwordField.classList.add('hidden');
            nameFieldContainer.classList.add('hidden');
        } else {
            submitBtn.innerHTML = '<i class="fas fa-sign-in-alt mr-2"></i>Login';
        }
    }
    window.setAuthMode = setAuthMode;
    setAuthMode('login');

    document.getElementById('switchToRegister').addEventListener('click', function(e){ e.preventDefault(); setAuthMode('register'); });
    document.getElementById('switchToLogin').addEventListener('click', function(e){ e.preventDefault(); setAuthMode('login'); });

    // Resend verification code handler
    document.getElementById('resendCodeBtn').addEventListener('click', async function(e) {
        e.preventDefault();
        const email = document.getElementById('authEmail').value.trim();
        const authError = document.getElementById('authError');
        const authSuccess = document.getElementById('authSuccess');
        const btn = e.target.closest('button');
        
        if (!email) {
            authError.textContent = 'Please enter your email address';
            return;
        }
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Sending...';
        
        try {
            const res = await ajaxPost('/ajax/ticket-auth/resend-code', { email });
            if (res.success) {
                authSuccess.textContent = 'Verification code resent! Check your email and spam folder.';
                authSuccess.classList.remove('hidden');
                authError.textContent = '';
            } else {
                authError.textContent = res.message || 'Failed to resend code';
                authSuccess.classList.add('hidden');
            }
        } catch (err) {
            authError.textContent = 'Server error. Please try again.';
            authSuccess.classList.add('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-redo-alt mr-1"></i>Resend Verification Code';
        }
    });

    async function ajaxPost(url, data) {
        const token = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : (document.querySelector('input[name="_token"]') ? document.querySelector('input[name="_token"]').value : '');
        const resp = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify(data)
        });
        return resp.json();
    }

    document.getElementById('authForm').addEventListener('submit', async function(e){
        e.preventDefault();
        const email = document.getElementById('authEmail').value.trim();
        const password = document.getElementById('authPassword').value;
        const name = document.getElementById('authName').value.trim();
        const code = document.getElementById('verificationCode').value.trim();
        const authError = document.getElementById('authError');
        authError.textContent = '';

        let data = { email };
        let url = '';
        if (authMode === 'register') {
            url = '/ajax/ticket-auth/register';
            data.password = password;
            data.name = name;
            const confirm = document.getElementById('authConfirmPassword').value;
            if (password !== confirm) { authError.textContent = 'Passwords do not match.'; return; }
        } else if (authMode === 'login') {
            url = '/ajax/ticket-auth/login';
            data.password = password;
        } else if (authMode === 'verify') {
            url = '/ajax/ticket-auth/verify';
            data.code = code;
        }

        try {
            const res = await ajaxPost(url, data);
            if (res.success) {
                if (authMode === 'register') {
                    const authSuccess = document.getElementById('authSuccess');
                    const authError = document.getElementById('authError');
                    authSuccess.textContent = '✓ Verification code sent to ' + email + '. Check your email and spam folder.';
                    authSuccess.classList.remove('hidden');
                    authError.textContent = '';
                    setAuthMode('verify');
                    return;
                }
                // Success in verify or login
                closeAuthModal();
                
                // Check if this is from invest page (data already filled)
                if (window._investmentFormData) {
                    // For invest page, skip investor modal since they already filled the form
                    // Just trigger the submission
                    window.dispatchEvent(new CustomEvent('investorProfileSkipped'));
                    return;
                }
                
                // Check if user has investor profile (only for customer role)
                try {
                    const profileResp = await fetch('/users/investor-profile', {
                        headers: { 
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    const profileData = await profileResp.json();
                    
                    console.log('Profile data received:', profileData);
                    
                    // Wait a bit to ensure modal is ready
                    setTimeout(() => {
                        // If profile exists, load it into modal; otherwise modal starts empty
                        if (profileData.success && profileData.profile) {
                            if (typeof window.loadInvestorProfile === 'function') {
                                window.loadInvestorProfile(profileData.profile);
                            }
                        }
                        
                        // Show investor info modal for review/edit
                        const modalElement = document.getElementById('investorInfoModal');
                        if (modalElement) {
                            const investorModal = new bootstrap.Modal(modalElement);
                            investorModal.show();
                            console.log('Investor modal displayed successfully');
                        } else {
                            console.error('Investor modal element not found!');
                            // If modal not found, proceed with form submission
                            if (window._ticketAuthPendingForm) {
                                window._ticketAuthPendingForm.submit();
                            }
                        }
                        
                        // Store pending form for later submission
                        window._investorProfilePendingForm = window._ticketAuthPendingForm;
                        window._ticketAuthPendingForm = null;
                    }, 300);
                    
                } catch (profileErr) {
                    console.error('Failed to load investor profile:', profileErr);
                    // If profile check fails, proceed with form submission anyway
                    if (window._ticketAuthPendingForm) {
                        const f = window._ticketAuthPendingForm;
                        window._ticketAuthPendingForm = null;
                        
                        // Fetch a fresh CSRF token
                        try {
                            const csrfResp = await fetch('/refresh-csrf', {
                                method: 'GET',
                                headers: { 'Accept': 'application/json' }
                            });
                            const csrfData = await csrfResp.json();
                            
                            // Update CSRF token in the form
                            const tokenInput = f.querySelector('input[name="_token"]');
                            if (tokenInput && csrfData.token) {
                                tokenInput.value = csrfData.token;
                            }
                            
                            // Update meta tag too
                            const metaTag = document.querySelector('meta[name="csrf-token"]');
                            if (metaTag && csrfData.token) {
                                metaTag.setAttribute('content', csrfData.token);
                            }
                        } catch (err) {
                            console.error('CSRF refresh failed:', err);
                        }
                        
                        // Submit the form
                        f.submit();
                    }
                }
            } else {
                if (res.require_verification) {
                    setAuthMode('verify');
                }
                authError.textContent = res.message || 'An error occurred';
            }
        } catch (err) {
            authError.textContent = 'Server error. Please try again.';
        }
    });

    // Intercept forms that submit to /tickets
    document.addEventListener('submit', function(e){
        const form = e.target;
        if (!form || form.tagName !== 'FORM') return;
        const action = form.getAttribute('action');
        if (!action) return;
        if (action.includes('/tickets')) {
            // Check auth status via ajax
            e.preventDefault();
            ajaxPost('/ajax/ticket-auth/check', {}).then(res => {
                if (res.authenticated && res.verified) {
                    form.submit();
                } else {
                    window._ticketAuthPendingForm = form;
                    // Always show login first
                    setAuthMode('login');
                    openAuthModal();
                    // Only switch mode if user is already authenticated but not verified
                    if (res.authenticated && !res.verified) {
                        setTimeout(() => setAuthMode('verify'), 100);
                    }
                }
            }).catch(() => {
                setAuthMode('login');
                openAuthModal();
            });
        }
    }, true);

    // Handle investor profile saved event
    window.addEventListener('investorProfileSaved', async function() {
        if (window._investorProfilePendingForm) {
            const f = window._investorProfilePendingForm;
            window._investorProfilePendingForm = null;
            
            // Fetch a fresh CSRF token
            try {
                const csrfResp = await fetch('/refresh-csrf', {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                });
                const csrfData = await csrfResp.json();
                
                // Update CSRF token in the form
                const tokenInput = f.querySelector('input[name="_token"]');
                if (tokenInput && csrfData.token) {
                    tokenInput.value = csrfData.token;
                }
                
                // Update meta tag too
                const metaTag = document.querySelector('meta[name="csrf-token"]');
                if (metaTag && csrfData.token) {
                    metaTag.setAttribute('content', csrfData.token);
                }
            } catch (err) {
                console.error('CSRF refresh failed:', err);
            }
            
            // Submit the form
            f.submit();
        }
    });

    // Handle investor profile skipped event
    window.addEventListener('investorProfileSkipped', async function() {
        if (window._investorProfilePendingForm) {
            const f = window._investorProfilePendingForm;
            window._investorProfilePendingForm = null;
            
            // Fetch a fresh CSRF token
            try {
                const csrfResp = await fetch('/refresh-csrf', {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                });
                const csrfData = await csrfResp.json();
                
                // Update CSRF token in the form
                const tokenInput = f.querySelector('input[name="_token"]');
                if (tokenInput && csrfData.token) {
                    tokenInput.value = csrfData.token;
                }
                
                // Update meta tag too
                const metaTag = document.querySelector('meta[name="csrf-token"]');
                if (metaTag && csrfData.token) {
                    metaTag.setAttribute('content', csrfData.token);
                }
            } catch (err) {
                console.error('CSRF refresh failed:', err);
            }
            
            // Submit the form
            f.submit();
        }
    });
})();
</script>
