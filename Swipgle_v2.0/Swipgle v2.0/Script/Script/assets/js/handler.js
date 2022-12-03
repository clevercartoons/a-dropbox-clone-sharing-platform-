(function($, Dropzone) {

    "use strict";

    let UploadUrl = getConfig.baseURL + '/' + getConfig.lang + '/upload',
        deleteUrl = getConfig.baseURL + '/' + getConfig.lang + '/uploads/delete',
        transferFilesForm = $('.transfer-files-form'),
        dataDzTotalSize = document.querySelector('[data-dz-totalsize]'),
        uploadZoneFiles = $('#uploadZone-files'),
        transferButton = $('#transfer-btn');
    var uploadedDocumentMap = {}
    window.totalSize = 0;
    let previewNode = document.querySelector('#preview-template');
    previewNode.id = "";
    let previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);
    var dropzoneConfig = {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: UploadUrl,
            method: 'POST',
            paramName: 'file',
            filesizeBase: 1024,
            maxFilesize: null,
            maxFiles: null,
            previewTemplate: previewTemplate,
            addRemoveLinks: true,
            parallelUploads: 10,
            timeout: 0,
            chunking: true,
            forceChunking: true,
            chunkSize: 104857600,
            retryChunks: true,
        },
        dropzoneConfig = Object.assign({}, dropzoneConfig, dropzoneOptions);

    Dropzone.autoDiscover = false;
    var dropzone = new Dropzone("#upload-previews", dropzoneConfig);

    function changeFilesWordByTotal() {
        if (dropzone.files.length == 1) {
            uploadZoneFiles.html(getUploadConfig.singleFile);
        } else {
            uploadZoneFiles.html(getUploadConfig.multipleFiles);
        }
    }

    function onFileAdd(file) {
        changeFilesWordByTotal();
        transferButton.prop('disabled', true);
        if (getUploadConfig.subscribed != 0 && getUploadConfig.subscriptionExpired != 1 && getUploadConfig.subscriptionCanceled != 1) {
            window.totalSize += file.size;
            dataDzTotalSize.textContent = `(${formatBytes(window.totalSize)})`;
            if (getUploadConfig.userRemainingStorageSpace != "") {
                if (window.totalSize > getUploadConfig.userRemainingStorageSpace) {
                    transferButton.prop('disabled', false);
                    toastr.error(getUploadConfig.insufficientStorageSpaceError);
                    this.removeFile(file);
                }
            }
            if (getUploadConfig.maxTransferSize != "") {
                if (window.totalSize > getUploadConfig.maxTransferSize) {
                    transferButton.prop('disabled', false);
                    toastr.error(getUploadConfig.maxTransferSizeError);
                    this.removeFile(file);
                }
            }
        } else {
            transferButton.prop('disabled', false);
            if (getUploadConfig.subscriptionCanceled == 1) {
                toastr.error(getUploadConfig.subscriptionCanceledError);
            } else {
                toastr.error(getUploadConfig.unsubscribedError);
            }
            this.removeFile(file);
        }
    }

    function onSending(file, xhr, formData) {
        formData.append('total_size', window.totalSize);
    }

    function onUploadprogress(file, progress, bytesSent) {
        if (file.previewElement) {
            const preview = $(file.previewElement);
            const uploadFilePercentage = preview.find(".dz-percentage");
            file.previewElement.querySelector("[data-dz-percentage]").textContent = `(${progress.toFixed(0)}%)`;
            if (progress.toFixed(0) == 100) {
                uploadFilePercentage.html('');
                uploadFilePercentage.append('<div class="spinner-border spinner-border-sm text-muted" role="status"><span class="visually-hidden"></span></div>');
            }
        }
    }

    function onFileError(file = null, message = null) {
        const preview = $(file.previewElement);
        preview.removeClass('dz-success');
        preview.addClass('dz-error');
        toastr.error(message + ' (' + file.name + ')');
    }

    function onUploadComplete(file) {
        if (dropzone.getUploadingFiles().length === 0 && dropzone.getQueuedFiles().length === 0) {
            transferButton.prop('disabled', false);
        }
        if (file.status == "success") {
            const preview = $(file.previewElement);
            const response = JSON.parse(file.xhr.response);
            const uploadFilePercentage = preview.find(".dz-percentage");
            uploadFilePercentage.html('(100%)');
            if (response.type == 'success') {
                transferFilesForm.append('<input type="hidden" name="files[]" value="' + response.id + '">');
                uploadedDocumentMap[file.upload.uuid] = response.id;
            } else {
                preview.removeClass('dz-success');
                preview.addClass('dz-error');
                toastr.error(response.msg);
            }
        }
    }

    function onRemovedfile(file) {
        changeFilesWordByTotal();
        window.totalSize -= file.size;
        dataDzTotalSize.textContent = `(${formatBytes(window.totalSize)})`;
        if (dropzone.files.length == 0) {
            dataDzTotalSize.textContent = '';
        }
        let fileId = uploadedDocumentMap[file.upload.uuid];
        if (fileId) {
            transferFilesForm.find('input[name="files[]"][value="' + fileId + '"]').remove();
            $.ajax({
                url: deleteUrl,
                type: "POST",
                data: { id: fileId },
                success: function(response) {
                    if (response.error) {
                        toastr.error(response.error);
                    }
                }
            });
        }
    }

    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    let removeAll = document.querySelectorAll('[data-remove-files]');
    removeAll.forEach((el) => {
        el.onclick = () => {
            Dropzone.forElement("#upload-previews").removeAllFiles(true);
        };
    });

    let clickable = document.querySelectorAll('[data-upload-btn]');
    clickable.forEach((el) => {
        el.onclick = () => {
            document.querySelector('#upload-previews').click();
        };
    });

    window.addEventListener('DOMSubtreeModified', () => {
        let uploadFilesCount = document.querySelector('[data-upload-count]'),
            uploadBoxInfo = document.querySelector('.upload-box-files-info');
        if (document.querySelector('#upload-previews').children.length > 0) {
            uploadFilesCount.textContent = document.querySelector('#upload-previews').children.length;
            uploadBoxInfo.classList.add('active');
        } else {
            uploadFilesCount.textContent = '0';
            uploadBoxInfo.classList.remove('active');
        }
    });

    setInterval(() => {
        let hiddenInput = document.querySelector('.dz-hidden-input');
        hiddenInput.setAttribute('title', '');
    }, 0);

    window.addEventListener('dragover', () => {
        let hiddenInput = document.querySelector('.dz-hidden-input');
        hiddenInput.ondragover = () => {
            document.querySelector('#drag-box').classList.add('active');
            document.querySelector('.upload-box').classList.add('hidden');
            document.getElementById('upload-box').classList.remove('active');
            document.getElementById('upload-box').classList.remove('animated');
        };
        hiddenInput.ondragleave = () => {
            document.querySelector('#drag-box').classList.remove('active');
            document.querySelector('.upload-box').classList.remove('hidden');
        };
        hiddenInput.ondrop = () => {
            document.querySelector('#drag-box').classList.remove('active');
            document.querySelector('.upload-box').classList.remove('hidden');
            document.getElementById('upload-box').classList.add('active');
            setTimeout(() => {
                document.getElementById('upload-box').classList.add('animated');
            }, 20);
        };
    });

    window.addEventListener('change', () => {
        if (document.querySelector('#upload-previews').children.length > 0) {
            let uploadBoxBtn = document.getElementById('upload-btn');
            uploadBoxBtn.style.display = 'none';
            document.getElementById('uploaded-box').classList.remove('active');
            document.getElementById('uploaded-box').classList.remove('animated');
            document.getElementById('upload-box').classList.add('active');
            setTimeout(() => {
                document.getElementById('upload-box').classList.add('animated');
            }, 20);
        }
    });

    setInterval(() => {
        let fileName = document.querySelectorAll('[data-dz-name]');
        fileName.forEach(function(el, id) {
            if (fileName[id].textContent.length > 23) {
                var fileTextName = fileName[id].textContent.slice(0, 18),
                    fileTextExt = fileName[id].textContent.slice(fileName[id].textContent.length - 4);
                fileName[id].textContent = fileTextName + '..' + fileTextExt;
            }
        });
    }, 0);


    let transferBtn = $('#transfer-btn'),
        sendFilesForm = $('#send-files'),
        createLinkForm = $('#create-link');

    let DeleteAllTags = () => {
        let tagCloseBtn = document.querySelectorAll(".tags-input-wrapper .tag a"),
            tagsContainer = document.querySelector(".tags-input-wrapper input");
        tagCloseBtn.forEach((el) => {
            el.click();
        });
        tagsContainer.blur();
    };

    let resetOp = () => {
        let closeOptionsBtn = document.querySelectorAll('[data-close-options]');
        closeOptionsBtn.forEach((el) => {
            el.click();
        });
    };

    let uploadBox = document.getElementById('upload-box'),
        uploadedBox = document.getElementById('uploaded-box'),
        newTransferBtn = document.getElementById('new-transfer');

    let transferDone = () => {
        uploadBox.classList.remove('active');
        uploadBox.classList.remove('animated');
        uploadedBox.classList.add('active');
        setTimeout(() => {
            uploadedBox.classList.add('animated');
        }, 20);
    };

    newTransferBtn.onclick = () => {
        uploadedBox.classList.remove('active');
        uploadedBox.classList.remove('animated');
        uploadBox.classList.add('active');
        setTimeout(() => {
            uploadBox.classList.add('animated');
        }, 20);
    };

    let updateTransferDeatilsCard = (response) => {

        let transferExpiryOn = $('#transfer-expiry-on'),
            filesUploadedExpiryText = $('#files-uploaded-expiry-text'),
            facebookBtn = $('.facebook'),
            twitterBtn = $('.twitter'),
            linkedinBtn = $('.linkedin'),
            whatsappBtn = $('.whatsapp'),
            InputLink = $('#input-link'),
            openDownloadLink = $('#open-download-link'),
            viewTransferBtn = $('#view-taransfer'),
            clientUsedSpace = $('#client-used-space'),
            clientUsedSpaceProgress = $('#client-used-space-progress'),
            transferCompletedAudio = new Audio(getConfig.baseURL + "/assets/media/transfer-completed.mp3"),
            ratingBox = $('#rating-box');

        if (response.rating == true) {
            if (ratingBox.length) {
                ratingBox.remove();
            }
        }

        clientUsedSpace.html(response.subscription.used_space);
        clientUsedSpaceProgress.css('width', response.subscription.used_percentage + '%');
        clientUsedSpaceProgress.attr('aria-valuenow', response.subscription.used_percentage);
        if (response.subscription.used_percentage > 80) {
            clientUsedSpaceProgress.removeClass('bg-success');
            clientUsedSpaceProgress.removeClass('bg-warning');
            clientUsedSpaceProgress.addClass('bg-danger');
        } else if (response.subscription.used_percentage > 50 && response.subscription.used_percentage < 80) {
            clientUsedSpaceProgress.removeClass('bg-success');
            clientUsedSpaceProgress.removeClass('bg-danger');
            clientUsedSpaceProgress.addClass('bg-warning');
        } else {
            clientUsedSpaceProgress.removeClass('bg-warning');
            clientUsedSpaceProgress.removeClass('bg-danger');
            clientUsedSpaceProgress.addClass('bg-success');
        }

        if ($.isEmptyObject(response.transfer_expiry_on)) {
            filesUploadedExpiryText.addClass('d-none');
        } else {
            filesUploadedExpiryText.removeClass('d-none');
            transferExpiryOn.html(response.transfer_expiry_on);
        }
        facebookBtn.attr('href', response.facebook_share_link);
        twitterBtn.attr('href', response.twitter_share_link);
        linkedinBtn.attr('href', response.linkedin_share_link);
        whatsappBtn.attr('href', response.whatsapp_share_link);
        InputLink.attr('value', response.transfer_download_link);
        openDownloadLink.attr('href', response.transfer_download_link);
        if (response.view_transfer_link != null) {
            viewTransferBtn.attr('href', response.view_transfer_link);
            viewTransferBtn.removeClass('disabled');
        } else {
            viewTransferBtn.addClass('disabled');
        }
        transferCompletedAudio.play();

        if (getUploadConfig.userRemainingStorageSpace != "") {
            getUploadConfig.userRemainingStorageSpace -= response.transfer_size;
        }
        document.querySelector("[autosize]").style.height = "44px";
    };

    $("body").on("click", '#transfer-btn', function(e) {
        e.preventDefault();
        transferBtn.prop('disabled', true);
        if (getUploadConfig.subscribed != 0) {
            if (sendFilesForm.parent().hasClass('active')) {
                const formData = sendFilesForm.serializeArray();
                const transferUrl = getConfig.baseURL + '/' + getConfig.lang + '/transfer/sendfiles';
                $.ajax({
                    url: transferUrl,
                    type: "POST",
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        transferBtn.prop('disabled', false);
                        if ($.isEmptyObject(response.error)) {
                            sendFilesForm.trigger("reset");
                            DeleteAllTags();
                            resetOp();
                            transferFilesForm.find('input[name="files[]"]').remove();
                            uploadedDocumentMap = {};
                            dropzone.removeAllFiles(true);
                            updateTransferDeatilsCard(response);
                            transferDone();
                        } else {
                            toastr.error(response.error);
                        }
                    }
                });
            } else if (createLinkForm.parent().hasClass('active')) {
                const formData = createLinkForm.serializeArray();
                const transferUrl = getConfig.baseURL + '/' + getConfig.lang + '/transfer/createlink';
                $.ajax({
                    url: transferUrl,
                    type: "POST",
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        transferBtn.prop('disabled', false);
                        if ($.isEmptyObject(response.error)) {
                            createLinkForm.trigger("reset");
                            resetOp();
                            transferFilesForm.find('input[name="files[]"]').remove();
                            uploadedDocumentMap = {};
                            dropzone.removeAllFiles(true);
                            updateTransferDeatilsCard(response);
                            transferDone();
                        } else {
                            toastr.error(response.error);
                        }
                    }
                });
            }
        } else {
            transferBtn.prop('disabled', false);
            toastr.error(getUploadConfig.unsubscribedError);
        }
    });
    dropzone.on('addedfile', onFileAdd);
    dropzone.on('sending', onSending);
    dropzone.on('uploadprogress', onUploadprogress);
    dropzone.on('removedfile', onRemovedfile);
    dropzone.on('error', onFileError);
    dropzone.on('complete', onUploadComplete);
})(jQuery, Dropzone);