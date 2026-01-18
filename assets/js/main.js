/**
 * zzprompts Theme - Main JavaScript
 * Copy & Like System with LocalStorage tracking
 * 
 * @package zzprompts
 * @version 1.0.0
 */

(function () {
    'use strict';

    // Track in-progress requests to prevent rapid clicks
    const requestsInProgress = {
        copy: {},
        like: {}
    };

    /**
     * Mobile Menu Toggle - Modern V1 (BEM Selectors)
     */
    function initMobileMenu() {
        // Modern V1 BEM selectors
        const menuToggle = document.getElementById('zz-mobile-toggle');
        const mobileMenu = document.getElementById('zz-mobile-menu');
        const closeBtn = document.getElementById('zz-mobile-close');

        // Legacy fallback selectors
        const legacyToggle = document.querySelector('.menu-toggle');
        const legacyNav = document.querySelector('.main-navigation');

        // Modern V1 Mobile Menu
        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', function () {
                const expanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !expanded);
                mobileMenu.classList.toggle('is-open');
                mobileMenu.setAttribute('aria-hidden', expanded);
                document.body.classList.toggle('menu-open');
            });

            if (closeBtn) {
                closeBtn.addEventListener('click', function () {
                    mobileMenu.classList.remove('is-open');
                    mobileMenu.setAttribute('aria-hidden', 'true');
                    menuToggle.setAttribute('aria-expanded', 'false');
                    document.body.classList.remove('menu-open');
                    menuToggle.focus();
                });
            }

            // Close on ESC
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && mobileMenu.classList.contains('is-open')) {
                    mobileMenu.classList.remove('is-open');
                    mobileMenu.setAttribute('aria-hidden', 'true');
                    menuToggle.setAttribute('aria-expanded', 'false');
                    document.body.classList.remove('menu-open');
                    menuToggle.focus();
                }
            });

            // Close when clicking outside
            document.addEventListener('click', function (e) {
                if (mobileMenu.classList.contains('is-open') &&
                    !mobileMenu.contains(e.target) &&
                    !menuToggle.contains(e.target)) {
                    mobileMenu.classList.remove('is-open');
                    mobileMenu.setAttribute('aria-hidden', 'true');
                    menuToggle.setAttribute('aria-expanded', 'false');
                    document.body.classList.remove('menu-open');
                }
            });

            return; // Modern V1 is active, skip legacy
        }

        // Legacy menu (Fallback for old templates)
        if (!legacyToggle || !legacyNav) return;

        legacyToggle.addEventListener('click', function () {
            const expanded = this.getAttribute('aria-expanded') === 'true';

            this.setAttribute('aria-expanded', !expanded);
            legacyNav.classList.toggle('active');
            document.body.classList.toggle('menu-open');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function (e) {
            if (!legacyNav.contains(e.target) && !legacyToggle.contains(e.target)) {
                legacyNav.classList.remove('active');
                legacyToggle.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('menu-open');
            }
        });

        // Close menu on ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && legacyNav.classList.contains('active')) {
                legacyNav.classList.remove('active');
                legacyToggle.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('menu-open');
                legacyToggle.focus();
            }
        });
    }

    /**
     * Show Toast Notification
     */
    function showToast(message, type = 'success') {
        const toast = document.getElementById('zz-toast');
        if (!toast) return;

        const toastMessage = toast.querySelector('.toast-message');
        if (toastMessage) {
            toastMessage.textContent = message;
        }

        toast.className = 'zz-toast zz-toast-' + type + ' zz-toast-show';

        setTimeout(function () {
            toast.classList.remove('zz-toast-show');
        }, 3000);
    }

    /**
     * Initialize Toast Container
     */
    function initToast() {
        if (!document.getElementById('zz-toast')) {
            const toastDiv = document.createElement('div');
            toastDiv.id = 'zz-toast';
            toastDiv.className = 'zz-toast';
            toastDiv.innerHTML = '<span class="toast-message"></span>';
            document.body.appendChild(toastDiv);
        }
    }

    /**
     * Copy Prompt to Clipboard - Event Delegation Approach (Grid & Single Support)
     */
    /**
     * Copy Prompt to Clipboard - Event Delegation Approach (Grid & Single & V2 Support)
     */
    function initCopySystem() {
        // Use event delegation on document for dynamic buttons
        document.addEventListener('click', function (e) {
            // Single Prompt: Expand/collapse prompt box
            const promptExpandBtn = e.target.closest('.zz-prompt-expand-btn');
            if (promptExpandBtn) {
                e.preventDefault();
                e.stopPropagation();

                const promptBox = promptExpandBtn.closest('.zz-prompt-box');
                if (!promptBox) return;

                const isExpanded = promptBox.classList.toggle('is-expanded');
                promptExpandBtn.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');

                // Update accessible label + tooltip
                const sr = promptExpandBtn.querySelector('.zz-expand-sr');
                const expandLabel = 'Expand prompt';
                const collapseLabel = 'Collapse prompt';
                const label = isExpanded ? collapseLabel : expandLabel;
                if (sr) sr.textContent = label;
                promptExpandBtn.setAttribute('title', label);

                // If we just expanded and the wrapper is scrollable, keep focus near the top
                const wrapperId = promptExpandBtn.getAttribute('aria-controls');
                if (wrapperId) {
                    const wrapper = document.getElementById(wrapperId);
                    if (wrapper && isExpanded) {
                        wrapper.scrollTop = 0;
                    }
                }
                return;
            }

            // Check if clicked element is copy button or inside it
            // Supports old legacy, v2, and new Modern V1 (.zz-btn-copy) buttons
            const copyBtn = e.target.closest('.zz-copy-btn, .zz-btn-copy, .zz-copy-btn-large, .zz-copy-btn-sticky');

            if (!copyBtn) return;

            // FIX: If it's a link (like on homepage), let it redirect, don't copy here
            if (copyBtn.tagName.toLowerCase() === 'a' && copyBtn.hasAttribute('href')) {
                return;
            }

            e.preventDefault();
            e.stopPropagation();

            const postId = copyBtn.getAttribute('data-post-id') || copyBtn.getAttribute('data-prompt-id') || copyBtn.getAttribute('data-id');

            // Check if already in progress
            if (postId && requestsInProgress.copy[postId]) {
                return;
            }

            let textToCopy = '';

            // 1. Attribute Strategy (V2 uses data-prompt-text)
            if (copyBtn.getAttribute('data-prompt-text')) {
                textToCopy = copyBtn.getAttribute('data-prompt-text');
            }
            else if (copyBtn.getAttribute('data-clipboard-text')) {
                textToCopy = copyBtn.getAttribute('data-clipboard-text');
            }
            // 2. Element Target Strategy
            else {
                let promptBox;
                const targetId = copyBtn.getAttribute('data-clipboard-target') || copyBtn.getAttribute('data-target');
                if (targetId) promptBox = document.querySelector(targetId);
                else promptBox = document.getElementById('zz-prompt-raw');

                if (promptBox) textToCopy = promptBox.innerText || promptBox.textContent || '';
            }

            // ZZ Fallback: If no explicit text, try to find the card text
            if (!textToCopy) {
                const card = copyBtn.closest('.zz-library-card');
                if (card) {
                    const cardText = card.querySelector('.zz-library-card-text');
                    if (cardText) textToCopy = cardText.innerText.trim();
                }
            }

            if (!textToCopy || !textToCopy.trim()) {
                console.warn('ZZPrompts: No text found to copy for button', copyBtn);
                showToast('Error: No prompt text found!', 'error');
                return;
            }

            // Target the Text Span inside button (if exists)
            const btnTextSpan = copyBtn.querySelector('.btn-text');

            // 3. Copy to Clipboard
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(textToCopy)
                    .then(function () {
                        handleCopySuccess(copyBtn, btnTextSpan, postId);
                    })
                    .catch(function (err) {
                        fallbackCopy(textToCopy, copyBtn, btnTextSpan, postId);
                    });
            } else {
                fallbackCopy(textToCopy, copyBtn, btnTextSpan, postId);
            }
        });
    }

    /**
     * Long Prompt Expand/Collapse Logic (Professional Fade & Expand Pattern)
     */
    function initPromptExpandSystem() {
        // Only for modern layout (v2)
        const wrappers = document.querySelectorAll('body.style-v2 .zz-prompt-restrict');

        wrappers.forEach(function (wrapper) {
            const overlay = wrapper.querySelector('.prompt-fade-overlay');
            const btn = wrapper.parentElement.querySelector('.zz-expand-btn');
            if (!btn) return;

            // Show expand button if content is long, else hide
            if (wrapper.scrollHeight > 300) {
                btn.classList.remove('hidden');
            } else {
                if (overlay) overlay.style.display = 'none';
                btn.classList.add('hidden');
                wrapper.classList.remove('zz-prompt-restrict');
            }

            btn.addEventListener('click', function (e) {
                e.preventDefault();
                wrapper.classList.toggle('is-expanded');
                this.classList.toggle('active');
            });
        });
    }

    /**
     * Classic Single Prompt Expand/Collapse Logic
     */
    function initClassicPromptExpand() {
        const wrappers = document.querySelectorAll('.csp-prompt-restrict');

        wrappers.forEach(function (wrapper) {
            const preBlock = wrapper.querySelector('pre');
            const overlay = wrapper.querySelector('.csp-prompt-fade');
            const btn = wrapper.parentElement.querySelector('.csp-expand-btn');

            if (!preBlock || !btn) return;

            const contentHeight = preBlock.scrollHeight;
            const limitHeight = 280;

            if (contentHeight > limitHeight + 20) {
                // Long prompt - show expand button
                btn.classList.remove('csp-expand-hidden');
                wrapper.style.maxHeight = limitHeight + 'px';
            } else {
                // Short prompt - hide overlay and button
                if (overlay) overlay.style.display = 'none';
                btn.classList.add('csp-expand-hidden');
                wrapper.classList.remove('csp-prompt-restrict');
                wrapper.style.maxHeight = 'none';
            }

            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const isExpanded = wrapper.classList.contains('csp-is-expanded');

                if (isExpanded) {
                    wrapper.classList.remove('csp-is-expanded');
                    wrapper.style.maxHeight = limitHeight + 'px';
                    this.classList.remove('csp-is-active');
                    wrapper.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    wrapper.classList.add('csp-is-expanded');
                    wrapper.style.maxHeight = contentHeight + 'px';
                    this.classList.add('csp-is-active');

                    setTimeout(function () {
                        if (wrapper.classList.contains('csp-is-expanded')) {
                            wrapper.style.maxHeight = 'none';
                        }
                    }, 400);
                }
            });
        });
    }

    /**
     * Handle successful copy (Grid & Single Support)
     */
    function handleCopySuccess(copyBtn, btnTextSpan, postId) {
        // Check if this is a grid card (icon-only button)
        const isGridCard = copyBtn.classList.contains('btn-icon-only');

        if (isGridCard) {
            // GRID CARD: Icon Swap Animation
            const originalHTML = copyBtn.innerHTML;

            // Add success class
            copyBtn.classList.add('copied-success');

            // Swap to Checkmark Icon
            copyBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>';

            // Show toast
            showToast('Copied to clipboard.', 'success');

            // Revert after 2 seconds
            setTimeout(function () {
                copyBtn.classList.remove('copied-success');
                copyBtn.innerHTML = originalHTML;
            }, 2000);

        } else {
            // SINGLE PAGE: Text Change
            copyBtn.classList.add('copied');

            // Check for .btn-text span (V2 improved layout)
            const btnTextElement = copyBtn.querySelector('.btn-text');
            if (btnTextElement) {
                if (!copyBtn.dataset.originalText) {
                    copyBtn.dataset.originalText = btnTextElement.textContent;
                }
                btnTextElement.textContent = window.zzprompts_vars ? window.zzprompts_vars.copy_success_text : 'Copied!';
            } else if (btnTextSpan) {
                btnTextSpan.textContent = window.zzprompts_vars ? window.zzprompts_vars.copy_success_text : 'Copied!';
            } else {
                // Fallback if no span, change text directly if it has text node
                const originalText = copyBtn.innerText;
                // Ideally store original text
                if (!copyBtn.dataset.originalText) copyBtn.dataset.originalText = originalText;

                // If it's a V2 single button (which might not use spans the same way)
                // But current V2 single html is: <button class="zz-copy-btn"> <svg>..</svg> <span>Text</span> </button>
                // The 'btnTextSpan' selector above might fail if class isn't .btn-text.
                // Let's safe check for V2 Single
                const labelSpan = copyBtn.querySelector('span');
                if (labelSpan) labelSpan.textContent = 'Copied!';
            }

            // Also update sticky button if exists (mobile)
            const stickyBtn = document.querySelector('.zz-copy-btn-sticky');
            if (stickyBtn && stickyBtn !== copyBtn && postId === stickyBtn.getAttribute('data-post-id')) {
                stickyBtn.classList.add('copied');
                const stickyBtnText = stickyBtn.querySelector('.btn-text');
                if (stickyBtnText) {
                    stickyBtnText.textContent = window.zzprompts_vars ? window.zzprompts_vars.copy_success_text : 'Copied!';
                }
            }

            // Show toast
            showToast(window.zzprompts_vars ? window.zzprompts_vars.copy_success_text : 'Copied to clipboard.', 'success');

            // Restore after 2 seconds
            setTimeout(function () {
                copyBtn.classList.remove('copied');

                // Restore .btn-text if exists
                const btnTextElement = copyBtn.querySelector('.btn-text');
                if (btnTextElement && copyBtn.dataset.originalText) {
                    btnTextElement.textContent = copyBtn.dataset.originalText;
                } else if (btnTextSpan) {
                    const originalText = copyBtn.getAttribute('data-original-text');
                    if (originalText) btnTextSpan.textContent = originalText;
                } else {
                    const labelSpan = copyBtn.querySelector('span');
                    if (labelSpan) labelSpan.textContent = copyBtn.dataset.originalText || 'Copy Prompt';
                }

                // Also restore sticky button if exists (mobile)
                const stickyBtn = document.querySelector('.zz-copy-btn-sticky');
                if (stickyBtn && stickyBtn !== copyBtn && postId === stickyBtn.getAttribute('data-post-id')) {
                    stickyBtn.classList.remove('copied');
                    const stickyBtnText = stickyBtn.querySelector('.btn-text');
                    if (stickyBtnText && stickyBtn.dataset.originalText) {
                        stickyBtnText.textContent = stickyBtn.dataset.originalText;
                    }
                }
            }, 2000);
        }

        // Track copy count
        if (postId) trackCopyCount(postId);
    }

    /**
     * Fallback copy method for older browsers
     */
    function fallbackCopy(text, copyBtn, btnTextSpan, postId) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            if (successful) {
                handleCopySuccess(copyBtn, btnTextSpan, postId);
            } else {
                showToast('Copy failed. Please try manually.', 'error');
            }
        } catch (err) {
            showToast('Copy not supported in this browser.', 'error');
        }

        document.body.removeChild(textArea);
    }

    /**
     * Track Copy Count via AJAX (once per session)
     */
    function trackCopyCount(postId) {
        // Check if already copied in this session
        const copiedKey = 'copied_prompt_' + postId;
        if (localStorage.getItem(copiedKey)) {
            return;
        }

        // Prevent duplicate requests
        if (requestsInProgress.copy[postId]) {
            return;
        }

        requestsInProgress.copy[postId] = true;

        // Send AJAX to track copy
        const formData = new FormData();
        formData.append('action', 'zzprompts_track_copy');
        formData.append('post_id', postId);
        formData.append('nonce', window.zzprompts_vars ? window.zzprompts_vars.nonce : '');

        fetch(window.zzprompts_vars ? window.zzprompts_vars.ajax_url : '/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.success) {
                    // Mark as copied in localStorage
                    localStorage.setItem(copiedKey, '1');

                    // Update copy count display (sync meta)
                    if (data.data && data.data.copies) {
                        const scoped = document.querySelectorAll('.copy-count[data-post-id="' + postId + '"]');
                        if (scoped && scoped.length) {
                            scoped.forEach(function (el) {
                                el.textContent = data.data.copies;
                            });
                        } else {
                            const copyCountElement = document.querySelector('.copy-count');
                            if (copyCountElement) copyCountElement.textContent = data.data.copies;
                        }
                    }
                }
            })
            .catch(function (error) {
                // Silent fail
            })
            .finally(function () {
                requestsInProgress.copy[postId] = false;
            });
    }

    /**
     * Like System
     */
    function initLikeSystem() {
        const likeButtons = document.querySelectorAll('.zz-like-btn');

        likeButtons.forEach(function (button) {
            const postId = button.getAttribute('data-post-id') || button.getAttribute('data-id');
            const likedKey = 'liked_prompt_' + postId;

            // Check if already liked
            if (localStorage.getItem(likedKey)) {
                button.classList.add('liked');
            }

            button.addEventListener('click', function (e) {
                e.preventDefault();
                handleLikeClick(this, postId);
            });
        });
    }

    /**
     * Handle Like Click
     */
    function handleLikeClick(button, postId) {
        const likedKey = 'liked_prompt_' + postId;

        // Check if already liked
        if (localStorage.getItem(likedKey)) {
            showToast(window.zzprompts_vars ? window.zzprompts_vars.already_liked_text : 'You already liked this!', 'info');
            return;
        }

        // Prevent duplicate requests
        if (requestsInProgress.like[postId]) {
            return;
        }

        requestsInProgress.like[postId] = true;

        // Optimistic UI update
        button.classList.add('liked', 'animating');

        // Send AJAX request
        const formData = new FormData();
        formData.append('action', 'zzprompts_like_prompt');
        formData.append('post_id', postId);
        formData.append('nonce', window.zzprompts_vars ? window.zzprompts_vars.nonce : '');

        fetch(window.zzprompts_vars ? window.zzprompts_vars.ajax_url : '/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.success) {
                    // Update main button like count
                    const likeCountElement = button.querySelector('.like-count');
                    if (likeCountElement && data.data.likes) {
                        likeCountElement.textContent = data.data.likes;
                    }

                    // UPDATE POPULAR PROMPTS WIDGET (Real-time Sync)
                    const popularItems = document.querySelectorAll('.zz-widget__popular-item[data-post-id="' + postId + '"]');
                    popularItems.forEach(item => {
                        const meta = item.querySelector('.zz-widget__popular-meta');
                        if (meta) {
                            // Extract tool name if it exists (Format: "12 Likes · ChatGPT")
                            const toolMatch = meta.textContent.match(/·\s*(.+)$/);
                            const toolStr = toolMatch ? ' · ' + toolMatch[1] : '';
                            meta.textContent = data.data.likes + ' ' + (window.zzprompts_vars ? window.zzprompts_vars.likes_text || 'Likes' : 'Likes') + toolStr;
                        }
                    });

                    // Save in localStorage
                    localStorage.setItem(likedKey, '1');

                    // Show success message
                    showToast(window.zzprompts_vars ? window.zzprompts_vars.like_success_text : 'Thank you for liking!', 'success');

                    // Remove animation class
                    setTimeout(function () {
                        button.classList.remove('animating');
                    }, 600);
                } else {
                    // Revert optimistic update on error
                    button.classList.remove('liked', 'animating');

                    // Show specific error from server (e.g., "Already liked")
                    const errorMsg = data.data && data.data.message ? data.data.message : 'Like failed. Please try again.';
                    showToast(errorMsg, 'error');
                }
            })
            .catch(function (error) {
                button.classList.remove('liked', 'animating');
                showToast('Network error. Please try again.', 'error');
            })
            .finally(function () {
                requestsInProgress.like[postId] = false;
            });
    }

    /**
     * Smooth Scroll for Anchor Links
     */
    function initSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');

        links.forEach(function (link) {
            link.addEventListener('click', function (e) {
                const href = this.getAttribute('href');

                if (href === '#') return;

                const target = document.querySelector(href);

                if (target) {
                    e.preventDefault();

                    const headerOffset = 80;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    /**
     * Featured Image Lightbox (Single Prompt)
     */
    function initFeaturedImageLightbox() {
        const trigger = document.querySelector('.zz-lightbox-trigger');
        const modal = document.getElementById('zz-lightbox');
        if (!trigger || !modal) return;

        const modalImage = modal.querySelector('.zz-lightbox-image');
        const closeBtn = modal.querySelector('.zz-lightbox-close');
        if (!modalImage || !closeBtn) return;

        let lastFocused = null;

        function open() {
            const fullSrc = trigger.getAttribute('data-full-src');
            if (!fullSrc) return;

            const thumbImg = trigger.querySelector('img');
            const alt = thumbImg ? (thumbImg.getAttribute('alt') || '') : '';

            lastFocused = document.activeElement;
            modalImage.setAttribute('src', fullSrc);
            modalImage.setAttribute('alt', alt);

            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('zz-lightbox-open');

            closeBtn.focus();
        }

        function close() {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('zz-lightbox-open');

            // Clear src to stop downloading on close
            modalImage.setAttribute('src', '');

            if (lastFocused && typeof lastFocused.focus === 'function') {
                lastFocused.focus();
            }
        }

        trigger.addEventListener('click', function (e) {
            e.preventDefault();
            open();
        });

        trigger.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                open();
            }
        });

        closeBtn.addEventListener('click', function (e) {
            e.preventDefault();
            close();
        });

        // Click outside image closes
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                close();
            }
        });

        // ESC closes
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) {
                close();
            }
        });
    }

    /**
     * Initialize all functions on DOM ready
     */
    /**
     * Instant AJAX Filter System (V2 Pill Buttons)
     * Triggers on checkbox change without page reload
     */
    /**
     * Instant AJAX Filter System (V2 Chips/Pills)
     * Triggers on checkbox change without page reload
     */
    function initInstantAjaxFilters() {
        const filterForm = document.getElementById('zz-filter-form');
        if (!filterForm) return;

        // Get all filter checkboxes
        // Support both old 'pill-checkbox' and new 'chip' classes
        const checkboxes = filterForm.querySelectorAll('input[type="checkbox"]');
        if (checkboxes.length === 0) return;

        // Handle checkbox changes
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                // Determine container class (chip)
                // Update active state
                const chipLabel = this.closest('.zz-chip');

                if (chipLabel) {
                    if (this.checked) {
                        chipLabel.classList.add('active');
                    } else {
                        chipLabel.classList.remove('active');
                    }
                }

                // Submit AJAX filter
                submitAjaxFilter(filterForm);
            });
        });

        // Handle search input (with debounce)
        const searchInput = filterForm.querySelector('.zz-filter-search');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    submitAjaxFilter(filterForm);
                }, 500); // 500ms debounce
            });

            // Trigger immediately on Enter key
            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimeout);
                    submitAjaxFilter(filterForm);
                }
            });
        }

        // "Show More" functionality for Chips
        const showMoreBtns = filterForm.querySelectorAll('.zz-show-more-btn');
        showMoreBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                // The container is usually right before the button, or find closest parent's sibling
                const container = this.previousElementSibling;

                if (container && container.classList.contains('zz-chips-container')) {

                    // Check if we are expanding or collapsing
                    const hiddenItems = container.querySelectorAll('.zz-chip-hidden');
                    const isExpanding = hiddenItems.length > 0;

                    if (isExpanding) {
                        // Show all hidden items
                        container.querySelectorAll('.zz-chip-hidden').forEach(el => {
                            el.classList.remove('zz-chip-hidden');
                            el.classList.add('zz-was-hidden');
                            el.style.display = ''; // Ensure visibility
                        });

                        // Toggle texts
                        const moreText = this.querySelector('.show-more-text');
                        const lessText = this.querySelector('.show-less-text');
                        if (moreText) moreText.style.display = 'none';
                        if (lessText) lessText.style.display = 'inline';

                    } else {
                        // Collapse items that were previously hidden
                        container.querySelectorAll('.zz-was-hidden').forEach(el => {
                            el.classList.add('zz-chip-hidden');
                            el.classList.remove('zz-was-hidden');
                        });

                        // Toggle texts
                        const moreText = this.querySelector('.show-more-text');
                        const lessText = this.querySelector('.show-less-text');
                        if (moreText) moreText.style.display = 'inline';
                        if (lessText) lessText.style.display = 'none';
                    }
                }
            });
        });
    }

    /**
     * Submit filter form via AJAX
     */
    /**
  * Submit filter form via AJAX (FIXED for Empty State)
  */

    /**
  * Submit filter form via AJAX (Subtle Opacity Loader)
  */
    function submitAjaxFilter(form) {
        // Target the MAIN CONTENT WRAPPER
        const contentWrapper = document.querySelector('.zz-archive-content');

        if (!contentWrapper) return;

        // Toggle Reset Button Visibility Instantly
        const resetWrap = document.querySelector('.zz-filter-reset-wrap');
        if (resetWrap) {
            // Collect current state
            const formData = new FormData(form);
            const hasActiveFilters = Array.from(formData.values()).some(val => val.trim() !== '');

            if (hasActiveFilters) {
                resetWrap.style.display = 'block';
            } else {
                resetWrap.style.display = 'none';
            }
        }

        // Subtle Visual Cue: Just dim the content slightly
        contentWrapper.style.opacity = '0.5';
        contentWrapper.style.transition = 'opacity 0.2s ease';

        // Build FormData from form
        const formData = new FormData(form);

        // Get current page URL
        const currentUrl = new URL(window.location.href);
        const baseUrl = currentUrl.pathname + currentUrl.search;

        // Build query string from form data
        const params = new URLSearchParams();
        for (const [key, value] of formData.entries()) {
            params.append(key, value);
        }

        // Reset pagination to page 1 on filter change
        params.set('paged', '1');

        const queryString = params.toString();
        const requestUrl = baseUrl.split('?')[0] + (queryString ? '?' + queryString : '');

        // Fetch filtered results
        fetch(requestUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Find the new content wrapper in response
                const newContentWrapper = doc.querySelector('.zz-archive-content');

                if (newContentWrapper) {
                    // Replace ENTIRE content
                    contentWrapper.innerHTML = newContentWrapper.innerHTML;

                    // Restore opacity (Subtle effect ends)
                    contentWrapper.style.opacity = '1';

                    // Scroll to top of content smoothly
                    setTimeout(() => {
                        contentWrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);

                    // Re-attach handlers
                    attachPaginationHandlers();
                }

                // Update URL without reload
                if (queryString) {
                    window.history.pushState(
                        { filter: queryString },
                        '',
                        requestUrl
                    );
                }
            })
            .catch(error => {
                console.error('Filter error:', error);
                contentWrapper.style.opacity = '1';
                // Fallback: reload page only on critical error
                window.location.href = requestUrl;
            });
    }

    /**
     * Show More/Less Button for Filter Pills
     */

    function initShowMoreLess() {
        // Event listener on 'document' so it works after AJAX
        document.addEventListener('click', function (e) {

            // 1. Check if click is on "Show More" button
            const button = e.target.closest('.zz-show-more-btn');
            if (!button) return;

            e.preventDefault();

            // 2. Find container (Chips)
            const container = button.closest('.zz-chips-container');
            if (!container) return;

            // 3. Find items (to show/hide)
            const items = container.querySelectorAll('.zz-chip');

            // 4. Find texts (Show More / Show Less)
            const showMoreText = button.querySelector('.show-more-text');
            const showLessText = button.querySelector('.show-less-text');

            // 5. Check if currently Expanded or Collapsed
            const isExpanded = container.classList.contains('expanded');

            if (isExpanded) {
                // ---> ACTION: COLLAPSE (Hide)
                container.classList.remove('expanded');

                // Hide items from index 6 onwards
                items.forEach((item, index) => {
                    if (index >= 6) {
                        item.classList.add('zz-chip-hidden');
                        item.style.display = 'none'; // Force Hide
                    }
                });

                // Button Text Update
                if (showMoreText) showMoreText.style.display = 'inline';
                if (showLessText) showLessText.style.display = 'none';

            } else {
                // ---> ACTION: EXPAND (Show)
                container.classList.add('expanded');

                // Show all
                items.forEach((item) => {
                    item.classList.remove('zz-chip-hidden');
                    item.style.display = ''; // Reset display
                });

                // Button Text Update
                if (showMoreText) showMoreText.style.display = 'none';
                if (showLessText) showLessText.style.display = 'inline';
            }
        });
    }

    /**
     * Attach handlers to pagination links to preserve filters
     */
    /**
     * Attach handlers to pagination links to preserve filters (FIXED)
     */
    function attachPaginationHandlers() {
        const paginationLinks = document.querySelectorAll('.navigation.pagination a');

        paginationLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                if (href) {
                    // Fetch with current filters
                    const filterForm = document.getElementById('zz-filter-form');
                    if (filterForm) {
                        // Extract page number from URL
                        const urlParams = new URL(href, window.location.origin).searchParams;
                        const page = urlParams.get('paged') || '1';

                        // Build new URL with filters
                        const formData = new FormData(filterForm);
                        const params = new URLSearchParams(formData);
                        params.set('paged', page);

                        const newUrl = window.location.pathname + '?' + params.toString();

                        // Visual loading on wrapper
                        const contentWrapper = document.querySelector('.zz-archive-content');
                        if (contentWrapper) contentWrapper.style.opacity = '0.6';

                        fetch(newUrl, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                            .then(response => response.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');

                                // CHANGE: Target Wrapper
                                const newContentWrapper = doc.querySelector('.zz-archive-content');

                                if (newContentWrapper && contentWrapper) {
                                    contentWrapper.innerHTML = newContentWrapper.innerHTML;
                                    contentWrapper.style.opacity = '1';
                                    contentWrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });

                                    // Re-attach handlers
                                    attachPaginationHandlers();
                                }

                                // Update URL
                                window.history.pushState({ filter: params.toString() }, '', newUrl);
                            })
                            .catch(error => {
                                console.error('Pagination error:', error);
                                window.location.href = href;
                            });
                    }
                }
            });
        });
    }

    function init() {
        initToast();
        initMobileMenu();
        initCopySystem();
        initPromptExpandSystem();
        initClassicPromptExpand(); // Classic Single Prompt expand
        initLikeSystem();
        initSmoothScroll();
        initFeaturedImageLightbox();
        initInstantAjaxFilters(); // Initialize AJAX filters
        initShowMoreLess(); // Initialize Show More/Less for filter lists
        initBlogInstantSearch(); // Initialize blog instant search
    }

    /**
     * Blog Instant Search (AJAX)
     */
    function initBlogInstantSearch() {
        const searchInput = document.getElementById('zz-blog-search-input');

        // Blog grid selector
        const blogGrid = document.querySelector('.zz-blog-grid') || document.querySelector('.blog-posts-grid');

        if (!searchInput || !blogGrid) return;

        // Determine which layout we're using
        const isModernLayout = blogGrid.classList.contains('zz-blog-grid');

        let searchTimeout;
        let originalContent = blogGrid.innerHTML; // Store original cards
        let isSearching = false;

        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 2) {
                // Restore original content
                blogGrid.style.opacity = '1';
                blogGrid.innerHTML = originalContent;
                isSearching = false;
                return;
            }

            searchTimeout = setTimeout(() => {
                isSearching = true;
                // Show loading state with opacity
                blogGrid.style.opacity = '0.5';
                blogGrid.style.transition = 'opacity 0.2s ease';

                fetchBlogSearchResults(query, blogGrid, originalContent, isModernLayout);
            }, 300);
        });

        // Reset on escape
        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                searchInput.value = '';
                blogGrid.style.opacity = '1';
                blogGrid.innerHTML = originalContent;
                isSearching = false;
            }
        });
    }

    /**
     * Fetch Blog Search Results via AJAX
     */
    function fetchBlogSearchResults(query, blogGrid, originalContent, isModernLayout) {
        const ajaxUrl = window.zzprompts_vars ? window.zzprompts_vars.ajax_url : '/wp-admin/admin-ajax.php';

        const formData = new FormData();
        formData.append('action', 'zzprompts_search_blog');
        formData.append('query', query);
        formData.append('nonce', window.zzprompts_vars ? window.zzprompts_vars.nonce : '');

        fetch(ajaxUrl, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    let html = '';
                    data.data.forEach(post => {
                        const imageHtml = post.image
                            ? `<img src="${post.image}" alt="${post.title}">`
                            : '';

                        if (isModernLayout) {
                            // Modern Card Template (BEM naming)
                            const categoryHtml = post.category 
                                ? `<a href="${post.category_url || '#'}" class="zz-blog-card__category">${post.category}</a>` 
                                : '';
                            
                            html += `
                            <article class="zz-blog-card">
                                <a href="${post.url}" class="zz-blog-card__image-wrapper">
                                    <div class="zz-blog-card__image">
                                        ${imageHtml}
                                    </div>
                                    <span class="zz-blog-card__date">${post.date}</span>
                                </a>

                                <div class="zz-blog-card__content">
                                    <div class="zz-blog-card__meta">
                                        ${categoryHtml}
                                        <span class="zz-blog-card__reading-time">
                                            <i class="far fa-clock"></i> ${post.reading_time}
                                        </span>
                                    </div>

                                    <h3 class="zz-blog-card__title">
                                        <a href="${post.url}">${post.title}</a>
                                    </h3>

                                    <p class="zz-blog-card__excerpt">${post.excerpt}</p>

                                    <a href="${post.url}" class="zz-blog-card__link">
                                        Read Article <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </article>
                        `;
                        } else {
                            // Classic Card Template
                            html += `
                            <article class="zz-blog-card">
                                ${post.image ? `<a href="${post.url}" class="zz-card-image"><img src="${post.image}" alt="${post.title}"></a>` : ''}
                                <div class="zz-card-content">
                                    <header class="entry-header">
                                        <div class="entry-meta">
                                            <span class="meta-date">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="meta-icon"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                                ${post.date}
                                            </span>
                                            ${post.category ? `<span class="meta-sep">&bull;</span><span class="meta-cat">${post.category}</span>` : ''}
                                        </div>
                                        <h2 class="entry-title"><a href="${post.url}">${post.title}</a></h2>
                                    </header>
                                    <div class="entry-summary"><p>${post.excerpt}</p></div>
                                    <footer class="entry-footer mt-auto">
                                        <a href="${post.url}" class="read-more-link">
                                            Read Article
                                            <svg class="icon-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                        </a>
                                    </footer>
                                </div>
                            </article>
                        `;
                        }
                    });
                    blogGrid.innerHTML = html;
                } else {
                    blogGrid.innerHTML = '<div class="no-search-results" style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #9ca3af;">No posts found matching your search.</div>';
                }

                // Restore opacity after content is updated
                blogGrid.style.opacity = '1';
            })
            .catch(error => {
                console.error('Search error:', error);
                // Restore original content on error
                blogGrid.innerHTML = originalContent;
                blogGrid.style.opacity = '1';
            });
    }

    /**
     * Comments - Enhanced UX
     * Handle comment form errors and prevent page redirects
     */
    function initComments() {
        var commentsSection = document.querySelector('.zz-comments-section');
        if (!commentsSection) return;

        var commentForm = commentsSection.querySelector('form.zz-comment-form');
        if (!commentForm) return;

        // Handle form submission with AJAX for better UX
        commentForm.addEventListener('submit', function (e) {
            var submitBtn = commentForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span style="opacity:0.7">Posting...</span>';
            }
        });

        // Remove existing inline errors on form interaction
        commentForm.addEventListener('focusin', function () {
            var existingErrors = commentsSection.querySelectorAll('.zz-comment-error-inline');
            existingErrors.forEach(function (err) {
                err.remove();
            });
        });
    }

    /**
     * Homepage Live Search (Prompts)
     */
    function initHomepageLiveSearch() {
        const searchForms = document.querySelectorAll('.zz-live-search-form');

        searchForms.forEach(form => {
            const input = form.querySelector('.zz-live-search-input');
            const resultsContainer = form.querySelector('.zz-live-search-results');
            let debounceTimeout;

            if (!input || !resultsContainer) return;

            // Input Listener
            input.addEventListener('input', function () {
                const term = this.value.trim();
                clearTimeout(debounceTimeout);

                if (term.length < 2) {
                    resultsContainer.classList.remove('active');
                    resultsContainer.innerHTML = '';
                    return;
                }

                // Show Loading
                resultsContainer.classList.add('active');
                resultsContainer.innerHTML = '<div class="zz-search-loading">Searching...</div>';

                debounceTimeout = setTimeout(() => {
                    fetchSearchResults(term, resultsContainer);
                }, 300); // 300ms wait (Debounce)

            });

            // Hide when clicking outside
            document.addEventListener('click', function (e) {
                if (!form.contains(e.target)) {
                    resultsContainer.classList.remove('active');
                }
            });
        });
    }

    // Fetch Function
    function fetchSearchResults(term, container) {
        // Safe check for vars
        if (!window.zzprompts_vars || !window.zzprompts_vars.ajax_url) return;

        const formData = new FormData();
        formData.append('action', 'zzprompts_ajax_search_prompts');
        formData.append('term', term);
        formData.append('nonce', window.zzprompts_vars.nonce);

        fetch(window.zzprompts_vars.ajax_url, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    let html = '';
                    data.data.forEach(item => {
                        const toolBadge = item.tool ? `<span class="zz-search-tool">${item.tool}</span>` : '';
                        html += `
                        <a href="${item.url}" class="zz-search-item">
                            <span class="zz-search-title">${item.title}</span>
                            ${toolBadge}
                        </a>
                    `;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="zz-search-loading">No prompts found.</div>';
                }
            })
            .catch(err => {
                container.classList.remove('active');
            });
    }

    /**
     * Theme Toggle (Dark/Light Mode)
     */
   function initThemeToggle() {
        const toggle = document.getElementById('zz-theme-toggle');
        if (!toggle) return;

        // Helper to set theme
        const setTheme = (theme, saveToStorage = false) => {
            document.documentElement.setAttribute('data-theme', theme);
            if (saveToStorage) {
                localStorage.setItem('zz-theme', theme);
            }
        };

        // Click Handler
        toggle.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme');
            const newTheme = current === 'dark' ? 'light' : 'dark';
            setTheme(newTheme, true); // Only save when clicked
        });

        // System Preference Listener
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('zz-theme')) {
                setTheme(e.matches ? 'dark' : 'light', false);
            }
        });
        
        // No need to run setTheme() on load here, header.php already did it.
    }

    /**
     * Back to Top Button
     */
    function initBackToTop() {
        const btn = document.getElementById('zz-back-to-top');
        if (!btn) return;

        const toggleVisibility = () => {
            if (window.scrollY > 300) {
                btn.classList.add('is-visible');
            } else {
                btn.classList.remove('is-visible');
            }
        };

        window.addEventListener('scroll', toggleVisibility, { passive: true });

        btn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        toggleVisibility();
    }

    /**
     * Smart Terminal (Single Prompt Page)
     * Short prompts: auto height, no expand button
     * Long prompts: collapsed with fade, expand/collapse toggle
     */
    function initSmartTerminal() {
        const terminals = document.querySelectorAll('.zz-terminal__body');
        const SHORT_THRESHOLD = 180; // pixels - below this, show full

        terminals.forEach(terminal => {
            const code = terminal.querySelector('.zz-terminal__code');
            const expandBtn = terminal.querySelector('.zz-terminal__expand');
            if (!code) return;

            // Measure true height
            const originalMaxHeight = terminal.style.maxHeight;
            terminal.style.maxHeight = 'none';
            terminal.style.overflow = 'visible';
            const scrollHeight = code.scrollHeight;
            terminal.style.maxHeight = originalMaxHeight;
            terminal.style.overflow = '';

            // If content is short, mark as short (no expand needed)
            if (scrollHeight <= SHORT_THRESHOLD) {
                terminal.classList.add('is-short');
                return;
            }

            // Long prompt - setup expand/collapse
            if (expandBtn) {
                expandBtn.addEventListener('click', () => {
                    const isExpanded = terminal.classList.contains('is-expanded');

                    if (isExpanded) {
                        // Collapse
                        expandBtn.querySelector('.expand-text').style.display = '';
                        expandBtn.querySelector('.collapse-text').style.display = 'none';
                        expandBtn.querySelector('i').classList.remove('fa-chevron-up');
                        expandBtn.querySelector('i').classList.add('fa-chevron-down');

                        // Reset internal scroll and then remove class
                        terminal.scrollTop = 0;
                        terminal.classList.remove('is-expanded');

                        // Center terminal in viewport
                        terminal.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    } else {
                        // Expand
                        terminal.classList.add('is-expanded');
                        expandBtn.querySelector('.expand-text').style.display = 'none';
                        expandBtn.querySelector('.collapse-text').style.display = '';
                        expandBtn.querySelector('i').classList.remove('fa-chevron-down');
                        expandBtn.querySelector('i').classList.add('fa-chevron-up');
                    }
                });
            }
        });
    }

    /**
     * Reading Progress Bar for Blog Single Pages
     * Throttled scroll listener for performance
     */
    function initReadingProgressBar() {
        const progressBar = document.getElementById('zzReadProgressBar');
        if (!progressBar) return;

        const article = document.querySelector('.zz-blog-article') || document.querySelector('.zz-blog-main');
        if (!article) return;

        let ticking = false;

        function updateProgress() {
            const articleRect = article.getBoundingClientRect();
            const articleTop = articleRect.top + window.scrollY;
            const articleHeight = article.offsetHeight;
            const windowHeight = window.innerHeight;
            const scrollY = window.scrollY;

            // Calculate progress
            const start = articleTop - windowHeight * 0.3;
            const end = articleTop + articleHeight - windowHeight * 0.5;
            const range = end - start;
            const progress = Math.min(100, Math.max(0, ((scrollY - start) / range) * 100));

            progressBar.style.width = progress + '%';
            ticking = false;
        }

        window.addEventListener('scroll', function () {
            if (!ticking) {
                window.requestAnimationFrame(updateProgress);
                ticking = true;
            }
        }, { passive: true });

        // Initial call
        updateProgress();
    }

  // DOM Ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            init();
            initComments(); // <--- FIXED: Matches the function name on line 868
            initHomepageLiveSearch();
            initThemeToggle();
            initBackToTop();
            initSmartTerminal();
            initReadingProgressBar();
        });
    } else {
        init();
        initComments(); // <--- FIXED
        initHomepageLiveSearch();
        initThemeToggle();
        initBackToTop();
        initSmartTerminal();
        initReadingProgressBar();
    }

})();
