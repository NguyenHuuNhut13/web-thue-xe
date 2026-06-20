<script>
(function() {
    console.log('NKS Login Remember hook script loaded.');

    function initRememberLogin() {
        console.log('NKS initRememberLogin running...');
        const emailInput = document.getElementById('form.email') || document.getElementById('data.email') || document.getElementById('email');
        const passwordInput = document.getElementById('form.password') || document.getElementById('data.password') || document.getElementById('password');
        const rememberCheckbox = document.getElementById('form.remember') || document.getElementById('data.remember') || document.getElementById('remember');

        if (!emailInput || !passwordInput || !rememberCheckbox) {
            console.log('NKS fields not found yet, retrying...');
            return false;
        }

        console.log('NKS fields found!');

        // 1. Thay đổi nhãn "Remember me" / "Ghi nhớ đăng nhập" -> "Ghi nhớ mật khẩu"
        const labelSpan = rememberCheckbox.closest('label')?.querySelector('span') || 
                          document.querySelector('label[for="' + rememberCheckbox.id + '"] span') ||
                          document.querySelector('label[for="form.remember"] span') ||
                          document.querySelector('label[for="data.remember"] span') ||
                          document.querySelector('label[for="remember"] span');
        if (labelSpan) {
            labelSpan.textContent = 'Ghi nhớ mật khẩu';
            console.log('NKS label text changed to "Ghi nhớ mật khẩu"');
        }

        // 2. Kiểm tra xem có tài khoản đã lưu từ trước không
        const lastUserEmail = localStorage.getItem('nks_last_user_email');
        const lastUserName = localStorage.getItem('nks_last_user_name');
        const lastUserAvatar = localStorage.getItem('nks_last_user_avatar');
        
        const savedPassword = localStorage.getItem('nks_saved_password');
        const isRemembered = localStorage.getItem('nks_remember_password') === 'true';

        const emailWrapper = emailInput.closest('.fi-fo-field-wrp') || emailInput.parentElement;

        if (lastUserEmail && lastUserName && emailWrapper) {
            console.log('NKS Found last user:', lastUserEmail);
            // Điền email vào input để Livewire/Laravel nhận diện khi submit
            emailInput.value = lastUserEmail;
            emailInput.dispatchEvent(new Event('input', { bubbles: true }));
            emailInput.dispatchEvent(new Event('change', { bubbles: true }));

            if (isRemembered && savedPassword) {
                passwordInput.value = savedPassword;
                passwordInput.dispatchEvent(new Event('input', { bubbles: true }));
                passwordInput.dispatchEvent(new Event('change', { bubbles: true }));
                rememberCheckbox.checked = true;
                rememberCheckbox.dispatchEvent(new Event('change', { bubbles: true }));
            }

            // Ẩn trường nhập email
            emailWrapper.style.display = 'none';

            // Tránh tạo trùng hộp Profile
            let profileWrapper = document.getElementById('nks-login-profile-wrapper');
            if (!profileWrapper) {
                profileWrapper = document.createElement('div');
                profileWrapper.id = 'nks-login-profile-wrapper';
                profileWrapper.style.position = 'relative';
                profileWrapper.style.width = '100%';

                // Xác định câu chào theo thời gian
                const hour = new Date().getHours();
                let greeting = 'Chào buổi sáng';
                if (hour >= 12 && hour < 14) greeting = 'Chào buổi trưa';
                else if (hour >= 14 && hour < 18) greeting = 'Chào buổi chiều';
                else if (hour >= 18 || hour < 5) greeting = 'Chào buổi tối';

                // Tạo giao diện Profile chào mừng
                const profileHtml = `
                    <div id="nks-login-profile-box" style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; margin-bottom: 1.25rem; box-sizing: border-box; width: 100%;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <img id="nks-profile-avatar" src="${lastUserAvatar || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&h=150&q=80'}" 
                                 style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0;" 
                                 onerror="this.src='https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&h=150&q=80'" />
                            <div style="display: flex; flex-direction: column; line-height: 1.2; text-align: left;">
                                <span style="font-size: 0.75rem; color: #64748b; font-weight: 500;">${greeting}</span>
                                <span id="nks-profile-name" style="font-size: 0.95rem; font-weight: 700; color: #0284c7;">${lastUserName}</span>
                            </div>
                        </div>
                        <button type="button" id="nks-switch-account-btn" title="Chuyển tài khoản" style="background: none; border: none; padding: 0.5rem; color: #64748b; cursor: pointer; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; outline: none; transition: color 0.2s;" onmouseover="this.style.color='#0284c7'" onmouseout="this.style.color='#64748b'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 18px; height: 18px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                            </svg>
                        </button>
                    </div>
                `;
                profileWrapper.innerHTML = profileHtml;
                emailWrapper.insertAdjacentElement('afterend', profileWrapper);

                // Lắng nghe sự kiện click nút chuyển tài khoản
                document.getElementById('nks-switch-account-btn').addEventListener('click', function (e) {
                    e.stopPropagation();
                    toggleAccountDropdown(profileWrapper);
                });
            }
        }

        // Hộp thoại chuyển đổi tài khoản (Dropdown)
        function toggleAccountDropdown(parent) {
            let dropdown = document.getElementById('nks-account-dropdown');
            if (dropdown) {
                dropdown.remove();
                return;
            }

            let accounts = [];
            try {
                accounts = JSON.parse(localStorage.getItem('nks_saved_accounts')) || [];
            } catch(e) { accounts = []; }
            if (!Array.isArray(accounts)) accounts = [];

            // Nếu rỗng nhưng đã có user trước đó, tạo 1 entry tạm từ các khoá cũ
            if (accounts.length === 0 && lastUserEmail) {
                accounts.push({
                    email: lastUserEmail,
                    name: lastUserName,
                    avatar: lastUserAvatar,
                    password: savedPassword,
                    remember: isRemembered
                });
            }

            dropdown = document.createElement('div');
            dropdown.id = 'nks-account-dropdown';
            dropdown.style.position = 'absolute';
            dropdown.style.top = '100%';
            dropdown.style.right = '0';
            dropdown.style.left = '0';
            dropdown.style.backgroundColor = 'white';
            dropdown.style.border = '1px solid #e2e8f0';
            dropdown.style.borderRadius = '1rem';
            dropdown.style.boxShadow = '0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05)';
            dropdown.style.marginTop = '-1rem';
            dropdown.style.zIndex = '100';
            dropdown.style.padding = '0.5rem 0';
            dropdown.style.maxHeight = '250px';
            dropdown.style.overflowY = 'auto';
            dropdown.style.boxSizing = 'border-box';

            let dropdownHtml = '';
            accounts.forEach(acc => {
                const isCurrent = acc.email === localStorage.getItem('nks_last_user_email');
                dropdownHtml += `
                    <div class="nks-account-item" data-email="${acc.email}" style="display: flex; align-items: center; justify-content: space-between; padding: 0.6rem 1rem; cursor: pointer; transition: background-color 0.2s; background-color: ${isCurrent ? '#f0f9ff' : 'transparent'};" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='${isCurrent ? '#f0f9ff' : 'transparent'}'">
                        <div style="display: flex; align-items: center; gap: 0.75rem; flex: 1;">
                            <img src="${acc.avatar || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&h=150&q=80'}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&h=150&q=80'" />
                            <div style="display: flex; flex-direction: column; text-align: left; line-height: 1.2;">
                                <span style="font-size: 0.85rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 0.25rem;">
                                    ${acc.name}
                                    ${isCurrent ? '<span style="font-size: 0.65rem; background-color: #0ea5e9; color: white; padding: 0.05rem 0.25rem; border-radius: 0.25rem; font-weight: 500;">Hiện tại</span>' : ''}
                                </span>
                                <span style="font-size: 0.75rem; color: #64748b;">${acc.email}</span>
                            </div>
                        </div>
                        <button type="button" class="nks-remove-account-btn" data-email="${acc.email}" title="Xóa tài khoản đã lưu" style="background: none; border: none; padding: 0.25rem 0.5rem; color: #94a3b8; cursor: pointer; border-radius: 0.25rem; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; line-height: 1; z-index: 10;" onmouseover="this.style.color='#f43f5e'; event.stopPropagation();" onmouseout="this.style.color='#94a3b8'; event.stopPropagation();">
                            &times;
                        </button>
                    </div>
                `;
            });

            dropdownHtml += `
                <div id="nks-add-account-option" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-top: 1px solid #f1f5f9; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                    <div style="width: 28px; height: 28px; border-radius: 50%; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #64748b;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <span style="font-size: 0.85rem; font-weight: 600; color: #64748b;">Sử dụng tài khoản khác</span>
                </div>
            `;

            dropdown.innerHTML = dropdownHtml;
            parent.appendChild(dropdown);

            // Bắt sự kiện click chọn tài khoản
            dropdown.querySelectorAll('.nks-account-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    const email = this.getAttribute('data-email');
                    selectAccount(email);
                    dropdown.remove();
                });
            });

            // Bắt sự kiện xóa tài khoản đã lưu
            dropdown.querySelectorAll('.nks-remove-account-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const email = this.getAttribute('data-email');
                    removeAccount(email);
                });
            });

            // Bắt sự kiện click "Sử dụng tài khoản khác"
            document.getElementById('nks-add-account-option').addEventListener('click', function(e) {
                e.stopPropagation();
                useOtherAccount();
                dropdown.remove();
            });

            // Đóng dropdown khi bấm ra ngoài
            const clickOutsideHandler = function(e) {
                if (!dropdown.contains(e.target) && e.target !== document.getElementById('nks-switch-account-btn')) {
                    dropdown.remove();
                    document.removeEventListener('click', clickOutsideHandler);
                }
            };
            document.addEventListener('click', clickOutsideHandler);
        }

        // Chọn tài khoản để đăng nhập
        function selectAccount(email) {
            console.log('NKS Selecting account:', email);
            let accounts = [];
            try {
                accounts = JSON.parse(localStorage.getItem('nks_saved_accounts')) || [];
            } catch(e) { accounts = []; }
            if (!Array.isArray(accounts)) accounts = [];

            const acc = accounts.find(a => a.email === email);
            if (acc) {
                localStorage.setItem('nks_last_user_email', acc.email);
                localStorage.setItem('nks_last_user_name', acc.name);
                localStorage.setItem('nks_last_user_avatar', acc.avatar);
                if (acc.remember && acc.password) {
                    localStorage.setItem('nks_remember_password', 'true');
                    localStorage.setItem('nks_saved_password', acc.password);
                } else {
                    localStorage.removeItem('nks_remember_password');
                    localStorage.removeItem('nks_saved_password');
                }

                emailInput.value = acc.email;
                emailInput.dispatchEvent(new Event('input', { bubbles: true }));
                emailInput.dispatchEvent(new Event('change', { bubbles: true }));

                if (acc.remember && acc.password) {
                    passwordInput.value = acc.password;
                    passwordInput.dispatchEvent(new Event('input', { bubbles: true }));
                    passwordInput.dispatchEvent(new Event('change', { bubbles: true }));
                    rememberCheckbox.checked = true;
                    rememberCheckbox.dispatchEvent(new Event('change', { bubbles: true }));
                } else {
                    passwordInput.value = '';
                    passwordInput.dispatchEvent(new Event('input', { bubbles: true }));
                    passwordInput.dispatchEvent(new Event('change', { bubbles: true }));
                    rememberCheckbox.checked = false;
                    rememberCheckbox.dispatchEvent(new Event('change', { bubbles: true }));
                }

                const avatarImg = document.getElementById('nks-profile-avatar');
                const nameSpan = document.getElementById('nks-profile-name');
                if (avatarImg) avatarImg.src = acc.avatar || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&h=150&q=80';
                if (nameSpan) nameSpan.textContent = acc.name;
            }
        }

        // Xóa tài khoản khỏi danh sách đã lưu
        function removeAccount(email) {
            console.log('NKS Removing account:', email);
            let accounts = [];
            try {
                accounts = JSON.parse(localStorage.getItem('nks_saved_accounts')) || [];
            } catch(e) { accounts = []; }
            if (!Array.isArray(accounts)) accounts = [];

            accounts = accounts.filter(acc => acc.email !== email);
            localStorage.setItem('nks_saved_accounts', JSON.stringify(accounts));

            const lastEmail = localStorage.getItem('nks_last_user_email');
            if (lastEmail === email) {
                if (accounts.length > 0) {
                    selectAccount(accounts[0].email);
                    const dropdown = document.getElementById('nks-account-dropdown');
                    if (dropdown && dropdown.parentElement) {
                        const parent = dropdown.parentElement;
                        dropdown.remove();
                        toggleAccountDropdown(parent);
                    }
                } else {
                    useOtherAccount();
                }
            } else {
                const dropdown = document.getElementById('nks-account-dropdown');
                if (dropdown && dropdown.parentElement) {
                    const parent = dropdown.parentElement;
                    dropdown.remove();
                    toggleAccountDropdown(parent);
                }
            }
        }

        // Sử dụng một tài khoản khác hoàn toàn mới
        function useOtherAccount() {
            console.log('NKS useOtherAccount clicked');
            emailWrapper.style.display = 'block';

            emailInput.value = '';
            emailInput.dispatchEvent(new Event('input', { bubbles: true }));
            emailInput.dispatchEvent(new Event('change', { bubbles: true }));
            passwordInput.value = '';
            passwordInput.dispatchEvent(new Event('input', { bubbles: true }));
            passwordInput.dispatchEvent(new Event('change', { bubbles: true }));
            rememberCheckbox.checked = false;
            rememberCheckbox.dispatchEvent(new Event('change', { bubbles: true }));

            const wrapper = document.getElementById('nks-login-profile-wrapper');
            if (wrapper) wrapper.remove();

            localStorage.removeItem('nks_last_user_email');
            localStorage.removeItem('nks_last_user_name');
            localStorage.removeItem('nks_last_user_avatar');
            localStorage.removeItem('nks_saved_password');
            localStorage.removeItem('nks_remember_password');
        }

        // 3. Hàm lưu thông tin đăng nhập khi submit
        function saveCredentials() {
            console.log('NKS Saving credentials...');
            const email = emailInput.value;
            const password = passwordInput.value;
            const remember = rememberCheckbox.checked;

            if (!email) return;

            let accounts = [];
            try {
                accounts = JSON.parse(localStorage.getItem('nks_saved_accounts')) || [];
            } catch(e) { accounts = []; }
            if (!Array.isArray(accounts)) accounts = [];

            let existIndex = accounts.findIndex(acc => acc.email === email);
            
            if (remember) {
                if (existIndex > -1) {
                    accounts[existIndex].password = password;
                    accounts[existIndex].remember = true;
                } else {
                    accounts.push({
                        email: email,
                        name: email.split('@')[0],
                        avatar: '',
                        password: password,
                        remember: true
                    });
                }
                localStorage.setItem('nks_remember_password', 'true');
                localStorage.setItem('nks_saved_password', password);
                localStorage.setItem('nks_last_user_email', email);
            } else {
                if (existIndex > -1) {
                    accounts[existIndex].password = '';
                    accounts[existIndex].remember = false;
                }
                localStorage.removeItem('nks_remember_password');
                localStorage.removeItem('nks_saved_password');
            }
            
            localStorage.setItem('nks_saved_accounts', JSON.stringify(accounts));
        }

        // Lắng nghe sự kiện thay đổi của các trường và submit
        const form = emailInput.closest('form');
        if (form) {
            form.removeEventListener('submit', saveCredentials);
            form.addEventListener('submit', saveCredentials);

            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.removeEventListener('click', saveCredentials);
                submitBtn.addEventListener('click', saveCredentials);
            }
        }

        rememberCheckbox.removeEventListener('change', saveCredentials);
        rememberCheckbox.addEventListener('change', saveCredentials);

        return true;
    }

    // Loop to find elements (necessary for dynamically loaded forms or Livewire re-renders)
    let searchInterval = null;
    function startSearch() {
        if (searchInterval) clearInterval(searchInterval);
        
        let attempts = 0;
        searchInterval = setInterval(() => {
            attempts++;
            const success = initRememberLogin();
            if (success || attempts > 30) {
                clearInterval(searchInterval);
            }
        }, 100);
    }

    // Run when ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startSearch);
    } else {
        startSearch();
    }

    // Livewire v3 events compatibility
    document.addEventListener('livewire:navigated', startSearch);
    document.addEventListener('livewire:load', startSearch);
})();
</script>
