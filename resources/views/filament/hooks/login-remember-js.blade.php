<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkInterval = setInterval(() => {
            const emailInput = document.getElementById('data.email');
            const passwordInput = document.getElementById('data.password');
            const rememberCheckbox = document.getElementById('data.remember');
            
            if (emailInput && passwordInput && rememberCheckbox) {
                clearInterval(checkInterval);
                initRememberLogin(emailInput, passwordInput, rememberCheckbox);
            }
        }, 100);

        function initRememberLogin(emailInput, passwordInput, rememberCheckbox) {
            // 1. Thay đổi nhãn "Ghi nhớ đăng nhập" -> "Ghi nhớ mật khẩu"
            const labelSpan = rememberCheckbox.closest('label')?.querySelector('span') || 
                              document.querySelector('label[for="data.remember"] span');
            if (labelSpan) {
                labelSpan.textContent = 'Ghi nhớ mật khẩu';
            }

            // 2. Kiểm tra xem có tài khoản đã lưu từ trước không
            const lastUserEmail = localStorage.getItem('nks_last_user_email');
            const lastUserName = localStorage.getItem('nks_last_user_name');
            const lastUserAvatar = localStorage.getItem('nks_last_user_avatar');
            
            // Lấy mật khẩu đã lưu nếu có (nếu người dùng tích chọn ghi nhớ mật khẩu trước đó)
            const savedPassword = localStorage.getItem('nks_saved_password');
            const isRemembered = localStorage.getItem('nks_remember_password') === 'true';

            // Tìm container của email input
            const emailWrapper = emailInput.closest('.fi-fo-field-wrp');

            if (lastUserEmail && lastUserName && emailWrapper) {
                // Điền email vào input để Livewire/Laravel nhận diện khi submit
                emailInput.value = lastUserEmail;
                emailInput.dispatchEvent(new Event('input', { bubbles: true }));

                if (isRemembered && savedPassword) {
                    passwordInput.value = savedPassword;
                    passwordInput.dispatchEvent(new Event('input', { bubbles: true }));
                    rememberCheckbox.checked = true;
                    rememberCheckbox.dispatchEvent(new Event('change', { bubbles: true }));
                }

                // Ẩn trường nhập email
                emailWrapper.style.display = 'none';

                // Xác định câu chào theo thời gian
                const hour = new Date().getHours();
                let greeting = 'Chào buổi sáng';
                if (hour >= 12 && hour < 14) greeting = 'Chào buổi trưa';
                else if (hour >= 14 && hour < 18) greeting = 'Chào buổi chiều';
                else if (hour >= 18 || hour < 5) greeting = 'Chào buổi tối';

                // Tạo giao diện Profile chào mừng
                const profileHtml = `
                    <div id="nks-login-profile-box" style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; margin-bottom: 1.25rem; box-sizing: border-box;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <img src="${lastUserAvatar || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&h=150&q=80'}" 
                                 style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0;" 
                                 onerror="this.src='https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&h=150&q=80'" />
                            <div style="display: flex; flex-direction: column; line-height: 1.2; text-align: left;">
                                <span style="font-size: 0.75rem; color: #64748b; font-weight: 500;">${greeting}</span>
                                <span style="font-size: 0.95rem; font-weight: 700; color: #0284c7;">${lastUserName}</span>
                            </div>
                        </div>
                        <button type="button" id="nks-switch-account-btn" title="Chuyển tài khoản" style="background: none; border: none; padding: 0.5rem; color: #64748b; cursor: pointer; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; outline: none; transition: color 0.2s;" onmouseover="this.style.color='#0284c7'" onmouseout="this.style.color='#64748b'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 18px; height: 18px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                            </svg>
                        </button>
                    </div>
                `;

                // Chèn Box Profile vào trước ô mật khẩu
                emailWrapper.insertAdjacentHTML('afterend', profileHtml);

                // Lắng nghe sự kiện click nút chuyển tài khoản
                document.getElementById('nks-switch-account-btn').addEventListener('click', function () {
                    // Hiển thị lại ô nhập email
                    emailWrapper.style.display = 'block';
                    
                    // Xóa thông tin đã điền sẵn
                    emailInput.value = '';
                    emailInput.dispatchEvent(new Event('input', { bubbles: true }));
                    passwordInput.value = '';
                    passwordInput.dispatchEvent(new Event('input', { bubbles: true }));
                    
                    // Xóa hộp Profile
                    document.getElementById('nks-login-profile-box').remove();

                    // Xóa dữ liệu cũ khỏi localStorage để tránh tự động hiện lại
                    localStorage.removeItem('nks_last_user_email');
                    localStorage.removeItem('nks_last_user_name');
                    localStorage.removeItem('nks_last_user_avatar');
                    localStorage.removeItem('nks_saved_password');
                    localStorage.removeItem('nks_remember_password');
                });
            }

            // 3. Lắng nghe sự kiện submit của form để lưu thông tin nếu người dùng chọn ghi nhớ
            const form = emailInput.closest('form');
            if (form) {
                form.addEventListener('submit', function () {
                    if (rememberCheckbox.checked) {
                        localStorage.setItem('nks_remember_password', 'true');
                        localStorage.setItem('nks_saved_password', passwordInput.value);
                        // Lưu email hiện tại để nhớ người dùng
                        localStorage.setItem('nks_last_user_email', emailInput.value);
                    } else {
                        localStorage.removeItem('nks_remember_password');
                        localStorage.removeItem('nks_saved_password');
                    }
                });
            }
        }
    });
</script>
