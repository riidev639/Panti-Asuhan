import './bootstrap';
import { upload } from '@vercel/blob/client';

const limits = {
    photo: {
        bytes: 10 * 1024 * 1024,
        types: ['image/jpeg', 'image/png', 'image/webp'],
        label: 'foto',
    },
    video: {
        bytes: 30 * 1024 * 1024,
        types: ['video/mp4', 'video/quicktime', 'video/webm', 'video/x-matroska'],
        label: 'video',
    },
};

function safeFilename(filename) {
    const cleaned = filename
        .normalize('NFKD')
        .replace(/[^a-zA-Z0-9._-]+/g, '-')
        .replace(/^-+|-+$/g, '');

    return (cleaned || 'file').slice(-100);
}

function detectedContentType(file, kind) {
    if (file.type) {
        return file.type;
    }

    const extension = file.name.split('.').pop()?.toLowerCase();
    const fallbacks = kind === 'photo'
        ? { jpg: 'image/jpeg', jpeg: 'image/jpeg', png: 'image/png', webp: 'image/webp' }
        : { mp4: 'video/mp4', mov: 'video/quicktime', webm: 'video/webm', mkv: 'video/x-matroska' };

    return fallbacks[extension] || '';
}

function addHiddenField(form, name, value) {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = String(value ?? '');
    form.appendChild(input);
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form[data-blob-upload]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            if (form.dataset.blobEnabled !== '1' || form.dataset.uploading === '1') {
                return;
            }

            const kind = form.dataset.blobUpload;
            const settings = limits[kind];
            const fileInput = form.querySelector(`input[type="file"][name="${kind}"]`);
            const file = fileInput?.files?.[0];

            // Editing only the title/description does not require a new upload.
            if (!settings || !file) {
                return;
            }

            event.preventDefault();

            const errorBox = form.querySelector('[data-upload-error]');
            const progress = form.querySelector('[data-upload-progress]');
            const submitButton = form.querySelector('button[type="submit"]');
            const contentType = detectedContentType(file, kind);

            if (!settings.types.includes(contentType)) {
                if (errorBox) {
                    errorBox.textContent = `Format ${settings.label} tidak didukung.`;
                    errorBox.classList.remove('hidden');
                }
                return;
            }

            if (file.size > settings.bytes) {
                if (errorBox) {
                    errorBox.textContent = `Ukuran ${settings.label} melebihi batas yang diperbolehkan.`;
                    errorBox.classList.remove('hidden');
                }
                return;
            }

            form.dataset.uploading = '1';
            if (errorBox) errorBox.classList.add('hidden');
            if (progress) {
                progress.textContent = 'Menyiapkan upload…';
                progress.classList.remove('hidden');
            }
            if (submitButton) submitButton.disabled = true;

            try {
                const blob = await upload(
                    `${kind}s/${Date.now()}-${safeFilename(file.name)}`,
                    file,
                    {
                        access: 'public',
                        handleUploadUrl: '/api/blob-upload',
                        clientPayload: JSON.stringify({ kind }),
                        contentType,
                        multipart: file.size > 4 * 1024 * 1024,
                        onUploadProgress: ({ percentage }) => {
                            if (progress) {
                                progress.textContent = `Mengupload ${settings.label}… ${Math.round(percentage)}%`;
                            }
                        },
                    },
                );

                fileInput.disabled = true;
                addHiddenField(form, 'blob_url', blob.url);
                addHiddenField(form, 'blob_pathname', blob.pathname);
                addHiddenField(form, 'blob_mime_type', contentType);
                addHiddenField(form, 'blob_size', file.size);
                addHiddenField(form, 'blob_original_name', file.name);

                if (progress) progress.textContent = 'Menyimpan data…';
                HTMLFormElement.prototype.submit.call(form);
            } catch (error) {
                console.error(error);
                form.dataset.uploading = '0';
                if (submitButton) submitButton.disabled = false;
                if (progress) progress.classList.add('hidden');
                if (errorBox) {
                    errorBox.textContent = 'Upload gagal. Pastikan sesi login masih aktif, lalu coba lagi.';
                    errorBox.classList.remove('hidden');
                }
            }
        });
    });
});
