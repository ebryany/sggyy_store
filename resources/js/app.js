import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// ===== ENHANCED BUTTON INTERACTIONS =====
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎨 Enhanced button interactions loaded');
    
    // 1. Auto-add loading state to form submissions (prevent double-click)
    // EXCEPT for chat forms - they must submit normally (not AJAX)
    const forms = document.querySelectorAll('form:not([data-no-ajax="true"]):not([data-no-intercept="true"])');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Skip if this is a chat form or has no-intercept flag (must submit normally)
            if (form.id === 'messageForm' || form.hasAttribute('data-no-ajax') || form.hasAttribute('data-no-intercept')) {
                return; // Let form submit normally - DO NOT interfere
            }
            
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled && !submitBtn.classList.contains('btn-loading')) {
                // Add loading class
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
                
                // Store original text
                const originalText = submitBtn.innerHTML;
                submitBtn.setAttribute('data-original-text', originalText);
                
                // Show loading state (text becomes invisible, spinner shows)
                submitBtn.innerHTML = '<span style="opacity: 0;">' + originalText + '</span>';
                
                console.log('✅ Form submitting, button disabled');
                
                // Fallback: Re-enable after 5 seconds (in case form submission fails silently)
                setTimeout(() => {
                    if (submitBtn.classList.contains('btn-loading')) {
                        submitBtn.classList.remove('btn-loading');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                        console.log('⏰ Form timeout - button re-enabled');
                    }
                }, 5000);
            }
        });
    });
    
    // 2. Add click feedback sound (optional - can be enabled)
    // Uncomment below to enable click sound
    /*
    document.addEventListener('click', function(e) {
        if (e.target.matches('button, .btn, [role="button"]')) {
            // Play subtle click sound
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTGH0fPTgjMGHm7A7+OZRQ0PWqvn77BdGAg+ltryxnMnBSl+y/DbizUHGmS37OihUBELTKXh8bllHAU2jtL0z3wvBSF2xfHaiTgIGGm87OWhUBELTKXh8bllHAU2jtL0z3wvBSF2xfHaiTgIGGm87OWhUBELTKXh8bllHAU2jtL0z3wvBSF2xfHaiTgIGGm87OWhUBELTKXh8bllHAU2jtL0z3wvBSF2xfHaiTgIGGm87OWhUBELTKXh8bllHAU2jtL0z3wvBSF2xfHaiTgIGGm87OWhUBELTKXh8bllHAU2jtL0z3wvBSF2xfHaiQ==');
            audio.volume = 0.1;
            audio.play().catch(() => {});
        }
    });
    */
    
    // 3. Visual feedback for all clickable elements
    const clickables = document.querySelectorAll('button, .btn, [role="button"], a[href]');
    clickables.forEach(el => {
        // Ensure cursor is pointer
        if (!el.style.cursor) {
            el.style.cursor = 'pointer';
        }
    });
    
    console.log(`✅ Enhanced ${forms.length} forms and ${clickables.length} clickable elements`);
});
