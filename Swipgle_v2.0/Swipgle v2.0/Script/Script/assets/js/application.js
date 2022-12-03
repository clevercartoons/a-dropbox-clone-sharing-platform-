(function($) {
    "use strict";

    document.querySelectorAll('[data-year]').forEach(function(el) {
        el.textContent = new Date().getFullYear();
    });
    $.LoadingOverlaySetup({
        imageColor: getConfig.LoadingOverlayColor,
    });
    $(document).on({
        ajaxStart: function() { $.LoadingOverlay("show"); },
        ajaxStop: function() { $.LoadingOverlay("hide"); },
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let clipboardBtn = document.querySelector("#copy-btn");
    if (clipboardBtn) {
        let clipboard = new ClipboardJS(clipboardBtn);
        clipboard.on('success', function(e) {
            toastr.success(getConfig.copiedToClipboardSuccess);
        });
    }
    var dropdown = document.querySelectorAll('[data-dropdown]'),
        dropdownv2 = document.querySelectorAll('[data-dropdown-v2]'),
        dropdownv3 = document.querySelectorAll('[data-dropdown-v3]');
    if (dropdown != null) {
        dropdown.forEach(function(el) {
            window.addEventListener('click', function(e) {
                if (el.contains(e.target)) {
                    el.classList.toggle('active');
                    setTimeout(function() {
                        el.classList.toggle('animated');
                    }, 0);
                } else {
                    el.classList.remove('active');
                    el.classList.remove('animated');
                }
            });
        });
    }
    if (dropdownv2 != null) {
        dropdownv2.forEach(function(el) {
            el.onclick = () => {
                el.classList.toggle('active');
                setTimeout(function() {
                    el.classList.toggle('animated');
                }, 0);
            };
        });
    }
    if (dropdownv3 != null) {
        dropdownv3.forEach(function(el) {
            window.addEventListener('click', function(e) {
                el.querySelector('.transfer-option-menu').style.top = e.clientY + 15 + 'px';
                el.querySelector('.transfer-option-menu').style.left = e.clientX - 130 + 'px';
                if (el.contains(e.target)) {
                    el.classList.toggle('active');
                    setTimeout(function() {
                        el.classList.toggle('animated');
                    }, 0);
                } else {
                    el.classList.remove('active');
                    el.classList.remove('animated');
                }
            });

            window.addEventListener('resize', () => {
                el.classList.remove('active');
                el.classList.remove('animated');
            });
        });
    }
    let userHeaderBtn = document.querySelectorAll('.upload-user-header-button'),
        userBodyItems = document.querySelectorAll('.upload-user-body-item');
    userHeaderBtn.forEach((el, id) => {
        if (el.hasAttribute("disabled") == false) {
            userHeaderBtn[id].onclick = () => {
                userHeaderBtn.forEach((el) => {
                    el.classList.remove('active');
                });
                userBodyItems.forEach((el) => {
                    el.classList.remove('active');
                });
                el.classList.add('active');
                userBodyItems[id].classList.add('active');
            };
        }
    });
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    let userOptions = document.querySelectorAll('.upload-user-option'),
        userOptionForms = document.querySelector('.upload-user-options-forms'),
        optionsButtons = document.querySelectorAll('.upload-user-footer-button'),
        closeOptions = document.querySelectorAll('[data-close-options]'),
        confirmOptions = document.querySelectorAll('[data-confirm-options]'),
        sendFiles = document.getElementById('send-files'),
        createLink = document.getElementById('create-link');
    userOptions.forEach((el, id) => {
        if (el.hasAttribute("disabled") == false) {
            el.onclick = () => {
                if (userOptionForms.children[id].classList.contains('active')) {
                    userOptionForms.classList.remove('active');
                    userOptionForms.children.forEach((el) => {
                        el.classList.remove('active');
                    });
                    optionsButtons[0].classList.add('active');
                    optionsButtons[1].classList.remove('active');
                } else {
                    userOptionForms.classList.add('active');
                    optionsButtons[0].classList.remove('active');
                    optionsButtons[1].classList.add('active');
                    userOptionForms.children.forEach((el) => {
                        el.classList.remove('active');
                    });
                    userOptionForms.children[id].classList.add('active');
                }
            };
        }
    });
    let filePassInput = document.querySelector(".file-password"),
        passwordOption = document.querySelector(".password-option");
    if (filePassInput) {
        filePassInput.onchange = () => {
            if (filePassInput.value != "") {
                passwordOption.querySelector("i").classList.remove("fa-lock-open");
                passwordOption.querySelector("i").classList.add("fa-lock");
            } else {
                passwordOption.querySelector("i").classList.add("fa-lock-open");
                passwordOption.querySelector("i").classList.remove("fa-lock");
            }
        };
    }
    closeOptions.forEach((el) => {
        el.onclick = () => {
            userOptionForms.querySelectorAll('input').forEach((el) => {
                el.value = '';
                el.checked = false;
                sendFiles.getElementsByClassName(el.className).forEach((el) => {
                    el.remove();
                });
                createLink.getElementsByClassName(el.className).forEach((el) => {
                    el.remove();
                });
            });
            passwordOption.querySelector("i").classList.add("fa-lock-open");
            passwordOption.querySelector("i").classList.remove("fa-lock");
            userOptionForms.classList.remove('active');
            userOptionForms.children.forEach((el) => {
                el.classList.remove('active');
            });
            optionsButtons[0].classList.add('active');
            optionsButtons[1].classList.remove('active');
        };
    });

    confirmOptions.forEach((el) => {
        el.onclick = () => {
            let passwordInput = document.querySelector('.upload-user-options-form .transfer-password'),
                expiryNotify = document.querySelector('.upload-user-options-form .expiry-notify'),
                downloadNotify = document.querySelector('.upload-user-options-form .download-notify'),
                expiryDateInput = document.querySelector('.upload-user-options-form .transfer-expiry-date');
            if (passwordInput.value != "" && getUploadConfig.transferPassword == 0) {
                toastr.error(getUploadConfig.transferPasswordError);
            } else if (expiryNotify.checked == true && getUploadConfig.transferNotify == 0) {
                toastr.error(getUploadConfig.transferNotifyError);
            } else if (downloadNotify.checked == true && getUploadConfig.transferNotify == 0) {
                toastr.error(getUploadConfig.transferNotifyError);
            } else if (expiryDateInput.value != "" && getUploadConfig.transferExpiry == 0) {
                toastr.error(getUploadConfig.transferExpiryError);
            } else {
                let optionsInputs = userOptionForms.querySelectorAll('input');
                optionsInputs.forEach((el) => {
                    let optionsInputsClone = el.cloneNode(),
                        optionsInputsClone2 = el.cloneNode(),
                        optionsValue = el.value,
                        optionsCheck = el.checked;
                    if (sendFiles.getElementsByClassName(el.className).length == 0) {
                        sendFiles.appendChild(optionsInputsClone);
                        createLink.appendChild(optionsInputsClone2);
                        optionsInputsClone.setAttribute('hidden', '');
                        optionsInputsClone2.setAttribute('hidden', '');

                        sendFiles.getElementsByClassName(el.className).forEach((el) => {
                            if (el.getAttribute('type') == 'checkbox') {
                                el.removeAttribute("value");
                            }
                        });
                        createLink.getElementsByClassName(el.className).forEach((el) => {
                            if (el.getAttribute('type') == 'checkbox') {
                                el.removeAttribute("value");
                            }
                        });
                    } else {
                        sendFiles.getElementsByClassName(el.className).forEach((el) => {
                            el.value = optionsValue;
                            if (el.getAttribute('type') == 'checkbox') {
                                el.checked = optionsCheck;
                                el.removeAttribute("value");
                            }
                        });
                        createLink.getElementsByClassName(el.className).forEach((el) => {
                            el.value = optionsValue;
                            if (el.getAttribute('type') == 'checkbox') {
                                el.checked = optionsCheck;
                                el.removeAttribute("value");
                            }
                        });
                    }

                    if (el.getAttribute("type") != 'checkbox' && optionsValue == "") {
                        sendFiles.getElementsByClassName(el.className).forEach((el) => {
                            if (el.value == "") {
                                el.remove();
                            }
                        });
                        createLink.getElementsByClassName(el.className).forEach((el) => {
                            if (el.value == "") {
                                el.remove();
                            }
                        });
                    }

                    if (el.getAttribute("type") == 'checkbox' && optionsCheck == false) {
                        sendFiles.getElementsByClassName(el.className).forEach((el) => {
                            if (el.checked == false || el.value == "") {
                                el.remove();
                            }
                        });
                        createLink.getElementsByClassName(el.className).forEach((el) => {
                            if (el.checked == false || el.value == "") {
                                el.remove();
                            }
                        });
                    }
                });
                userOptionForms.classList.remove('active');
                userOptionForms.children.forEach((el) => {
                    el.classList.remove('active');
                });
                optionsButtons[0].classList.add('active');
                optionsButtons[1].classList.remove('active');
            }
        }
    });
    let showTargetBtn = document.querySelectorAll('[data-password]');
    showTargetBtn.forEach((el) => {
        el.onclick = () => {
            let showTarget = document.querySelectorAll(`.${el.getAttribute('data-password')}`);
            showTarget.forEach((input) => {
                if (input.getAttribute('type') == 'password') {
                    input.setAttribute('type', 'text');
                    el.children[0].classList.add('d-none');
                    el.children[1].classList.remove('d-none');
                } else {
                    input.setAttribute('type', 'password');
                    el.children[0].classList.remove('d-none');
                    el.children[1].classList.add('d-none');
                }
            });
        };
    });
    let navbarIcon = document.querySelector('.nav-bar-menu-icon'),
        navbarLinks = document.querySelector('.nav-bar-links'),
        navbarClose = document.querySelectorAll('.nav-bar-links-close'),
        navbarLink = document.querySelectorAll('.nav-bar-link');
    if (navbarLinks != null) {
        navbarIcon.onclick = () => {
            navbarLinks.classList.add('active');
            setTimeout(() => {
                navbarLinks.classList.add('animated');
            }, 0);
        };
        navbarClose.forEach((el) => {
            el.onclick = () => {
                navbarLinks.classList.remove('active');
                navbarLinks.classList.remove('animated');
            };
        });
        navbarLink.forEach((el) => {
            el.onclick = () => {
                if (el.classList.contains("language") == false) {
                    navbarLinks.classList.remove('active');
                    navbarLinks.classList.remove('animated');
                }
            };
        });
    }
    let navBar = document.querySelector('.nav-bar');
    if (navBar != null) {
        let navbarHeight = () => {
            if (window.scrollY > 60) {
                navBar.classList.add('active');
            } else {
                navBar.classList.remove('active');
            }
        };
        window.addEventListener('scroll', navbarHeight);
        window.addEventListener('load', navbarHeight);
    }
    let links = document.querySelectorAll('[data-link]');
    if (links) {
        links.forEach((el) => {
            el.onclick = (e) => {
                e.preventDefault();
                let scrollTarget = document.querySelector(el.getAttribute('data-link')).offsetTop - 60;
                navbarLinks.classList.remove('active');
                navbarLinks.classList.remove('animated');
                window.scrollTo('0', scrollTarget);
            };
        });
    }
    let plans = document.querySelector(".plans"),
        planSwitcher = document.querySelector(".plan-switcher");
    if (planSwitcher) {
        planSwitcher.onclick = () => {
            planSwitcher.classList.toggle("yearly");
            plans.classList.toggle("yearly");
        };
    }
    let confirmFormBtn = $('.vr__confirm__action__form');
    confirmFormBtn.on('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: getConfig.alertActionTitle,
            text: getConfig.alertActionText,
            icon: 'question',
            showCancelButton: true,
            allowOutsideClick: false,
            focusConfirm: true,
            confirmButtonText: getConfig.alertActionConfirmButton,
            confirmButtonColor: getConfig.primaryColor,
            cancelButtonText: getConfig.alertActionCancelButton,
        }).then((result) => {
            if (result.isConfirmed) {
                $(this).parents('form')[0].submit();
            }
        })
    });
    let contactForm = $('#contactForm'),
        sendMessageBtn = $('#sendMessage');
    sendMessageBtn.on('click', function(e) {
        e.preventDefault();
        let formData = contactForm.serializeArray(),
            sendUrl = getConfig.baseURL + '/' + getConfig.lang + '/contact/send';
        sendMessageBtn.prop('disabled', true);
        $.ajax({
            url: sendUrl,
            type: "POST",
            data: formData,
            dataType: 'json',
            success: function(response) {
                sendMessageBtn.prop('disabled', false);
                if ($.isEmptyObject(response.error)) {
                    contactForm.trigger("reset");
                    grecaptcha.reset();
                    toastr.success(response.success);
                } else {
                    toastr.error(response.error);
                }
            }
        });
    });
    let downloadBtn = $('.download-btn'),
        downloadAllBtn = $('.download-all-btn');
    downloadBtn.on('click', function(e) {
        e.preventDefault();
        let id = $(this).data('id'),
            requestUrl = getConfig.baseURL + '/' + getConfig.lang + '/d/' + getDownloadConfig.transferIdentifier + '/single/request';
        downloadBtn.prop('disabled', true);
        $.ajax({
            url: requestUrl,
            type: "POST",
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                downloadBtn.prop('disabled', false);
                if ($.isEmptyObject(response.error)) {
                    window.location = response.download_link;
                } else {
                    toastr.error(response.error);
                }
            }
        });
    });
    downloadAllBtn.on('click', function(e) {
        e.preventDefault();
        let requestUrl = getConfig.baseURL + '/' + getConfig.lang + '/d/' + getDownloadConfig.transferIdentifier + '/all/request';
        downloadAllBtn.prop('disabled', true);
        $.ajax({
            url: requestUrl,
            type: "GET",
            dataType: 'json',
            success: function(response) {
                downloadAllBtn.prop('disabled', false);
                if ($.isEmptyObject(response.error)) {
                    window.location = response.download_link;
                } else {
                    toastr.error(response.error);
                }
            }
        });
    });
})(jQuery);