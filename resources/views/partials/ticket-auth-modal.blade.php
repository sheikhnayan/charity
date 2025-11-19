<div id="authModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-8 relative">
        <button class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl font-bold" onclick="closeAuthModal()">&times;</button>
        <h2 class="text-2xl font-bold mb-4 text-center">Login or Register to Continue</h2>
        <form id="authForm" autocomplete="off">
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Full Name</label>
                <input type="text" name="name" id="authName" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Email Address</label>
                <input type="email" name="email" id="authEmail" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" required>
            </div>

            <div class="mb-4" id="passwordField">
                <label class="block text-gray-700 font-semibold mb-2">Password</label>
                <input type="password" name="password" id="authPassword" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" required>
            </div>

            <div class="mb-4" id="confirmPasswordField">
                <label class="block text-gray-700 font-semibold mb-2">Confirm Password</label>
                <input type="password" name="confirm_password" id="authConfirmPassword" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500">
            </div>

            <div class="mb-4 hidden" id="verificationField">
                <label class="block text-gray-700 font-semibold mb-2">Verification Code</label>
                <input type="text" name="verification_code" id="verificationCode" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" maxlength="6">
                <p class="text-xs text-gray-500 mt-2">Check your spam folder for the verification code.</p>
            </div>

            <div class="mb-4 text-center">
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg font-semibold w-full" id="authSubmitBtn">Continue</button>
            </div>
            <div class="text-center text-sm text-gray-500" id="authError"></div>
            <div class="text-center text-sm mt-2">
                <a href="#" id="switchToRegister" class="text-purple-600 hover:underline">Register</a> |
                <a href="#" id="switchToLogin" class="text-purple-600 hover:underline">Login</a>
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
        if (authError) authError.textContent = '';
        document.getElementById('verificationField').classList.add('hidden');
        document.getElementById('passwordField').classList.remove('hidden');
        document.getElementById('confirmPasswordField').classList.add('hidden');
        document.getElementById('authName').closest('div').classList.add('hidden');
        document.getElementById('authSubmitBtn').textContent = (mode === 'register') ? 'Register' : (mode === 'verify' ? 'Verify' : 'Login');
        if (mode === 'verify') {
            document.getElementById('verificationField').classList.remove('hidden');
            document.getElementById('passwordField').classList.add('hidden');
            document.getElementById('authName').closest('div').classList.add('hidden');
        }
        if (mode === 'register') {
            document.getElementById('confirmPasswordField').classList.remove('hidden');
            document.getElementById('authName').closest('div').classList.remove('hidden');
        }
    }
    setAuthMode('login');

    document.getElementById('switchToRegister').addEventListener('click', function(e){ e.preventDefault(); setAuthMode('register'); });
    document.getElementById('switchToLogin').addEventListener('click', function(e){ e.preventDefault(); setAuthMode('login'); });

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
                    authError.textContent = 'Verification code sent to ' + email + '. Check spam folder.';
                    setAuthMode('verify');
                    return;
                }
                // Success in verify or login
                closeAuthModal();
                // After login/verify, refresh the CSRF token and resubmit the form
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
})();
</script>
